<div class="crud-shell">
  <div class="card crud-card">
    <div class="card-body">
      <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
        <h4 class="card-title mb-0">Orders</h4>
        <button class="btn btn-outline-secondary btn-sm" wire:click="clearFilters">Reset Filters</button>
      </div>

      @include("partials.alerts_inc")

      <div class="row g-2 mb-3">
        <div class="col-12 col-md-4">
          <input
            type="text"
            class="form-control"
            wire:model.live.debounce.300ms="search"
            placeholder="Search code, customer, recipient, product..."
          >
        </div>
        <div class="col-6 col-md-2">
          <select class="form-select" wire:model.live="status">
            <option value="all">All Status</option>
            <option value="pending">Pending</option>
            <option value="processing">Processing</option>
            <option value="completed">Completed</option>
            <option value="success">Success</option>
            <option value="failed">Failed</option>
            <option value="cancelled">Cancelled</option>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select class="form-select" wire:model.live="payment">
            <option value="all">All Payment</option>
            <option value="paid">Paid</option>
            <option value="unpaid">Unpaid</option>
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select class="form-select" wire:model.live="agentId">
            <option value="all">All Agents</option>
            @foreach($agents as $agent)
              <option value="{{ $agent->id }}">{{ $agent->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-2">
          <select class="form-select" wire:model.live="categoryId">
            <option value="all">All Networks</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-6 col-md-2">
          <input type="date" class="form-control" wire:model.live="dateFrom">
        </div>
        <div class="col-6 col-md-2">
          <input type="date" class="form-control" wire:model.live="dateTo">
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle mb-0">
          <thead>
            <tr>
              <th>Code</th>
              <th>Customer</th>
              <th>Package</th>
              <th>Amount</th>
              <th>Recipient</th>
              <th>Payment</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($orders as $order)
              @php
                $rawStatus = strtolower((string) $order->status);
                $displayStatus = $rawStatus === "success" ? "completed" : $rawStatus;
                $statusClass = match($displayStatus) {
                    "completed" => "success",
                    "processing" => "primary",
                    "pending" => "warning text-dark",
                    "failed", "cancelled" => "danger",
                    default => "secondary",
                };
              @endphp
              <tr>
                <td>{{ $order->code }}</td>
                <td>{{ optional($order->customer)->name }}</td>
                <td>{{ optional(optional($order->product)->category)->name }} {{ optional($order->product)->name }}</td>
                <td>GHS {{ number_format((float) $order->total_amount, 2) }}</td>
                <td>{{ $order->phone_number }}</td>
                <td>
                  @if($order->payment_made)
                    <span class="badge bg-success">Paid</span>
                  @else
                    <span class="badge bg-warning text-dark">Unpaid</span>
                  @endif
                </td>
                <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($displayStatus) }}</span></td>
                <td>{{ $order->created_at?->format("d-m-Y H:i") }}</td>
                <td>
                  <div class="action-group">
                    @if($displayStatus === "pending")
                      <button
                        type="button"
                        class="btn btn-info btn-sm"
                        wire:click="confirmPurchase({{ $order->id }})"
                        wire:loading.attr="disabled"
                        wire:target="confirmPurchase"
                      >
                        Confirm
                      </button>
                    @elseif($displayStatus === "processing" && blank($order->provider_reference))
                      <button
                        type="button"
                        class="btn btn-success btn-sm"
                        wire:click="approvePurchase({{ $order->id }})"
                        wire:loading.attr="disabled"
                        wire:target="approvePurchase"
                      >
                        Approve
                      </button>
                    @elseif($displayStatus === "processing")
                      <span class="text-muted">Forwarded</span>
                    @endif
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="text-center text-muted py-4">No orders found for current filters.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="mt-3">
        {{ $orders->links() }}
      </div>
    </div>
  </div>
</div>
