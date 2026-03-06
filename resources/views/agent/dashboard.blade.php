@extends("layouts.default")

@section("title", "Dashboard")

@section("content")
  @php
    $hasWhatsappLink = !blank(optional($setting)->whatsapp_link);
    $hasWhatsappNumber = !blank(optional($setting)->whatsapp_number);
    $hasContactNumber = !blank(optional($setting)->contact_number);

    $whatsappHref = $hasWhatsappLink
      ? $setting->whatsapp_link
      : ($hasWhatsappNumber ? "https://wa.me/" . preg_replace('/\D+/', '', (string) $setting->whatsapp_number) : null);
    $telHref = $hasContactNumber
      ? "tel:" . preg_replace('/\D+/', '', (string) $setting->contact_number)
      : null;
  @endphp

  <div class="container-fluid content-top-gap crud-shell">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb my-breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route("agent.dashboard") }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
          </ol>
        </nav>
        <h4 class="mb-0">Agent Overview</h4>
        <p class="text-muted mb-0">Track orders, monitor deposits, and top up wallet quickly.</p>
      </div>

      <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="modal" data-bs-target="#topUp">
        <i class="mdi mdi-wallet-plus-outline me-1"></i> Top Up Balance
      </button>
    </div>

    @include("partials.alerts_inc")

    <div class="row g-3 mb-3">
      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card crud-card h-100">
          <div class="card-body">
            <p class="text-uppercase font-size-12 text-muted mb-1">Total Orders</p>
            <div class="d-flex align-items-center justify-content-between">
              <h3 class="mb-0">{{ $ordersCount }}</h3>
              <i class="mdi mdi-clipboard-list-outline font-size-24 text-primary"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card crud-card h-100">
          <div class="card-body">
            <p class="text-uppercase font-size-12 text-muted mb-1">Completed Orders</p>
            <div class="d-flex align-items-center justify-content-between">
              <h3 class="mb-0">{{ $closedOrders }}</h3>
              <i class="mdi mdi-check-decagram-outline font-size-24 text-success"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card crud-card h-100">
          <div class="card-body">
            <p class="text-uppercase font-size-12 text-muted mb-1">Open Orders</p>
            <div class="d-flex align-items-center justify-content-between">
              <h3 class="mb-0">{{ $openOrders }}</h3>
              <i class="mdi mdi-progress-clock font-size-24 text-warning"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-sm-6 col-xl-3">
        <div class="card crud-card h-100">
          <div class="card-body">
            <p class="text-uppercase font-size-12 text-muted mb-1">Wallet Balance</p>
            <div class="d-flex align-items-center justify-content-between">
              <h3 class="mb-0">GHS {{ number_format((float) auth()->user()->balance, 2) }}</h3>
              <i class="mdi mdi-wallet-outline font-size-24 text-info"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row g-3">
      <div class="col-12 col-xl-7">
        @livewire("agent.manage-orders-component")
      </div>

      <div class="col-12 col-xl-5">
        <div class="card crud-card mb-3">
          <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
              <h4 class="card-title mb-0">Recent Deposits</h4>
              <span class="badge bg-success">GHS {{ number_format((float) $totalDepositsAmount, 2) }}</span>
            </div>

            <div class="table-responsive">
              <table class="table table-striped table-hover align-middle mb-0">
                <thead>
                  <tr>
                    <th>Reference</th>
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
                          "pending" => "warning text-dark",
                          "cancelled" => "danger",
                          default => "secondary",
                      };
                    @endphp
                    <tr>
                      <td>{{ $deposit->code }}</td>
                      <td>GHS {{ number_format((float) $deposit->amount, 2) }}</td>
                      <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst((string) $deposit->status) }}</span></td>
                      <td>{{ $deposit->created_at?->format("d-m-Y H:i") }}</td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="4" class="text-center text-muted py-4">No deposits yet.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="card crud-card">
          <div class="card-body">
            <h4 class="card-title mb-1">Customer Support</h4>
            <p class="text-muted mb-2">Need help with top-up, order processing, or wallet issues?</p>

            <div class="d-flex flex-wrap gap-2">
              @if($telHref)
                <a href="{{ $telHref }}" class="btn btn-primary btn-sm">
                  <i class="mdi mdi-phone-outline me-1"></i> Call Support
                </a>
              @endif

              @if($whatsappHref)
                <a href="{{ $whatsappHref }}" target="_blank" rel="noopener noreferrer" class="btn btn-success btn-sm">
                  <i class="mdi mdi-whatsapp me-1"></i> WhatsApp
                </a>
              @endif
            </div>

            @if(!$telHref && !$whatsappHref)
              <div class="alert alert-warning mb-0 mt-2">
                Support contacts are not configured yet. Please contact admin.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="topUp" tabindex="-1" aria-labelledby="topUpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="topUpModalLabel">Add Cash to your Wallet</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form method="POST" action="{{ route('agent.initPayment') }}">
            @csrf
            <div class="mb-3">
              <label class="form-label">Amount (GHS)</label>
              <input type="number" step="0.01" class="form-control" min="1" name="amount" placeholder="e.g. 5.00, 10.00" required>
            </div>
            <input type="hidden" name="type" value="credit">
            <button type="submit" class="btn btn-primary w-100">Top-up wallet</button>

            <div class="alert alert-primary alert-dismissible fade show mt-3 mb-0" role="alert">
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              <strong>Kindly Note</strong>
              <ul class="mb-0 mt-2">
                <li>When gateway processing fails, contact <b>{{ $setting->contact_number ?? "" }}</b>.</li>
                <li>For manual support, reach us on <b>{{ $setting->contact_number ?? "" }}</b>.</li>
              </ul>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
