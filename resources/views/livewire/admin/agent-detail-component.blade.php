<div class="row g-3">
    <div class="col-12">
        @include('partials.alerts_inc')
    </div>

    @if($agent)
        @php
            $agentStatusClass = $agent->agent_status === 'active' ? 'success' : 'warning';
        @endphp

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                        <div>
                            <h4 class="mb-1">{{ $agent->name }}</h4>
                            <p class="text-muted mb-1">{{ $agent->phone }}</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-{{ $agentStatusClass }}">{{ strtoupper($agent->agent_status) }}</span>
                                <span class="badge bg-info">Code: {{ $agent->code }}</span>
                            </div>
                        </div>

                        <div class="text-end">
                            <p class="text-muted mb-1">Current Balance</p>
                            <h3 class="mb-0">GHS {{ number_format((float) $agent->balance, 2) }}</h3>
                        </div>
                    </div>

                    @if($agent->agent_status !== 'active')
                        <div class="mt-3">
                            <button wire:click="activateAcc" class="btn btn-success btn-sm">
                                <i class="mdi mdi-account-check-outline"></i> Activate Account
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1" style="font-size: 12px;">Total Orders</p>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1" style="font-size: 12px;">Completed Orders</p>
                    <h3 class="mb-0 text-success">{{ $completedOrders }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted text-uppercase mb-1" style="font-size: 12px;">Total Deposits</p>
                    <h3 class="mb-0 text-primary">GHS {{ number_format((float) $totalDepositsAmount, 2) }}</h3>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Top Up Agent Wallet</h5>
                    <form wire:submit.prevent="topUp" class="row g-2 align-items-end">
                        <div class="col-12 col-md-4">
                            <label class="form-label">Amount (GHS)</label>
                            <input type="number" step="0.01" wire:model.live="amount" class="form-control" placeholder="Enter amount">
                        </div>
                        <div class="col-12 col-md-2">
                            <button type="submit" class="btn btn-success w-100">Top Up</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Orders</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Package</th>
                                <th>Amount</th>
                                <th>Recipient</th>
                                <th>Initiated</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                @php
                                    $statusClass = match($order->status) {
                                        'completed' => 'success',
                                        'processing' => 'primary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        {{ optional(optional($order->product)->category)->name }}
                                        {{ optional($order->product)->name }}
                                    </td>
                                    <td>{{ number_format((float) optional($order->product)->agent_price, 2) }}</td>
                                    <td>{{ $order->phone_number }}</td>
                                    <td>{{ $order->created_at?->format('d-m-Y H:i') }}</td>
                                    <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($order->status) }}</span></td>
                                    <td>
                                        @if($order->status == 'pending')
                                            <a href="{{ route('root.confirmPurchase', $order->id) }}" class="btn btn-sm btn-info">Confirm</a>
                                        @elseif($order->status == 'processing' && blank($order->provider_reference))
                                            <a href="{{ route('root.approvePurchase', $order->id) }}" class="btn btn-sm btn-success">Approve</a>
                                        @elseif($order->status == 'processing')
                                            <span class="text-muted">Forwarded</span>
                                        @else
                                            <span class="text-success">Done</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No orders found for this agent.</td>
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

        <div class="col-12 col-xl-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title mb-3">Recent Deposits</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                            <tr>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($deposits as $deposit)
                                @php
                                    $depositStatusClass = match($deposit->status) {
                                        'completed' => 'success',
                                        'processing' => 'primary',
                                        default => 'secondary'
                                    };
                                @endphp
                                <tr>
                                    <td>GHS {{ number_format((float) $deposit->amount, 2) }}</td>
                                    <td><span class="badge bg-{{ $depositStatusClass }}">{{ ucfirst($deposit->status) }}</span></td>
                                    <td>{{ $deposit->created_at?->format('d-m-Y H:i') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-4">No deposits found.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="col-12">
            <div class="alert alert-warning mb-0">Agent not found.</div>
        </div>
    @endif
</div>
