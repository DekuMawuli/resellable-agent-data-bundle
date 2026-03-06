<?php

namespace App\Http\Controllers;

use App\Helper\UnimarketAPI;
use App\Models\User;
use App\Models\Order;
use App\Models\TopUp;
use App\Models\Product;
use App\Models\Setting;
use App\Models\SmsTracker;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{


    public function dashboard(){

        // $response = $this->checkBalance();


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

        $response = UnimarketAPI::getWalletBalance();

        if ($response['status'] != 'success'){
            CustomHelper::message("danger", "Could not fetch balance from Unimarket");
            $balance = "N/A";
        }else{
            $responseData = $response['data'];
            $balance = $responseData['balance'];
        }

        $deposits = TopUp::query()
            ->with("customer")
            ->orderByDesc("created_at")
            ->limit(10)
            ->get();

        $ctx = [
            "activeAgents" => $agents,
            "todaySales" => $todaySales,
            "balance" => $balance,
            "deposits" => $deposits,
            "totalDeposits" => $totalDeposit,
            "setting" => $setting
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
            ->where("id", "=", $id)
            ->where("paymentMade", "=", "Y")
            ->where("status", "=", "processing")
            ->first();
        if (is_null($order)){
            CustomHelper::message("warning", "Order cannot be done. First Approve Payment and Check whether agent has made payment");
            return redirect(route("root.dashboard"));
        }
        $order->status = "completed";
        $order->save();
        CustomHelper::message("success", "Order Approved");
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


    private function checkBalance()
    {
        // Base URL
        $baseUrl = 'https://datummarket.com/ishare_api/balance';

        // API and secret keys
        $tigoKey = config('services.tigo.key');
        $tigoSecret = config('services.tigo.secret');

        // Data to be sent in the body
        $data = [
            'name' => 'Azumah Nbalino',
        ];

        // Make the POST request using Laravel Http client
        $response = Http::withHeaders([
            'X-Api-Key' => $tigoKey,
            'X-Secret-Key' => $tigoSecret,
            'Content-Type' => 'application/json',
        ])
            ->withoutVerifying() // Equivalent to CURLOPT_SSL_VERIFYPEER = false
            ->post($baseUrl, $data);

        // Check if the request was successful
        if ($response->failed()) {
            throw new \Exception('API request failed: ' . $response->body());
        }

        // Return the decoded JSON response
        return $response->json();
    }



    public function settings()
    {
        return view("admin.settings");

    }

}
