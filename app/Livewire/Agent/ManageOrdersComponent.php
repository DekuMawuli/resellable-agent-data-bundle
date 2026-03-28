<?php

namespace App\Livewire\Agent;

use App\Models\Order;
use Livewire\Component;
use App\Services\RealestApiService;
use App\Http\Customs\CustomHelper;

class ManageOrdersComponent extends Component
{

    public function checkStatus($code){

        $order = Order::query()
            ->where("code", "=", $code)
            ->where("customer_id", "=", auth()->user()->id)
            ->first();

        if (!$order) {
            CustomHelper::message("warning", "Order not found.");
            return;
        }

        if (blank($order->provider_reference)) {
            CustomHelper::message("warning", "Order has not been forwarded to the provider yet.");
            return;
        }

        try{
            $response = app(RealestApiService::class)->getOrderStatus($order->provider_reference);

            if (($response["status"] ?? "error") !== "success"){
                CustomHelper::message("danger", $response["message"] ?? "Could not fetch order status from provider.");
                return;
            }

            $status = $this->normalizeOrderStatus((string) ($response['data']['order_status'] ?? "processing"));
            $order->provider_status = (string) ($response['data']['order_status'] ?? "processing");
            $order->status = $status;
            $order->save();

            CustomHelper::message("success", "Order Status: " . ucfirst($status));
        } catch (\Exception $e) {

            CustomHelper::message("danger", "An error occurred while checking order status.");
        }


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

    public function render()
    {
        return view('livewire.agent.manage-orders-component', [
            "orders" => Order::query()
                ->with(["product", "product.category"])
                ->where("customer_id", "=", auth()->user()->id)
                ->orderByDesc("created_at")
                ->get(),
        ]);
    }
}
