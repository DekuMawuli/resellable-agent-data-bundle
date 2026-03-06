@extends("layouts.default")


@section("title", "My Orders")


@section("content")

  <div class="container-fluid content-top-gap">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb my-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route("agent.dashboard") }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Orders</li>
      </ol>
    </nav>

    @livewire("agent-orders-component")

  </div>

@endsection
