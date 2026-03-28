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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

        // Fetch live balance from Realest API — cached for 5 min to avoid
        // a real HTTP call on every page load / post-approval redirect.
        $realestBalance      = null;
        $realestBalanceError = null;

        try {
            $cached = Cache::remember('realest_account_balance', 300, function () {
                return app(RealestApiService::class)->checkBalance();
            });

            if (($cached['status'] ?? '') === 'success') {
                $realestBalance = $cached['data']['balance'] ?? null;
            } else {
                // Don't cache error responses — bust so the next load retries
                Cache::forget('realest_account_balance');
                $realestBalanceError = $cached['message'] ?? 'Could not fetch balance.';
            }
        } catch (\Throwable $e) {
            Cache::forget('realest_account_balance');
            Log::channel('realest')->error('Failed to fetch Realest balance on admin dashboard', [
                'message' => $e->getMessage(),
            ]);
            $realestBalanceError = 'API error — check credentials.';
        }

        $ctx = [
            "activeAgents"        => $agents,
            "todaySales"          => $todaySales,
            "balance"             => $setting->account_balance,
            "realestBalance"      => $realestBalance,
            "realestBalanceError" => $realestBalanceError,
            "deposits"            => $deposits,
            "totalDeposits"       => $totalDeposit,
            "setting"             => $setting,
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
        $order = Order::firstWhere("code", "=", $code);
        if (!$order) {
            CustomHelper::message("warning", "Order not found.");
            return redirect()->back();
        }
        $order->delete();
        CustomHelper::message("warning",
        "Order Removed Successfully");
        return redirect()->back();
    }


    public function approveDeposit($id){
        $deposit = TopUp::query()
            ->where("id", "=", $id)
            ->where("status", "=", "pending")
            ->where("payment_made", "=", true)
            ->first();
        if (is_null($deposit)){
            CustomHelper::message("warning", "Deposit cannot be approved. Confirm that the agent has submitted payment.");
            return redirect(route("root.dashboard"));
        }

        DB::transaction(function () use ($deposit) {
            $lockedDeposit = TopUp::query()
                ->whereKey($deposit->id)
                ->lockForUpdate()
                ->first();

            if (!$lockedDeposit || $lockedDeposit->status === "completed") {
                return;
            }

            $agent = User::query()
                ->whereKey($lockedDeposit->customer_id)
                ->lockForUpdate()
                ->first();

            if (!$agent) {
                return;
            }

            $lockedDeposit->status = "completed";
            $lockedDeposit->save();

            $agent->balance += $lockedDeposit->amount;
            $agent->save();

            \App\Models\Transaction::query()->firstOrCreate(
                ["code" => $lockedDeposit->code],
                [
                    "customer_id" => $agent->id,
                    "amount" => $lockedDeposit->amount,
                    "type" => "credit",
                    "status" => "completed",
                    "description" => "Admin-approved deposit",
                ]
            );
        });

        CustomHelper::message("success", "Deposit Approved");
        return redirect(route("root.dashboard"));
    }
    public function confirmPayment($id){
        $deposit = TopUp::query()
            ->where("id", "=", $id)
            ->where("status", "=", "pending")
            ->where("payment_made", "=", true)
            ->first();
        if (is_null($deposit)){
            CustomHelper::message("warning", "Payment has not been submitted by the agent.");
            return redirect(route("root.dashboard"));
        }
        CustomHelper::message("info", "Payment submission confirmed. You can now approve the deposit.");
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

        $useLive = (bool) Setting::query()->value("use_live_payment");

        // ── TEST MODE: approve locally, never touch the external API ──────────
        if (!$useLive) {
            $order->provider_reference = "TEST-" . strtoupper(substr(uniqid(), -8));
            $order->provider_status    = "completed";
            $order->status             = "completed";
            $order->save();

            Log::channel("realest")->info("Order approved locally in test mode (no API call made)", [
                "order_code"        => $order->code,
                "provider_reference" => $order->provider_reference,
            ]);

            CustomHelper::message("info", "Test mode — order marked as completed locally. No request was sent to the provider.");
            return redirect(route("root.dashboard"));
        }

        // ── LIVE MODE: forward to Realest API ─────────────────────────────────
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
            $order->provider_status    = (string) ($responseData["order_status"] ?? "processing");
            $order->status             = match (strtolower(trim($order->provider_status))) {
                "success", "completed"                        => "completed",
                "pending", "processing", "queued", "ongoing" => "processing",
                "failed", "error", "cancelled", "reversed"   => "failed",
                default                                       => "processing",
            };
            $order->save();

            CustomHelper::message("success", "Order forwarded to provider successfully.");
        } catch (\Throwable $e) {
            Log::channel("realest")->error("Realest API exception during admin approvePurchase", [
                "order_code" => $order->code,
                "message"    => $e->getMessage(),
            ]);
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

    public function credentials()
    {
        return view("admin.credentials");
    }

}
