<?php

namespace App\Livewire\Agent;

use App\Http\Customs\CustomHelper;
use App\Models\Order;
use App\Services\OrderStatusSyncService;
use Livewire\Component;

class ManageOrdersComponent extends Component
{
    public function checkStatus($code)
    {

        $order = Order::query()
            ->where('code', '=', $code)
            ->where('customer_id', '=', auth()->user()->id)
            ->first();

        if (! $order) {
            CustomHelper::message('warning', 'Order not found.');

            return;
        }

        if (blank($order->provider_reference)) {
            CustomHelper::message('warning', 'Order has not been forwarded to the provider yet.');

            return;
        }

        $result = app(OrderStatusSyncService::class)->syncOrder($order, true);

        if (($result['failed'] ?? false) === true) {
            CustomHelper::message('danger', $result['message'] ?? 'An error occurred while checking order status.');

            return;
        }

        CustomHelper::message('success', 'Order Status: '.ucfirst((string) ($result['status'] ?? $order->status)));
    }

    public function render()
    {
        return view('livewire.agent.manage-orders-component', [
            'orders' => Order::query()
                ->with(['product', 'product.category'])
                ->where('customer_id', '=', auth()->user()->id)
                ->orderByDesc('created_at')
                ->get(),
        ]);
    }
}
