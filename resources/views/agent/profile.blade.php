@extends("layouts.default")

@section("title", "Profile")

@section("content")
    <div class="container-fluid content-top-gap">
        <div class="py-3 py-lg-4">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h4 class="page-title mb-0">Profile</h4>
                </div>
                <div class="col-lg-6">
                    <div class="d-none d-lg-block">
                        <ol class="breadcrumb m-0 float-end">
                            <li class="breadcrumb-item">
                                <a href="{{ auth()->user()->role === 'admin' ? route('root.dashboard') : route('agent.dashboard') }}">Home</a>
                            </li>
                            <li class="breadcrumb-item active">Profile</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @livewire("user.profile-details-component")
    </div>
@endsection
