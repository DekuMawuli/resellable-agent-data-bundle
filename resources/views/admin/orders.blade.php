@extends("layouts.default")


@section("title", "Orders")


@section("content")

  <div class="container-fluid content-top-gap">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb my-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route("root.dashboard") }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">My Orders</li>
      </ol>
    </nav>

    @include("partials.test_mode_notice", ["noticeContext" => "admin_order"])

    @livewire("admin.orders-component")

  </div>

@endsection
