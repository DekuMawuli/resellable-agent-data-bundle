<?php

namespace App\Livewire\Admin;

use App\Models\Order;
use App\Models\Setting;
use Livewire\Component;
use App\Helper\UnimarketAPI;
use App\Exports\OrdersExport;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use GuzzleHttp\Exception\RequestException;

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
        // Use firstOrFail for a cleaner query. It will 404 if the order is not found.
        $order = Order::query()
            ->with(["product", "product.category"])
            ->where("code", "=", $id)
            ->where("payment_made", "=", true)
            ->where("status", "=", "processing")
            ->firstOrFail(); // If not found, it stops here with a 404 page.

        try {
            // Call the external API
            $response = UnimarketAPI::purchaseBundle(
                strtoupper($order->product->category->name),
                $order->phone_number,
                $order->product->name
            );

            // Check for a business logic failure from the API
            if ($response['status'] != 'success') {
                $errorMessage = $response['message'] ?? 'Purchase failed at the provider.';
                CustomHelper::message("warning", $errorMessage);
                return redirect()->back();
            }

            // Use a transaction to safely update the order details
            DB::transaction(function () use ($order, $response) {
                $responseData = $response['data'];

                // ** CRITICAL FIX: Store reference in the NEW field, DO NOT overwrite the order code **
                $order->code = $responseData['reference'];

                // NOTE: This assumes instant completion. The correct way is to create a separate
                // process to check the status using the reference code, but for simplicity,
                // we will stick to your original logic of marking it as completed.
                $order->status = "completed";
                $order->save();
            });

            // If the transaction succeeds, redirect with a success message
            CustomHelper::message("success", "Order #" . $order->code . " was approved and processed successfully.");

        } catch (RequestException $e) {
            // This runs if the Unimarket API is down or returns an error
            Log::error("Unimarket API Exception during manual approval for Order #{$order->code}: " . $e->getMessage());
            CustomHelper::message("danger", "The provider's service is temporarily unavailable. The order was not processed. Please try again later.");
            return redirect()->back();
        } catch (\Throwable $e) {
            // This catches any other unexpected errors (e.g., database issues)
            Log::error("General Exception during manual approval for Order #{$order->code}: " . $e->getMessage());
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
}
