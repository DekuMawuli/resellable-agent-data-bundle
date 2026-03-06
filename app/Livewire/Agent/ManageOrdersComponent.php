<?php

namespace App\Livewire\Agent;

use App\Models\Order;
use Livewire\Component;
use App\Helper\OtherAPI;
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

        try{
            $response = OtherAPI::checkOrderStatus($code);

            if (!is_array($response) || !($response["success"] ?? false)){
                CustomHelper::message("danger", "Could not fetch order status from provider.");
                return;
            }

            $status = $this->normalizeOrderStatus((string) ($response['data']['status'] ?? "processing"));
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
