<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;

class AgentProductsComponent extends Component
{
    public $search = "";
    public $categoryId = "all";
    public $stockFilter = "all";
    public $allCategories = [];
    public $wizardStep = "browse";
    public $selectedProductCode = "";
    public $recipientPhone = "";
    public $lastOrderReference = "";
    public $isSubmitting = false;

    protected function rules(): array
    {
        return [
            "recipientPhone" => "required|digits:10",
        ];
    }

    public function render()
    {
        $productsQuery = Product::query()
            ->with("category")
            ->orderBy("category_id")
            ->orderBy("name");

        if (!blank($this->search)) {
            $productsQuery->where("name", "like", "%" . trim($this->search) . "%");
        }

        if ($this->categoryId !== "all") {
            $productsQuery->where("category_id", $this->categoryId);
        }

        if ($this->stockFilter === "in_stock") {
            $productsQuery->where("out_to_stock", false);
        } elseif ($this->stockFilter === "out_of_stock") {
            $productsQuery->where("out_to_stock", true);
        }

        return view("livewire.agent-products-component", [
            "allProducts" => $productsQuery->get(),
            "selectedProduct" => $this->getSelectedProduct(),
        ]);
    }

    public function startPurchase(string $productCode): void
    {
        $selectedProduct = Product::query()
            ->with("category")
            ->firstWhere("code", $productCode);

        if (!$selectedProduct) {
            $this->flashMessage("warning", "Selected package could not be found.");
            return;
        }

        if ((bool) $selectedProduct->out_to_stock) {
            $this->flashMessage("warning", "This package is currently out of stock.");
            return;
        }

        $this->resetErrorBag();
        $this->selectedProductCode = $selectedProduct->code;
        $this->recipientPhone = "";
        $this->wizardStep = "recipient";
        $this->lastOrderReference = "";
        $this->dispatch("openPurchaseWizard");
    }

    public function backToBrowse(): void
    {
        $this->wizardStep = "browse";
        $this->selectedProductCode = "";
        $this->recipientPhone = "";
        $this->isSubmitting = false;
        $this->dispatch("closePurchaseWizard");
    }

    public function proceedToConfirm(): void
    {
        $this->validate([
            "recipientPhone" => "required|digits:10",
        ]);

        $selectedProduct = $this->getSelectedProduct();
        if (!$selectedProduct) {
            $this->flashMessage("warning", "Please select a package again.");
            $this->backToBrowse();
            return;
        }

        if ((bool) $selectedProduct->out_to_stock) {
            $this->flashMessage("warning", "This package is currently out of stock.");
            $this->backToBrowse();
            return;
        }

        $currentUser = Auth::user()->fresh();
        if ((float) $currentUser->balance < (float) $selectedProduct->retail_price) {
            $this->flashMessage("warning", "Insufficient Balance to purchase bundle. Please top-up your wallet");
            return;
        }

        $this->wizardStep = "confirm";
    }

    public function submitPurchase(): void
    {
        if ($this->isSubmitting) {
            return;
        }

        $this->validate();

        $selectedProduct = Product::query()
            ->with("category")
            ->firstWhere("code", $this->selectedProductCode);

        if (!$selectedProduct) {
            $this->flashMessage("warning", "Selected package could not be found.");
            $this->backToBrowse();
            return;
        }

        if ((bool) $selectedProduct->out_to_stock) {
            $this->flashMessage("warning", "This package is currently out of stock.");
            $this->backToBrowse();
            return;
        }

        $currentUser = Auth::user()->fresh();
        if ((float) $currentUser->balance < (float) $selectedProduct->retail_price) {
            $this->flashMessage("warning", "Insufficient Balance to purchase bundle. Please top-up your wallet");
            return;
        }

        $this->isSubmitting = true;

        try {
            // $response = OtherAPI::purchaseData(
            //     $this->recipientPhone,
            //     $selectedProduct->name,
            //     strtoupper($selectedProduct->category->name)
            // );

            // if (!is_array($response) || !array_key_exists("success", $response) || !$response["success"]) {
            //     $errorMessage = $response["error"] ?? "Purchase failed. Please try again later.";
            //     $this->flashMessage("warning", $errorMessage);
            //     return;
            // }

            // $responseData = $response["data"] ?? [];
            $responseData = [
                "reference" => Str::uuid()
            ];

            DB::transaction(function () use ($currentUser, $selectedProduct, $responseData): void {
                $status = $this->normalizeOrderStatus((string) ($responseData["status"] ?? "processing"));
                $reference = (string) ($responseData["reference"] ?? Str::uuid());

                $newOrder = Order::query()->create([
                    "code" => $reference,
                    "payment_made" => true,
                    "status" => $status,
                    "customer_id" => $currentUser->id,
                    "product_id" => $selectedProduct->id,
                    "phone_number" => $this->recipientPhone,
                    "total_amount" => $selectedProduct->retail_price,
                ]);

                Transaction::query()->create([
                    "customer_id" => $currentUser->id,
                    "order_id" => $newOrder->id,
                    "amount" => $selectedProduct->retail_price,
                    "code" => (string) Str::uuid(),
                    "description" => "Purchase - Ref #" . $reference,
                    "type" => "debit",
                    "status" => "completed",
                ]);

                $currentUser->balance -= (float) $selectedProduct->retail_price;
                $currentUser->save();
            });

            session()->forget(["ORDER_PAYMENT", "TOPUP_ID", "TOPUP_AMOUNT"]);
            $this->lastOrderReference = (string) ($responseData["reference"] ?? "");
            $this->flashMessage("success", "Purchase Successful");
            $this->wizardStep = "success";
        } catch (\Throwable $e) {
            Log::error("Livewire purchase failed for user " . Auth::id() . ": " . $e->getMessage());
            $this->flashMessage("danger", "An unexpected error occurred. Please try again.");
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function mount()
    {
        $this->allCategories = Category::query()
            ->orderBy("name")
            ->get(["id", "name"]);
    }

    private function getSelectedProduct(): ?Product
    {
        if (blank($this->selectedProductCode)) {
            return null;
        }

        return Product::query()
            ->with("category")
            ->firstWhere("code", $this->selectedProductCode);
    }

    private function flashMessage(string $type, string $message): void
    {
        session()->flash("at", $type);
        session()->flash("am", $message);
    }

    private function normalizeOrderStatus(string $status): string
    {
        $normalized = strtolower(trim($status));

        return match ($normalized) {
            "success", "completed" => "completed",
            "pending", "processing", "queued", "ongoing" => "processing",
            "failed", "error", "cancelled", "reversed" => "failed",
            default => $normalized ?: "processing",
        };
    }
}
