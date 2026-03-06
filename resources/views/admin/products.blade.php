@extends("layouts.default")


@section("title", "Products")


@section("content")

  <div class="container-fluid content-top-gap">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb my-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route("root.dashboard") }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Products</li>
      </ol>
    </nav>

    @livewire("admin.product-component")

  </div>

@endsection
