<div class="row g-3 crud-shell">
    <div class="col-12 col-md-3">
        <div class="card crud-card crud-scroll">
          <div class="card-body">
              <h4 class="card-title">Categories</h4>
              <form wire:submit.prevent="saveCategory">
                  <div class="form-group">
                      <label for="">Category Name</label>
                      <input type="text" wire:model.blur="newCategory.name" class="form-control">
                      <div class="small text-muted mt-2 d-none align-items-center gap-2" wire:loading.flex wire:target="newCategory.name">
                          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                          Syncing category name...
                      </div>
                  </div>
                  <div class="form-group">
                      @if($updateMode)
                          <button type="submit" class="btn btn-info btn-block" wire:loading.attr="disabled" wire:target="saveCategory">
                              <span wire:loading wire:target="saveCategory" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                              <span wire:loading.remove wire:target="saveCategory">Update</span>
                              <span wire:loading wire:target="saveCategory">Updating...</span>
                          </button>
                          <button type="button" wire:click="clearSelection" wire:loading.attr="disabled" wire:target="clearSelection" class="btn btn-dark btn-block">
                              <span wire:loading wire:target="clearSelection" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                              <span wire:loading.remove wire:target="clearSelection">Clear</span>
                              <span wire:loading wire:target="clearSelection">Clearing...</span>
                          </button>
                      @else
                          <button type="submit" class="btn btn-success btn-block" wire:loading.attr="disabled" wire:target="saveCategory">
                              <span wire:loading wire:target="saveCategory" class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                              <span wire:loading.remove wire:target="saveCategory">Save</span>
                              <span wire:loading wire:target="saveCategory">Saving...</span>
                          </button>
                      @endif
                  </div>

              </form>
          </div>
      </div>
    </div>
    <div class="col-12 col-md-9">
        <div class="card crud-card crud-scroll">
            <div class="card-body">
                <h4 class="card-title">All Categories</h4>
                @include("partials.alerts_inc")
                <div class="table-responsive">
                <table class="table table-striped table-inverse table-responsive-sm">
            <thead>
            <tr>
                <th>Name</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
                @foreach($allCategories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>
                            <div class="action-group">
                            <button
                                type="button"
                                wire:click="setForEdit('{{ $category->code }}')"
                                wire:loading.attr="disabled"
                                wire:target="setForEdit('{{ $category->code }}')"
                                class="btn btn-info btn-sm"
                            >
                                <span wire:loading wire:target="setForEdit('{{ $category->code }}')" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i class="fas fa-pen" aria-hidden="true" wire:loading.remove wire:target="setForEdit('{{ $category->code }}')"></i>
                            </button>
                             <button
                                type="button"
                                wire:click="deleteCat('{{ $category->code }}')"
                                wire:loading.attr="disabled"
                                wire:target="deleteCat('{{ $category->code }}')"
                                class="btn btn-danger btn-sm"
                            >
                                <span wire:loading wire:target="deleteCat('{{ $category->code }}')" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                <i class="fas fa-trash-alt" aria-hidden="true" wire:loading.remove wire:target="deleteCat('{{ $category->code }}')"></i>
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
