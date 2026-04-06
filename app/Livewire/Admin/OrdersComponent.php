<?php

namespace App\Livewire\Admin;

use App\Http\Customs\CustomHelper;
use App\Models\Category;
use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use App\Services\OrderStatusSyncService;
use App\Services\RealestApiService;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class OrdersComponent extends Component
{
    use WithPagination;

    public $search = '';

    public $status = 'all';

    public $payment = 'all';

    public $agentId = 'all';

    public $categoryId = 'all';

    public $dateFrom = '';

    public $dateTo = '';

    public function mount(): void
    {
        app(OrderStatusSyncService::class)->syncPendingProviderOrders();
    }

    public function updated($property): void
    {
        if (in_array($property, [
            'search',
            'status',
            'payment',
            'agentId',
            'categoryId',
            'dateFrom',
            'dateTo',
        ])) {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->search = '';
        $this->status = 'all';
        $this->payment = 'all';
        $this->agentId = 'all';
        $this->categoryId = 'all';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function confirmPurchase(int $id): void
    {
        $order = Order::query()
            ->where('id', $id)
            ->where('payment_made', true)
            ->where('status', 'pending')
            ->first();

        if (! $order) {
            CustomHelper::message('warning', 'Order cannot be confirmed. Ensure payment is made and status is pending.');

            return;
        }

        $order->status = 'processing';
        $order->save();

        CustomHelper::message('success', 'Order moved to processing.');
    }

    public function approvePurchase(int $id): void
    {
        $order = Order::query()
            ->with(['product', 'product.category'])
            ->where('id', $id)
            ->where('payment_made', true)
            ->where('status', 'processing')
            ->first();

        if (! $order) {
            CustomHelper::message('warning', 'Order cannot be approved. Ensure payment is made and status is processing.');

            return;
        }

        if (filled($order->provider_reference)) {
            CustomHelper::message('info', 'Order has already been forwarded to the provider.');

            return;
        }

        $useLive = (bool) Setting::query()->value('use_live_payment');

        // ── TEST MODE: approve locally, no external API call ──────────────────
        if (! $useLive) {
            $order->provider_reference = 'TEST-'.strtoupper(substr(uniqid(), -8));
            $order->provider_status = 'completed';
            $order->status = 'completed';
            $order->save();

            Log::channel('realest')->info('Order approved locally in test mode (no API call made)', [
                'order_code' => $order->code,
                'provider_reference' => $order->provider_reference,
            ]);

            CustomHelper::message('info', "Test mode — order #{$order->code} marked as completed locally. No request was sent to the provider.");

            return;
        }

        // ── LIVE MODE: forward to Realest API ─────────────────────────────────
        try {
            $response = app(RealestApiService::class)->purchaseBundle(
                strtoupper($order->product->category->name),
                $order->phone_number,
                $order->product->name
            );

            if (($response['status'] ?? 'error') !== 'success') {
                CustomHelper::message('warning', $response['message'] ?? 'Purchase failed at the provider.');

                return;
            }

            $responseData = $response['data'] ?? [];
            $providerStatus = (string) ($responseData['order_status'] ?? 'processing');

            $order->provider_reference = (string) ($responseData['reference_code'] ?? $order->provider_reference);
            $order->provider_status = $providerStatus;
            $order->status = $this->normalizeOrderStatus($providerStatus);
            $order->save();

            CustomHelper::message('success', "Order #{$order->code} forwarded to provider successfully.");
        } catch (\Throwable $e) {
            Log::channel('realest')->error('Realest API exception during order approval', [
                'order_code' => $order->code,
                'message' => $e->getMessage(),
            ]);
            CustomHelper::message('danger', 'An unexpected error occurred while forwarding the order.');
        }
    }

    public function render()
    {
        $query = Order::query()
            ->with(['customer', 'product', 'product.category'])
            ->orderByDesc('created_at');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        } else {
            $query->whereIn('status', ['pending', 'processing', 'completed', 'success', 'failed', 'cancelled']);
        }

        if ($this->payment === 'paid') {
            $query->where('payment_made', true);
        } elseif ($this->payment === 'unpaid') {
            $query->where('payment_made', false);
        }

        if ($this->agentId !== 'all') {
            $query->where('customer_id', $this->agentId);
        }

        if ($this->categoryId !== 'all') {
            $query->whereHas('product', function ($q) {
                $q->where('category_id', $this->categoryId);
            });
        }

        if (! blank($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (! blank($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        if (! blank($this->search)) {
            $search = trim($this->search);

            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%'.$search.'%')
                    ->orWhere('phone_number', 'like', '%'.$search.'%')
                    ->orWhereHas('customer', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('phone', 'like', '%'.$search.'%');
                    })
                    ->orWhereHas('product', function ($sq) use ($search) {
                        $sq->where('name', 'like', '%'.$search.'%')
                            ->orWhereHas('category', function ($cq) use ($search) {
                                $cq->where('name', 'like', '%'.$search.'%');
                            });
                    });
            });
        }

        return view('livewire.admin.orders-component', [
            'orders' => $query->paginate(20),
            'agents' => User::query()
                ->where('role', 'agent')
                ->orderBy('name')
                ->get(['id', 'name']),
            'categories' => Category::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
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
