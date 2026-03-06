<div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <div>
                    <h4 class="mb-1">Browse Bundles</h4>
                    <p class="mb-0 text-muted">Find the right package quickly.</p>
                </div>
                <span class="badge bg-dark-subtle text-dark">{{ $products->count() }} bundle(s)</span>
            </div>

            <div class="row g-2">
                <div class="col-12 col-md-5">
                    <input
                        type="text"
                        class="form-control"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Search by size or network"
                    >
                </div>
                <div class="col-12 col-md-4">
                    <select class="form-select" wire:model.change="categoryCode">
                        <option value="all">All Networks</option>
                        @foreach($categories as $category)
                            <option value="{{ strtolower((string) $category->code) }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" wire:model.change="stockFilter">
                        <option value="all">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @forelse($products as $product)
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
                $buyLink = auth()->check() ? route("agent.products") : route("pages.login");
            @endphp

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="public-product-card">
                    <div class="public-network-chip {{ $networkClass }}">{{ $networkName }}</div>
                    <div class="public-product-body">
                        <h3 class="public-product-title">{{ $product->name }} GB</h3>
                        <p class="public-product-subtitle">{{ $product->category->name }} Bundle</p>
                        <div class="public-product-footer">
                            <div>
                                <span class="public-price-label">Price</span>
                                <h4 class="public-price-value">GHS {{ number_format((float) $product->agent_price, 2) }}</h4>
                            </div>
                            @if(!$product->out_to_stock)
                                <a href="{{ $buyLink }}" class="btn-primary slide-btn public-buy-btn">Buy Now</a>
                            @else
                                <span class="public-stock-badge">Out of Stock</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <h5 class="mb-1">No bundles found</h5>
                        <p class="text-muted mb-0">Try a different search or filter.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>
