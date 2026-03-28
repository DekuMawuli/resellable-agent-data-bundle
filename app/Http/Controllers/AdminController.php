<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\TopUp;
use App\Models\Setting;
use App\Services\RealestApiService;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{


    public function dashboard()
    {
        $agents = User::query()
            ->where("role", "agent")
            ->where("agent_status", "active")
            ->count();
        $totalDeposit = TopUp::query()
            ->sum("amount");
        $todaySales = Order::query()
            ->where("payment_made", true)
            ->whereIn("status", ["completed", "success"])
            ->whereDate("created_at", now()->toDateString())
            ->sum("total_amount");
         $setting = Setting::firstOrCreate(["id" => 1],
            [
                "code" => Str::uuid(),
                "whatsapp_link" => null,
                "whatsapp_number" => null,
                "contact_number" => null,
                "account_balance" => 0.00
            ]
        );



        $deposits = TopUp::query()
            ->with("customer")
            ->orderByDesc("created_at")
            ->limit(10)
            ->get();

        $ctx = [
            "activeAgents" => $agents,
            "todaySales" => $todaySales,
            "balance" => $setting->account_balance,
            "deposits" => $deposits,
            "totalDeposits" => $totalDeposit,
            "setting" => $setting,
        ];
        return view("admin.dashboard", $ctx);
    }



    public function agents(){
        return view("admin.agents");
    }


    public function toggleBalanceView(Request $request){
        if (session()->get("view-balance") == "Y"){
            session()->put("view-balance", "N");
            return redirect()->back();
        }
        $request->validate([
            "passkey" => "required"
        ]);
        if ($request->passkey != config("services.secret.balance-key")){
            CustomHelper::message("danger", "Invalid Passkey");
            return redirect()->back();
        }
        session()->put("view-balance", session()->get("view-balance") == "Y" ? "N" : "Y");
        return redirect()->back();
    }

    public function categories(){
        return view("admin.categories");
    }
    public function products(){
        return view("admin.products");
    }

    public function orders(){
        return view("admin.orders");
    }


    public function transactions(){
        return view("admin.dashboard");
    }

    public function deleteOrder($code){
        $order = Order::firstWhere("orderCode", "=", $code);
        $order->delete();
        CustomHelper::message("warning",
        "Order Removed Successfully");
        return redirect()->back();
    }


    public function approveDeposit($id){
        $deposit = TopUp::query()
            ->where("id", "=", $id)
            ->where("status", "=", "processing")
            ->where("paymentMade", "=", "Y")
            ->first();
        if (is_null($deposit)){
            CustomHelper::message("warning", "Deposit cannot be done. First Approve Payment and Check whether agent has made payment");
            return redirect(route("root.dashboard"));
        }
        $deposit->status = "completed";
        $deposit->save();
        $agent = User::where("id", "=", $deposit->user_id)->first();
        $agent->balance += $deposit->amount;
        $agent->save();
        CustomHelper::message("success", "Deposit Approved");
        return redirect(route("root.dashboard"));
    }
    public function confirmPayment($id){
        $deposit = TopUp::query()
            ->where("id", "=", $id)
            ->where("status", "=", "pending")
            ->where("paymentMade", "=", "Y")
            ->first();
        if (is_null($deposit)){
            CustomHelper::message("warning", "Payment is not completed by Agent");
            return redirect(route("root.dashboard"));
        }
        $deposit->status = "processing";
        $deposit->save();
        CustomHelper::message("success", "Deposit in process");
        return redirect(route("root.dashboard"));
    }


    public function approvePurchase($id){
        $order = Order::query()
            ->with(["product", "product.category"])
            ->where("id", "=", $id)
            ->where("payment_made", "=", true)
            ->where("status", "=", "processing")
            ->first();
        if (is_null($order)){
            CustomHelper::message("warning", "Order cannot be done. First Approve Payment and Check whether agent has made payment");
            return redirect(route("root.dashboard"));
        }

        if (filled($order->provider_reference)) {
            CustomHelper::message("info", "Order has already been forwarded to the provider.");
            return redirect(route("root.dashboard"));
        }

        try {
            $response = app(RealestApiService::class)->purchaseBundle(
                strtoupper($order->product->category->name),
                $order->phone_number,
                $order->product->name
            );

            if (($response["status"] ?? "error") !== "success") {
                CustomHelper::message("warning", $response["message"] ?? "Purchase failed at the provider.");
                return redirect(route("root.dashboard"));
            }

            $responseData = $response["data"] ?? [];

            $order->provider_reference = (string) ($responseData["reference_code"] ?? $order->provider_reference);
            $order->provider_status = (string) ($responseData["order_status"] ?? "processing");
            $order->status = match (strtolower(trim($order->provider_status))) {
                "success", "completed" => "completed",
                "pending", "processing", "queued", "ongoing" => "processing",
                "failed", "error", "cancelled", "reversed" => "failed",
                default => "processing",
            };
            $order->save();

            CustomHelper::message("success", "Order forwarded successfully.");
        } catch (\Throwable $e) {
            Log::error("Realest API Exception during admin controller approval for Order #{$order->code}: " . $e->getMessage());
            CustomHelper::message("danger", "An unexpected error occurred while forwarding the order.");
        }

        return redirect(route("root.dashboard"));
    }
    public function confirmPurchase($id){
        $order = Order::query()
            ->where("id", "=", $id)
            ->where("status", "=", "pending")
            ->where("payment_made", "=", true)
            ->first();
        if (is_null($order)){
            CustomHelper::message("warning", "Order is not completed by Agent");
            return redirect(route("root.dashboard"));
        }
        $order->status = "processing";
        $order->save();
        CustomHelper::message("success", "Order in process");
        return redirect(route("root.dashboard"));
    }

    public function agentDetail($code)
    {
        $agent = User::firstWhere("code", $code);



        if (!$agent){
            CustomHelper::message("warning", "Agent does not exist");
            return redirect()->back();
        }

        session()->put("AG_CODE", $agent->code);


        return view("admin.agent_detail");
    }

    public function settings()
    {
        return view("admin.settings");

    }

}
