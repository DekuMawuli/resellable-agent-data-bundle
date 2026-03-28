<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use Livewire\Component;
use App\Exports\OrdersExport;
use App\Services\RealestApiService;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardOrdersComponent extends Component
{

    public function confirmPurchase($code){
        $order = Order::query()
            ->where("code", "=", $code)
            ->where("status", "=", "pending")
            ->where("payment_made", "=", true)
            ->first();
        if (is_null($order)){
            CustomHelper::message("warning", "Order is not completed by Agent");
            return;
        }
        $order->status = "processing";
        $order->save();
        CustomHelper::message("success", "Order in process");
    }

    public function exportOrders(){
        return Excel::download(new OrdersExport, "orders.xlsx");
    }

    public function approvePurchase($id)
    {
        $order = Order::query()
            ->with(["product", "product.category"])
            ->where("code", "=", $id)
            ->where("payment_made", "=", true)
            ->where("status", "=", "processing")
            ->firstOrFail();

        try {
            if (filled($order->provider_reference)) {
                CustomHelper::message("info", "Order has already been forwarded to the provider.");
                return redirect()->back();
            }

            $response = app(RealestApiService::class)->purchaseBundle(
                strtoupper($order->product->category->name),
                $order->phone_number,
                $order->product->name
            );

            if (($response['status'] ?? 'error') !== 'success') {
                $errorMessage = $response['message'] ?? 'Purchase failed at the provider.';
                CustomHelper::message("warning", $errorMessage);
                return redirect()->back();
            }

            DB::transaction(function () use ($order, $response) {
                $responseData = $response['data'] ?? [];
                $providerStatus = (string) ($responseData['order_status'] ?? 'processing');

                $order->provider_reference = (string) ($responseData['reference_code'] ?? $order->provider_reference);
                $order->provider_status = $providerStatus;
                $order->status = $this->normalizeOrderStatus($providerStatus);
                $order->save();
            });

            CustomHelper::message("success", "Order #" . $order->code . " was forwarded successfully.");
        } catch (\Throwable $e) {
            Log::error("Realest API Exception during manual approval for Order #{$order->code}: " . $e->getMessage());
            CustomHelper::message("danger", "An unexpected error occurred. Please contact support.");
            return redirect()->back();
        }
    }
    public function render()
    {
        return view('livewire.admin.admin-dashboard-orders-component', [
            "orders" => Order::query()
            ->with(["customer", "product", "product.category"])
            ->where("payment_made", "=", true)
            ->latest()
            ->paginate(15)
        ]);
    }

    private function normalizeOrderStatus(string $status): string
    {
        $normalized = strtolower(trim($status));

        return match ($normalized) {
            "success", "completed" => "completed",
            "pending", "processing", "queued", "ongoing" => "processing",
            "failed", "error", "cancelled", "reversed" => "failed",
            default => $normalized ?: "processing",
        };
    }
}
