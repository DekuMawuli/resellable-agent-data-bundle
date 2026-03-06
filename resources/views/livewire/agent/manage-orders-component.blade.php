<div class="card crud-card h-100">
  <div class="card-body">
    <h4 class="card-title">Recent Orders</h4>
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>Package</th>
            <th>Amount</th>
            <th>Recipient</th>
            <th>Ordered At</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @forelse($orders as $order)
            @php
              $statusClass = match(strtolower((string) $order->status)) {
                "success", "completed" => "success",
                "processing" => "primary",
                "failed", "error", "cancelled" => "danger",
                default => "secondary",
              };
            @endphp
            <tr>
              <td>{{ optional(optional($order->product)->category)->name }} {{ optional($order->product)->name }}GB</td>
              <td>GHS {{ number_format((float) $order->total_amount, 2) }}</td>
              <td>{{ $order->phone_number }}</td>
              <td>{{ $order->created_at?->format("d-m-Y H:i") }}</td>
              <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst((string) $order->status) }}</span></td>
              <td>
                @if(!in_array(strtolower((string) $order->status), ["completed", "success"], true))
                  <button class="btn btn-outline-info btn-sm" wire:click="checkStatus('{{ $order->code }}')">
                    Check Status
                  </button>
                @endif
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" class="text-center text-muted py-4">No orders available.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
