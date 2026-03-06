<?php

namespace App\Livewire\Admin;

use App\Http\Customs\CustomHelper;
use App\Models\Order;
use App\Models\TopUp;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AgentsComponent extends Component
{

    public $allAgents;

    public $selectedAgent;

    public $amount;

    public $query;

    protected $rules = [
        "amount" => "required|int|gt:0",
        "query" => "sometimes|string"
    ];

    public function updatedQuery($val)
    {
        if (empty($val)){
            $this->fetchAgents();
        }

        $this->allAgents = User::query()
            ->where("role", "=", "agent")
            ->where("name", "LIKE", "%".$val."%")
             ->orderByDesc("created_at")
             ->get();

    }

    public function activateAcc($code)
    {
        $u = $this->findAgent($code);
        if (!$u) {
            CustomHelper::message("warning", "Agent not found");
            return;
        }
        $u->agent_status = "active";
        $u->save();
        CustomHelper::message("info", "{$u->name} access granted");
        $this->fetchAgents();
    }

    public function updateAgentWallet(){

        $this->validate([
            "amount" => "required|int|gt:0",
        ]);
        DB::transaction(function (){
           $this->selectedAgent->balance += floatval($this->amount);
            $this->selectedAgent->save();

            TopUp::create([
                "payment_made" => true,
                "customer_id" => $this->selectedAgent->id,
                "amount" => floatval($this->amount),
                "status" => "completed"
            ]);
        });
        $this->dispatch("close-topup-modal");
        CustomHelper::message("info", "Wallet Loaded");
        $this->fetchAgents();

    }

    public function setForTopUp($code){
        $this->selectedAgent = $this->findAgent($code);
        if (!$this->selectedAgent) {
            CustomHelper::message("warning", "Agent not found");
            return;
        }
        $this->dispatch("show-topup-modal");
    }

    public function render()
    {
        return view('livewire.admin.agents-component');
    }

    private function fetchAgents(): void
    {
         $this->allAgents = User::query()
            ->where("role", "=", "agent")
             ->orderBy("role",)
             ->orderByDesc("created_at")
             ->get();
    }

    public function deleteAcc($code)
    {
        DB::transaction(function () use ($code){
            $u = $this->findAgent($code);
            if (!$u) {
                return;
            }
            Order::where("customer_id", $u->id)->delete();
            TopUp::where("customer_id", $u->id)->delete();
            Transaction::where("customer_id", $u->id)->delete();
            $u->delete();
        });
        CustomHelper::message("info", "Agent Removed");
        $this->fetchAgents();
    }

    public function mount(): void
    {
       $this->fetchAgents();
    }

    private function findAgent($identifier): ?User
    {
        if (blank($identifier)) {
            return null;
        }

        return User::query()
            ->where("code", "=", $identifier)
            ->first();
    }
}
