<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Order;
use App\Models\TopUp;
use Livewire\Component;
use Illuminate\Support\Str;
use App\Http\Customs\CustomHelper;
use Illuminate\Support\Facades\DB;

class AgentDetailComponent extends Component
{

    public $amount;

    public $agent;


    protected $rules = [
        "amount" => "required|numeric|gt:0",
    ];


    public function activateAcc()
    {
        $u = User::firstWhere("code", "=", session()->get("AG_CODE"));
        if (!$u) {
            CustomHelper::message("warning", "Agent not found");
            return;
        }
        $u->agent_status = "active";
        $u->save();
        $this->agent = $u;
        CustomHelper::message("info", "{$u->name} access granted");
    }


    public function topUp(){
        $this->validate();
        $agent = User::firstWhere("code", "=", session()->get("AG_CODE"));
        if (!$agent) {
            CustomHelper::message("warning", "Agent not found");
            return;
        }
        DB::transaction(function () use ($agent){
           $agent->balance += floatval($this->amount);
            $agent->save();

            TopUp::create([
                "code" => Str::uuid(),
                "payment_made" => true,
                "customer_id" => $agent->id,
                "amount" => floatval($this->amount),
                "status" => "completed"
            ]);
        });
        // $this->dispatch("close-topup-modal");
        $this->agent = $agent;
        $this->amount = "";
        CustomHelper::message("info", "Wallet Loaded");
    }

    public function updateAgentWallet(){
        $this->validate();
        $agent = User::firstWhere("code", "=", session()->get("AG_CODE"));
        if (!$agent) {
            CustomHelper::message("warning", "Agent not found");
            return;
        }
        DB::transaction(function () use ($agent){
           $agent->balance += floatval($this->amount);
            $agent->save();

            TopUp::create([
                "code" => Str::uuid(),
                "payment_made" => true,
                "customer_id" => $agent->id,
                "amount" => floatval($this->amount),
                "status" => "completed"
            ]);
        });
        $this->dispatch("close-topup-modal");
        $this->agent = $agent;
        $this->amount = "";
        CustomHelper::message("info", "Wallet Loaded");

    }

    public function setForTopUp(){
        $this->dispatch("show-topup-modal");
    }

    public function render()
    {
        $user = User::firstWhere("code", session()->get("AG_CODE"));

        if (!$user) {
            return view('livewire.admin.agent-detail-component', [
                "orders" => Order::query()->whereRaw("1 = 0")->paginate(10),
                "deposits" => collect(),
                "totalOrders" => 0,
                "completedOrders" => 0,
                "pendingOrders" => 0,
                "totalDepositsAmount" => 0,
            ]);
        }

        return view('livewire.admin.agent-detail-component', [
            "orders" => Order::query()
            ->with(["customer", "product", "product.category"])
                ->where("customer_id", $user->id)
                ->orderByDesc("created_at")
                ->paginate(10),
            "deposits" => TopUp::query()
                ->where("customer_id", "=", $user->id)
                ->orderByDesc("created_at")
                ->get(),
            "totalOrders" => Order::query()
                ->where("customer_id", $user->id)
                ->count(),
            "completedOrders" => Order::query()
                ->where("customer_id", $user->id)
                ->where("status", "completed")
                ->count(),
            "pendingOrders" => Order::query()
                ->where("customer_id", $user->id)
                ->whereIn("status", ["pending", "processing"])
                ->count(),
            "totalDepositsAmount" => (float) TopUp::query()
                ->where("customer_id", "=", $user->id)
                ->sum("amount")

        ]);
    }

    public function mount()
    {
        $this->agent =  User::firstWhere("code", "=", session()->get("AG_CODE"));
    }
}
