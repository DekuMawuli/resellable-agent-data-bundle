<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class AgentOrdersComponent extends Component
{
    use WithPagination;

    public function render()
    {
        return view("livewire.agent-orders-component", [
            "orders" => Order::query()
                ->with(["product", "product.category"])
                ->where("customer_id", auth()->id())
                ->orderByDesc("created_at")
                ->paginate(12),
        ]);
    }
}
