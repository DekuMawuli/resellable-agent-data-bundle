<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\TopUp;
use App\Models\Setting;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentController extends Controller
{
    public function dashboard(){

        // auth()->user()->balance = 0;
        // auth()->user()->save();

        if (!is_null(session()->get("ORDER_PAYMENT"))){
            return view("agent.confirm_deposit");
        }else{
            $deposits = TopUp::query()
                ->where("customer_id", "=", Auth::user()->id)
                ->orderByDesc("created_at")
                ->limit(8)
                ->get();
            $orders = Order::query()
                ->with(["product", "product.category"])
                ->where("customer_id", "=", Auth::user()->id)
                ->orderByDesc("created_at")
                ->count();
            $closedOrders = Order::query()
                ->where("customer_id", "=", Auth::user()->id)
                ->whereIn("status", ["completed", "success"])
                ->count();
            $openOrders = Order::query()
                ->where("customer_id", "=", Auth::user()->id)
                ->whereIn("status", ["pending", "processing", "queued", "ongoing"])
                ->get();
            $setting = Setting::firstOrCreate(
                ["id" => 1],
                [
                    "contact_number" => null,
                    "whatsapp_number" => null,
                    "whatsapp_link" => null,
                ]
            );

            $ctx = [
                "deposits" => $deposits,
                "ordersCount" => $orders,
                "closedOrders" => $closedOrders,
                "openOrders" => $openOrders->count(),
                "setting" => $setting,
                "totalDepositsAmount" => TopUp::query()
                    ->where("customer_id", Auth::user()->id)
                    ->where("status", "completed")
                    ->sum("amount"),
            ];
            return view("agent.dashboard", $ctx);
        }

    }

    public function orders(){
        if (!is_null(session()->get("ORDER_PAYMENT"))){
            return redirect(route("agent.dashboard"));
        }
        return view("agent.orders");
    }

    public function profile(){
        if (!is_null(session()->get("ORDER_PAYMENT"))){
            return redirect(route("agent.dashboard"));
        }
        return view("agent.profile");
    }

    public function products(){
        if (!is_null(session()->get("ORDER_PAYMENT"))){
            return redirect(route("agent.dashboard"));
        }
        return view("agent.products");
    }

    public function deposit(Request $request){
        $validatedData = $request->validate([
            "amount" => "required|gt:1"
        ]);


        $topUp = TopUp::create([
            "code" => (string) Str::uuid(),
            "customer_id" => Auth::user()->id,
            "amount" => $validatedData['amount'],
            "status" => "pending",
        ]);

        session()->put("ORDER_PAYMENT", false);
        session()->put("TOPUP_ID", $topUp->id);
        session()->put("TOPUP_AMOUNT", $topUp->amount);

        return redirect(route("agent.dashboard"));
    }

    public function confirmPayment(){
        if (!is_null(session()->get("ORDER_PAYMENT"))){
            $code = session()->get("TOPUP_ID");
            $item = TopUp::query()
                ->where("id", $code)
                ->where("customer_id", Auth::id())
                ->where("status", "pending")
                ->first();

            if (!$item) {
                session()->remove("ORDER_PAYMENT");
                session()->remove("TOPUP_ID");
                session()->remove("TOPUP_AMOUNT");
                return redirect(route("agent.dashboard"));
            }

            $item->payment_made = true;
            $item->save();
             session()->remove("ORDER_PAYMENT");
             session()->remove("TOPUP_ID");
             session()->remove("TOPUP_AMOUNT");
             session()->flash("at", "info");
             session()->flash("am", "Payment notice submitted. Your wallet will be credited after admin review.");
             return redirect(route("agent.dashboard"));
        }else{
            return redirect(route("agent.dashboard"));
        }
    }
}
