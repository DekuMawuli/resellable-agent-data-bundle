<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OrderStatusSyncService
{
    public function __construct(
        private readonly RealestApiService $realestApiService
    ) {}

    public function syncPendingProviderOrders(int $limit = 50, int $minAgeMinutes = 2): array
    {
        if (! $this->realestApiService->isReady()) {
            return [
                'synced' => 0,
                'updated' => 0,
                'failed' => 0,
                'skipped' => 0,
                'message' => 'Realest API is not configured.',
            ];
        }

        $lock = Cache::lock('orders:provider-status-sync', 120);

        return $lock->get(function () use ($limit, $minAgeMinutes) {
            $orders = Order::query()
                ->where('payment_made', true)
                ->whereNotNull('provider_reference')
                ->whereIn('status', ['pending', 'processing', 'queued', 'ongoing'])
                ->where('updated_at', '<=', now()->subMinutes(max(0, $minAgeMinutes)))
                ->orderBy('updated_at')
                ->limit($limit)
                ->get();

            $result = [
                'synced' => 0,
                'updated' => 0,
                'failed' => 0,
                'skipped' => 0,
                'message' => null,
            ];

            foreach ($orders as $order) {
                $syncResult = $this->syncOrder($order);
                $result['synced']++;

                if (($syncResult['updated'] ?? false) === true) {
                    $result['updated']++;

                    continue;
                }

                if (($syncResult['failed'] ?? false) === true) {
                    $result['failed']++;

                    continue;
                }

                $result['skipped']++;
            }

            return $result;
        }) ?? [
            'synced' => 0,
            'updated' => 0,
            'failed' => 0,
            'skipped' => 0,
            'message' => 'Another order sync is already running.',
        ];
    }

    public function syncOrder(Order $order, bool $force = false): array
    {
        if (blank($order->provider_reference)) {
            return [
                'updated' => false,
                'failed' => false,
                'status' => $order->status,
                'message' => 'Order has not been forwarded to the provider yet.',
            ];
        }

        if (! $this->realestApiService->isReady()) {
            return [
                'updated' => false,
                'failed' => true,
                'status' => $order->status,
                'message' => 'Realest API is not configured.',
            ];
        }

        if (! $force && $order->updated_at && $order->updated_at->gt(now()->subMinutes(2))) {
            return [
                'updated' => false,
                'failed' => false,
                'status' => $order->status,
                'message' => 'Order was checked recently.',
            ];
        }

        try {
            $response = $this->realestApiService->getOrderStatus($order->provider_reference);

            if (($response['status'] ?? 'error') !== 'success') {
                return [
                    'updated' => false,
                    'failed' => true,
                    'status' => $order->status,
                    'message' => $response['message'] ?? 'Could not fetch order status from provider.',
                ];
            }

            $providerStatus = (string) ($response['data']['order_status'] ?? 'processing');
            $normalizedStatus = $this->normalizeOrderStatus($providerStatus);
            $hasChanged = $order->provider_status !== $providerStatus || $order->status !== $normalizedStatus;

            if ($hasChanged) {
                $order->forceFill([
                    'provider_status' => $providerStatus,
                    'status' => $normalizedStatus,
                ])->save();

                Log::channel('orders')->info('Order status synced from provider', [
                    'order_code' => $order->code,
                    'provider_reference' => $order->provider_reference,
                    'provider_status' => $providerStatus,
                    'status' => $normalizedStatus,
                ]);
            } else {
                $order->touch();
            }

            return [
                'updated' => $hasChanged,
                'failed' => false,
                'status' => $normalizedStatus,
                'message' => 'Order status synced successfully.',
            ];
        } catch (\Throwable $exception) {
            Log::channel('orders')->warning('Order status sync failed', [
                'order_code' => $order->code,
                'provider_reference' => $order->provider_reference,
                'message' => $exception->getMessage(),
            ]);

            return [
                'updated' => false,
                'failed' => true,
                'status' => $order->status,
                'message' => 'An error occurred while checking order status.',
            ];
        }
    }

    private function normalizeOrderStatus(string $status): string
    {
        $normalized = strtolower(trim($status));

        return match ($normalized) {
            'success', 'completed' => 'completed',
            'pending', 'processing', 'queued', 'ongoing' => 'processing',
            'failed', 'error', 'cancelled', 'reversed' => 'failed',
            default => $normalized ?: 'processing',
        };
    }
}
