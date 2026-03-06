@extends("layouts.default")


@section("title", "All Products")


@section("content")

  <div class="container-fluid content-top-gap">

    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
      <div>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb my-breadcrumb mb-1">
            <li class="breadcrumb-item"><a href="{{ route("agent.dashboard") }}">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Buy Package</li>
          </ol>
        </nav>
        <h4 class="mb-0">Buy Package</h4>
      </div>
      <a href="{{ route("agent.orders") }}" class="btn btn-outline-primary btn-sm">
        <i class="mdi mdi-clipboard-list-outline me-1"></i> View My Orders
      </a>
    </div>

    @include("partials.alerts_inc")

    @livewire("agent-products-component")

  </div>

@endsection
