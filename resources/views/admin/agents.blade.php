@extends("layouts.default")


@section("title", "Agents")


@section("content")

  <div class="container-fluid content-top-gap">

    <nav aria-label="breadcrumb">
      <ol class="breadcrumb my-breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route("root.dashboard") }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Agents</li>
      </ol>
    </nav>

      @livewire("admin.agents-component")

  </div>


  <script>
      window.addEventListener('show-topup-modal', event => {
          $("#showTopUpModal").modal("show")
      })
      window.addEventListener('close-topup-modal', event => {
          $("#showTopUpModal").modal("hide")
      })
  </script>

@endsection
