<?php

namespace App\Livewire\Admin;

use App\Http\Customs\CustomHelper;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class AdminDashboardRecentOrdersComponent extends Component
{

    use WithPagination;


    public function confirmPurchase($id){
        $order = Order::query()
            ->where("id", "=", $id)
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

    public function render()
    {
        return view('livewire.admin.admin-dashboard-recent-orders-component', [
            "orders" => Order::query()
                ->with(["customer", "product", "product.category"])
                ->where("payment_made", true)
                ->whereIn("status", ["pending", "processing", "completed", "success"])
                ->orderByDesc("created_at")
                ->paginate(10)
        ]);
    }
}
