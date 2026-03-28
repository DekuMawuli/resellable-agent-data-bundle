<div class="row g-3 crud-shell">

@push('styles')
<style>
/* ── Catalog panel ──────────────────────────────────────────────────────── */
.catalog-panel {
    border: 1px solid #e0e7ff;
    border-radius: .5rem;
    overflow: hidden;
    margin-top: .75rem;
    background: #fafbff;
}
.catalog-panel__head {
    background: #eef2ff;
    border-bottom: 1px solid #e0e7ff;
    padding: .45rem .7rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .4rem;
}
.catalog-panel__title {
    font-size: .68rem;
    font-weight: 700;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: #4338ca;
}
.catalog-panel__hint {
    font-size: .63rem;
    color: #818cf8;
}
.catalog-panel__body {
    padding: .35rem .5rem;
    display: flex;
    flex-direction: column;
    gap: .25rem;
    max-height: 240px;
    overflow-y: auto;
}
.catalog-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: .5rem;
    padding: .35rem .5rem;
    border-radius: .35rem;
    cursor: pointer;
    border: 1px solid transparent;
    transition: background .12s, border-color .12s;
}
.catalog-item:hover { background: #e0e7ff; border-color: #c7d2fe; }
.catalog-item.is-selected { background: #e0e7ff; border-color: #6366f1; }
.catalog-item__size {
    font-weight: 700;
    font-size: .82rem;
    color: #1e1b4b;
    white-space: nowrap;
}
.catalog-item__cost {
    font-size: .75rem;
    color: #6366f1;
    font-weight: 600;
    white-space: nowrap;
}
.catalog-item__use {
    font-size: .63rem;
    color: #a5b4fc;
    border: 1px solid #c7d2fe;
    border-radius: 9999px;
    padding: .1rem .45rem;
    white-space: nowrap;
    flex-shrink: 0;
}
.catalog-item:hover .catalog-item__use {
    color: #4338ca;
    border-color: #6366f1;
}
.catalog-panel__status {
    padding: .6rem .7rem;
    font-size: .76rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.catalog-panel__status--empty   { color: #9ca3af; }
.catalog-panel__status--error   { color: #dc2626; }
.catalog-panel__status--loading { color: #6366f1; }
.catalog-panel--loading {
    pointer-events: none;
    opacity: .6;
}
.catalog-loading-bar {
    height: 3px;
    background: linear-gradient(90deg, #6366f1 0%, #818cf8 50%, #6366f1 100%);
    background-size: 200% 100%;
    animation: catalogBarSweep 1.1s linear infinite;
    border-radius: 9999px;
    margin: .5rem .7rem;
}
@keyframes catalogBarSweep {
    0%   { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
.bundle-size-locked {
    background: #f5f7ff !important;
    color: #a5b4fc !important;
    cursor: not-allowed !important;
    border-color: #c7d2fe !important;
}
.bundle-size-hint {
    font-size: .68rem;
    margin-top: .3rem;
    display: flex;
    align-items: center;
    gap: .3rem;
}
.catalog-cost-note {
    font-size: .65rem;
    color: #6366f1;
    background: #eef2ff;
    border-radius: 0 0 .5rem .5rem;
    padding: .3rem .7rem;
    border-top: 1px dashed #c7d2fe;
    display: flex;
    align-items: center;
    gap: .35rem;
}
</style>
@endpush

    <div class="col-12">
        @include("partials.alerts_inc")
    </div>
    <div class="col-12 col-md-3">
        <div class="card crud-card crud-scroll">
          <div class="card-body">
              <h4 class="card-title">{{ $updateMode ? 'Edit Product' : 'Add Product' }}</h4>
                <form wire:submit.prevent="saveProduct">

                  <div class="form-group">
                      <label for="">Network</label>
                      <select class="form-control" wire:model.live="categoryId">
                          <option value="">Select Network</option>
                          @foreach($allCategories as $category)
                              <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                      </select>
                      @error("categoryId")
                          <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror

                      {{-- ── Realest catalog reference panel ─────────────── --}}
                      @if ($categoryId)
                          <div class="catalog-panel mt-2">
                              <div class="catalog-panel__head">
                                  <span class="catalog-panel__title">
                                      <i class="fas fa-satellite-dish me-1"></i>Realest Catalog
                                  </span>
                                  <span class="catalog-panel__hint" wire:loading.remove wire:target="categoryId,setForEdit">
                                      tap a row to use
                                  </span>
                                  <span class="catalog-panel__hint" wire:loading wire:target="categoryId,setForEdit"
                                        style="color:#6366f1;font-weight:600;">
                                      loading…
                                  </span>
                              </div>

                              {{-- Animated progress bar while fetching --}}
                              <div wire:loading wire:target="categoryId,setForEdit">
                                  <div class="catalog-loading-bar"></div>
                              </div>

                              <div wire:loading.remove wire:target="categoryId,setForEdit">
                                  @if ($catalogStatus === 'loaded' && count($catalogProducts))
                                      <div class="catalog-panel__body">
                                          @foreach ($catalogProducts as $item)
                                              <div
                                                  class="catalog-item {{ (string)$name === (string)$item['name'] ? 'is-selected' : '' }}"
                                                  wire:click="fillFromCatalog('{{ $item['name'] }}')"
                                              >
                                                  <span class="catalog-item__size">{{ $item['name'] }} GB</span>
                                                  <span class="catalog-item__cost">GHS {{ number_format((float)$item['agent_price'], 2) }}</span>
                                                  <span class="catalog-item__use">use</span>
                                              </div>
                                          @endforeach
                                      </div>
                                      <div class="catalog-cost-note">
                                          <i class="fas fa-info-circle"></i>
                                          Prices shown are your cost. Set your retail price above these.
                                      </div>

                                  @elseif ($catalogStatus === 'empty')
                                      <div class="catalog-panel__status catalog-panel__status--empty">
                                          <i class="fas fa-inbox"></i> No products found for this network in the catalog.
                                      </div>

                                  @elseif ($catalogStatus === 'unconfigured')
                                      <div class="catalog-panel__status catalog-panel__status--error">
                                          <i class="fas fa-key"></i> Realest API key not configured.
                                      </div>

                                  @elseif ($catalogStatus === 'error')
                                      <div class="catalog-panel__status catalog-panel__status--error">
                                          <i class="fas fa-exclamation-triangle"></i> Could not load catalog. Check API credentials.
                                      </div>
                                  @endif
                              </div>
                          </div>
                      @endif
                      {{-- ── end catalog panel ────────────────────────────── --}}
                  </div>

                  <div class="form-group">
                      @php $catalogLocked = $catalogStatus === 'loaded'; @endphp
                      <label for="">
                          Bundle Size (GB)
                          @if ($catalogLocked)
                              <span style="font-size:.65rem;font-weight:400;color:#818cf8;margin-left:.35rem;">
                                  <i class="fas fa-lock" style="font-size:.6rem;"></i> select from catalog
                              </span>
                          @endif
                      </label>
                      <input
                          type="number"
                          wire:model.blur="name"
                          class="form-control {{ $catalogLocked ? 'bundle-size-locked' : '' }}"
                          placeholder="{{ $catalogLocked ? 'Pick a size from the catalog above' : 'e.g. 7' }}"
                          @if ($catalogLocked) disabled readonly @endif
                      >
                      @if ($catalogLocked && !$name)
                          <div class="bundle-size-hint text-muted">
                              <i class="fas fa-arrow-up" style="font-size:.6rem;color:#818cf8;"></i>
                              <span style="font-size:.68rem;">Click a product in the catalog to set the size.</span>
                          </div>
                      @endif
                      @error("name")
                          <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror
                  </div>

                  <div class="form-group">
                      <label for="">Your Retail Price</label>
                      <input type="number" step="0.01" wire:model.blur="retailPrice" class="form-control"
                          placeholder="Set price for agents">
                      @error("retailPrice")
                          <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror
                  </div>

                  <div class="form-group">
                      <label for="">Out of Stock</label>
                      <select class="form-control" wire:model.lazy.boolean="outOfStock">
                          <option value="0">No</option>
                          <option value="1">Yes</option>
                      </select>
                      @error("outOfStock")
                          <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror
                  </div>

                  <div class="form-group">
                      @if($updateMode)
                          <button type="submit" class="btn btn-info btn-block">Update</button>
                          <button type="button" wire:click="clearSelection" class="btn btn-dark btn-block">Clear</button>
                      @else
                          <button type="submit" class="btn btn-success btn-block">Save</button>
                      @endif
                  </div>

              </form>
          </div>
      </div>
    </div>

    <div class="col-12 col-md-9">
        <div class="card crud-card crud-scroll">
            <div class="card-body">
                <h4 class="card-title">All Products</h4>
                <div class="table-responsive">
                <table class="table table-striped table-inverse table-responsive-sm">
            <thead>
            <tr>
                <th>Network</th>
                <th>Name</th>
                <th>Retail Price</th>
                <th>Out of Stock</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach($allProducts as $product)
                    <tr>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->name }}GB</td>
                        <td>{{ $product->agent_price }}</td>
                        <td>
                            @if($product->out_to_stock)
                                <span class="badge badge-danger">Out of Stock</span>
                            @else
                                <span class="badge badge-success">Available</span>
                            @endif
                        </td>
                        <td>
                            <div class="action-group">
                                {{-- Edit --}}
                                <button
                                    wire:click="setForEdit('{{ $product->code }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="setForEdit,deleteProduct,toggleStockStatus"
                                    class="btn btn-info btn-sm">
                                    <span wire:loading wire:target="setForEdit('{{ $product->code }}')"
                                          class="spinner-border spinner-border-sm" role="status"></span>
                                    <i class="fas fa-pen"
                                       wire:loading.remove wire:target="setForEdit('{{ $product->code }}')"></i>
                                </button>

                                {{-- Delete --}}
                                <button
                                    wire:click="deleteProduct('{{ $product->code }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="setForEdit,deleteProduct,toggleStockStatus"
                                    class="btn btn-danger btn-sm">
                                    <span wire:loading wire:target="deleteProduct('{{ $product->code }}')"
                                          class="spinner-border spinner-border-sm" role="status"></span>
                                    <i class="fas fa-trash-alt"
                                       wire:loading.remove wire:target="deleteProduct('{{ $product->code }}')"></i>
                                </button>

                                {{-- Toggle Stock --}}
                                <button
                                    wire:click="toggleStockStatus('{{ $product->code }}')"
                                    wire:loading.attr="disabled"
                                    wire:target="setForEdit,deleteProduct,toggleStockStatus"
                                    class="btn btn-warning btn-sm">
                                    <span wire:loading wire:target="toggleStockStatus('{{ $product->code }}')"
                                          class="spinner-border spinner-border-sm" role="status"></span>
                                    <i class="fas fa-store"
                                       wire:loading.remove wire:target="toggleStockStatus('{{ $product->code }}')"></i>
                                    <span wire:loading.remove wire:target="toggleStockStatus('{{ $product->code }}')">
                                        Toggle Stock
                                    </span>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                </div>
            </div>
        </div>
    </div>
</div>
