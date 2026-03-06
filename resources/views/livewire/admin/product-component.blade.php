<div class="row g-3 crud-shell">
    <div class="col-12">
        @include("partials.alerts_inc")
    </div>
    <div class="col-12 col-md-3">
        <div class="card crud-card crud-scroll">
          <div class="card-body">
              <h4 class="card-title">Add Products</h4>
                <form wire:submit.prevent="saveProduct">
                  <div class="form-group">
                      <label for="">Bundle Size</label>
                      <input type="number" wire:model.blur="name" class="form-control">
                      @error("name")
                          <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror
                  </div>
                  <div class="form-group">
                      <label for="">Network</label>
                      <select class="form-control" wire:model.lazy="categoryId">
                          <option value="">Select Network</option>
                          @foreach($allCategories as $category)
                              <option value="{{ $category->id }}">{{ $category->name }}</option>
                          @endforeach
                      </select>
                      @error("categoryId")
                          <small class="text-danger d-block mt-1">{{ $message }}</small>
                      @enderror
                  </div>
                  <div class="form-group">
                      <label for="">Price</label>
                      <input type="number" step="0.1" wire:model.blur="retailPrice" class="form-control">
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
                            <button wire:click="setForEdit('{{ $product->code }}')" class="btn btn-info btn-sm">
                                <i class="mdi mdi-pencil-outline"></i>
                            </button>
                             <button wire:click="deleteProduct('{{ $product->code }}')" class="btn btn-danger btn-sm">
                                <i class="mdi mdi-trash-can-outline"></i>
                            </button>
                            <button wire:click="toggleStockStatus('{{ $product->code }}')" class="btn btn-warning btn-sm">
                                <i class="mdi mdi-store-outline"></i> Toggle Stock
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
