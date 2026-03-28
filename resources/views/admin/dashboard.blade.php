@extends("layouts.default")

@section("title", "Dashboard")

@section("content")
    <div class="container-fluid content-top-gap">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb my-breadcrumb mb-1">
                        <li class="breadcrumb-item"><a href="{{ route('root.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>
                <h4 class="mb-0">Admin Dashboard</h4>
                <p class="text-muted mb-0">Monitor activity and manage operations from one place.</p>
            </div>
        </div>

        @include("partials.alerts_inc")

        <div class="row g-3 mb-3">
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase font-size-12 text-muted mb-3">Active Agents</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="h3 mb-0 text-primary">{{ $activeAgents }}</span>
                            <i class="fas fa-user-check font-size-24 text-primary" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase font-size-12 text-muted mb-3">Today's Sales</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="h3 mb-0 text-info">GHS {{ number_format((float) $todaySales, 2) }}</span>
                            <i class="fas fa-money-bill-wave font-size-24 text-info" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase font-size-12 text-muted mb-3">Account Balance</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="h3 mb-0 text-success">
                                @if (is_numeric($balance))
                                    GHS {{ number_format((float) $balance, 2) }}
                                @else
                                    {{ $balance }}
                                @endif
                            </span>
                            <i class="fas fa-wallet font-size-24 text-success" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="text-uppercase font-size-12 text-muted mb-3">Total Deposits</h6>
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="h3 mb-0 text-danger">{{ $totalDeposits }}</span>
                            <i class="fas fa-coins font-size-24 text-danger" aria-hidden="true"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12 col-xl-6">
                @livewire("admin.admin-sales-chart-component")
            </div>
            <div class="col-12 col-xl-6">
                @livewire("admin.admin-topups-chart-component")
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                            <h4 class="card-title mb-0">Recent Orders</h4>
                            <a href="{{ route('root.orders') }}" class="btn btn-sm btn-primary">
                                View all
                            </a>
                        </div>
                        @livewire('admin.admin-dashboard-orders-component')
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h4 class="card-title mb-0">Recent Deposits</h4>
                            <span class="badge bg-success">Latest 10</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle mb-0">
                                <thead>
                                <tr>
                                    <th>Agent</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($deposits as $deposit)
                                    @php
                                        $statusClass = match(strtolower((string) $deposit->status)) {
                                            "completed" => "success",
                                            "processing" => "primary",
                                            "pending" => "warning text-dark",
                                            "cancelled" => "danger",
                                            default => "secondary",
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ optional($deposit->customer)->name ?? "N/A" }}</td>
                                        <td>GHS {{ number_format((float) $deposit->amount, 2) }}</td>
                                        <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst((string) $deposit->status) }}</span></td>
                                        <td>{{ $deposit->created_at?->format("d-m-Y H:i") }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">No deposits found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
