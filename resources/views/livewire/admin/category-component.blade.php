<div class="row g-3 crud-shell">
    <div class="col-12 col-md-3">
        <div class="card crud-card crud-scroll">
          <div class="card-body">
              <h4 class="card-title">Categories</h4>
              <form wire:submit.prevent="saveCategory">
                  <div class="form-group">
                      <label for="">Category Name</label>
                      <input type="text" wire:model.blur="newCategory.name" class="form-control">
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
                            <button wire:click="setForEdit('{{ $category->code }}')" class="btn btn-info btn-sm">
                                <i class="mdi mdi-pencil-outline"></i>
                            </button>
                             <button wire:click="deleteCat('{{ $category->code }}')" class="btn btn-danger btn-sm">
                                <i class="mdi mdi-trash-can-outline"></i>
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
