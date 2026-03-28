<div class="shop-shell">
    @include("partials.alerts_inc")

    <div class="card shop-toolbar-card mb-3">
        <div class="card-body">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h4 class="card-title mb-1">Buy Data Package</h4>
                    <p class="text-muted mb-0">Choose package below. Checkout continues in a modal wizard.</p>
                </div>
                <span class="shop-count-pill">{{ count($allProducts) }} package(s)</span>
            </div>

            <div class="row g-2">
                <div class="col-12 col-md-5">
                    <input
                        type="text"
                        class="form-control"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search bundle size e.g 1, 2, 5"
                    >
                </div>
                <div class="col-12 col-md-4">
                    <select class="form-select" wire:model.live="categoryId">
                        <option value="all">All Networks</option>
                        @foreach($allCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" wire:model.live="stockFilter">
                        <option value="all">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        @forelse($allProducts as $product)
            @php
                $networkName = strtoupper($product->category->name ?? "NETWORK");
                $networkClass = "is-default";

                if (in_array($networkName, ["MTN", "YELLO"])) {
                    $networkClass = "is-mtn";
                } elseif (in_array($networkName, ["VODAFONE", "TELECEL"])) {
                    $networkClass = "is-telecel";
                } elseif (in_array($networkName, ["AT_PREMIUM", "AIRTELTIGO", "AT_BIGTIME"])) {
                    $networkClass = "is-at";
                }
            @endphp

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card shop-product-card h-100 {{ $selectedProduct?->code === $product->code ? "is-selected" : "" }}">
                    <div class="card-body d-flex flex-column">
                        <div class="shop-network-chip {{ $networkClass }}">
                            {{ $networkName }}
                        </div>

                        <h5 class="mt-3 mb-1">{{ $product->name }} GB</h5>
                        <p class="text-muted mb-3">Bundle Size</p>

                        <div class="d-flex align-items-end justify-content-between mt-auto gap-2">
                            <div>
                                <small class="text-muted d-block">Price</small>
                                <h4 class="mb-0">GHS {{ number_format((float) $product->retail_price, 2) }}</h4>
                            </div>

                            @if(!$product->out_to_stock)
                                <button type="button" wire:click="startPurchase('{{ $product->code }}')" class="btn shop-buy-btn">
                                    <i class="fas fa-shopping-cart me-1" aria-hidden="true"></i> Buy Now
                                </button>
                            @else
                                <span class="shop-stock-badge">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shop-empty-card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open font-size-24 d-block mb-2" aria-hidden="true"></i>
                        <h5 class="mb-1">No packages found</h5>
                        <p class="text-muted mb-0">Try changing your search or filter options.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <div class="modal fade" wire:ignore.self id="purchaseWizardModal" tabindex="-1" aria-labelledby="purchaseWizardModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shop-wizard-modal-content">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title" id="purchaseWizardModalLabel">Purchase Wizard</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:click="backToBrowse"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="shop-wizard-steps mb-3">
                        <span class="step-chip {{ in_array($wizardStep, ["recipient", "confirm", "success"]) ? "is-done" : "" }}">1. Select</span>
                        <span class="step-chip {{ in_array($wizardStep, ["confirm", "success"]) ? "is-done" : "" }}">2. Recipient</span>
                        <span class="step-chip {{ $wizardStep === "success" ? "is-done" : "" }}">3. Confirm</span>
                    </div>

                    @if(in_array($wizardStep, ["recipient", "confirm", "success"]) && $selectedProduct)
                        <div class="shop-wizard-panel mb-3">
                            <p class="mb-1 text-muted">Selected Package</p>
                            <h6 class="mb-1">{{ strtoupper($selectedProduct->category->name ?? "NETWORK") }} - {{ $selectedProduct->name }} GB</h6>
                            <p class="mb-0">GHS {{ number_format((float) $selectedProduct->retail_price, 2) }}</p>
                        </div>
                    @endif

                    @if($wizardStep === "recipient")
                        <div class="mb-3">
                            <label class="form-label">Recipient Phone Number</label>
                            <input
                                type="text"
                                class="form-control"
                                wire:model.live.debounce.300ms="recipientPhone"
                                placeholder="e.g. 0551234567"
                            >
                            @error("recipientPhone")
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" wire:click="backToBrowse">Cancel</button>
                            <button type="button" class="btn shop-buy-btn ms-auto" wire:click="proceedToConfirm">Continue</button>
                        </div>
                    @endif

                    @if($wizardStep === "confirm" && $selectedProduct)
                        <div class="shop-wizard-panel mb-3">
                            <p class="mb-1 text-muted">Recipient</p>
                            <h6 class="mb-0">{{ $recipientPhone }}</h6>
                        </div>
                        <div class="shop-wizard-panel mb-3">
                            <p class="mb-1 text-muted">You will be charged</p>
                            <h4 class="mb-0">GHS {{ number_format((float) $selectedProduct->retail_price, 2) }}</h4>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" wire:click="$set('wizardStep','recipient')" wire:loading.attr="disabled" wire:target="submitPurchase">Back</button>
                            <button type="button" class="btn shop-buy-btn ms-auto" wire:click="submitPurchase" wire:loading.attr="disabled" wire:target="submitPurchase">
                                <span wire:loading wire:target="submitPurchase" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                                Confirm Purchase
                            </button>
                        </div>
                    @endif

                    @if($wizardStep === "success")
                        <div class="shop-wizard-panel mb-3">
                            <h6 class="text-success mb-1">Purchase submitted successfully</h6>
                            @if(!blank($lastOrderReference))
                                <p class="mb-0 text-muted">Reference: {{ $lastOrderReference }}</p>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-secondary" wire:click="backToBrowse">Close</button>
                            <a href="{{ route("agent.orders") }}" class="btn shop-buy-btn ms-auto">View Orders</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        let purchaseWizardModal = null;

        const getPurchaseWizardModal = () => {
            const modalEl = document.getElementById("purchaseWizardModal");
            if (!modalEl) {
                return null;
            }
            purchaseWizardModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            return purchaseWizardModal;
        };

        $wire.on("openPurchaseWizard", () => {
            const modal = getPurchaseWizardModal();
            if (modal) {
                modal.show();
            }
        });

        $wire.on("closePurchaseWizard", () => {
            const modal = getPurchaseWizardModal();
            if (modal) {
                modal.hide();
            }
        });
    </script>
    @endscript
</div>
