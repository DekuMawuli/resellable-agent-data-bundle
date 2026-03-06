@extends("layouts.default")

@section("title", "Confirm Deposit")

@section("content")
    <div class="container-fluid content-top-gap">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb my-breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route("agent.dashboard") }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Confirm Deposit</li>
            </ol>
        </nav>

        <div class="row justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <img src="{{ asset('images/confirm.svg') }}" alt="Confirm Payment" class="img-fluid mb-3" style="max-height: 260px;">
                        <h4 class="mb-2">Confirm Payment</h4>
                        <p class="text-muted mb-4">
                            Only click after payment is complete to <b>0597433238</b> (NBALINO VENTURES).<br>
                            Amount: <b>{{ \Illuminate\Support\Facades\Session::get("TOPUP_AMOUNT") ?? 0 }}</b><br>
                            Reference: <b>{{ auth()->user()->name }}</b>
                        </p>
                        <a class="btn btn-primary" href="{{ route("agent.confirmPayment") }}">
                            Yes, I have made payment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
