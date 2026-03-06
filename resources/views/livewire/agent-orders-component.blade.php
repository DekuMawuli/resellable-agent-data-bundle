<div class="row">
    <div class="col-12">
        <div class="card crud-card">
            <div class="card-body">
                <h4 class="card-title">My Orders</h4>
                @include("partials.alerts_inc")
                <div class="table-responsive">
                    <table class="table table-striped table-inverse table-responsive-sm">
                            <thead class="thead-inverse">
                            <tr>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>Payment Confirmed ?</th>
                                <th>Status</th>
                                <th>Created At</th>
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
                                        <td>
                                            {{ optional(optional($order->product)->category)->name }}
                                            {{ optional($order->product)->name }}GB
                                        </td>
                                        <td>GHS {{ number_format((float) $order->total_amount, 2) }}</td>
                                        <td>
                                            @if($order->payment_made)
                                                <span class="badge bg-success">Yes</span>
                                            @else
                                                <span class="badge bg-warning text-dark">No</span>
                                            @endif
                                        </td>
                                        <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst((string) $order->status) }}</span></td>
                                        <td>{{ $order->created_at?->format("d-m-Y H:i") }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">No orders found.</td>
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
</div>
