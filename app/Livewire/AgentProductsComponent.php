<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Transaction;
use App\Services\RealestApiService;
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
    public $lastPurchaseHeading = "";
    public $lastPurchaseMessage = "";
    public $lastPurchaseTone = "success";
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
        $this->lastPurchaseHeading = "";
        $this->lastPurchaseMessage = "";
        $this->lastPurchaseTone = "success";
        $this->dispatch("openPurchaseWizard");
    }

    public function backToBrowse(): void
    {
        $this->wizardStep = "browse";
        $this->selectedProductCode = "";
        $this->recipientPhone = "";
        $this->lastOrderReference = "";
        $this->lastPurchaseHeading = "";
        $this->lastPurchaseMessage = "";
        $this->lastPurchaseTone = "success";
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
            $useLive = (bool) Setting::query()->value("use_live_payment");
            $realestService = app(RealestApiService::class);

            if ($useLive) {
                if (!$realestService->isReady()) {
                    $this->flashMessage("warning", "Live fulfilment is enabled, but Realest API credentials are missing. No order was created.");
                    return;
                }

                $response = $realestService->purchaseBundle(
                    strtoupper((string) $selectedProduct->category->name),
                    $this->recipientPhone,
                    $selectedProduct->name
                );

                if (($response["status"] ?? "error") === "success") {
                    $providerData = $response["data"] ?? [];
                    $providerReference = (string) ($providerData["reference_code"] ?? Str::uuid());
                    $providerStatus = (string) ($providerData["order_status"] ?? "processing");

                    $responseData = [
                        "reference" => $providerReference,
                        "status" => $providerStatus,
                        "provider_reference" => $providerReference,
                        "provider_status" => $providerStatus,
                    ];

                    $this->storeOrderAndDebitWallet($currentUser, $selectedProduct, $responseData);
                    $this->completePurchaseFlow(
                        $responseData,
                        "success",
                        "Purchase submitted successfully",
                        "Your order was sent to the provider successfully."
                    );
                    return;
                }

                $responseData = [
                    "reference" => (string) Str::uuid(),
                    "status" => "processing",
                    "provider_reference" => null,
                    "provider_status" => null,
                ];

                $this->storeOrderAndDebitWallet($currentUser, $selectedProduct, $responseData);

                Log::channel("realest")->warning("Provider purchase failed; order recorded locally for manual follow-up", [
                    "user_id" => Auth::id(),
                    "product_code" => $selectedProduct->code,
                    "message" => $response["message"] ?? "Purchase failed at the provider.",
                    "local_reference" => $responseData["reference"],
                ]);

                $this->flashMessage("warning", "Provider could not process the order right now. A local order has been created for follow-up.");
                $this->completePurchaseFlow(
                    $responseData,
                    "warning",
                    "Order recorded locally",
                    "We could not complete the provider request right now, so your order was saved locally for follow-up."
                );
                return;
            }

            $responseData = [
                "reference" => (string) Str::uuid(),
                "status" => "processing",
                "provider_reference" => null,
                "provider_status" => null,
            ];

            $this->storeOrderAndDebitWallet($currentUser, $selectedProduct, $responseData);
            $this->completePurchaseFlow(
                $responseData,
                "success",
                "Order recorded in test mode",
                "No provider request was sent because the system is running in test mode."
            );
        } catch (\Throwable $e) {
            Log::channel("orders")->error("Agent product purchase failed", [
                "user_id" => Auth::id(),
                "message" => $e->getMessage(),
            ]);
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

    private function storeOrderAndDebitWallet($currentUser, Product $selectedProduct, array $responseData): void
    {
        DB::transaction(function () use ($currentUser, $selectedProduct, $responseData): void {
            $status = $this->normalizeOrderStatus((string) ($responseData["status"] ?? "processing"));
            $reference = (string) ($responseData["reference"] ?? Str::uuid());

            $newOrder = Order::query()->create([
                "code" => $reference,
                "provider_reference" => $responseData["provider_reference"] ?? null,
                "payment_made" => true,
                "status" => $status,
                "provider_status" => $responseData["provider_status"] ?? null,
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
    }

    private function completePurchaseFlow(array $responseData, string $tone, string $heading, string $message): void
    {
        session()->forget(["ORDER_PAYMENT", "TOPUP_ID", "TOPUP_AMOUNT"]);
        $this->lastOrderReference = (string) ($responseData["reference"] ?? "");
        $this->lastPurchaseTone = $tone;
        $this->lastPurchaseHeading = $heading;
        $this->lastPurchaseMessage = $message;

        Log::channel("orders")->info("Agent product purchase completed", [
            "user_id" => Auth::id(),
            "reference" => $this->lastOrderReference,
            "product_code" => $this->selectedProductCode,
            "provider_reference" => $responseData["provider_reference"] ?? null,
            "tone" => $tone,
        ]);

        if ($tone === "success") {
            $this->flashMessage("success", "Purchase Successful");
        }

        $this->wizardStep = "success";
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
