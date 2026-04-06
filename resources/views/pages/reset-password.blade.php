@extends("layouts.page_base")


@section("title", "Reset Password")

@section("content")
<main id="MainContent" class="content-for-layout">
            <style>
                .auth-brand-logo {
                    display: flex;
                    justify-content: center;
                    margin-bottom: 10px;
                }

                .auth-brand-logo img {
                    height: 34px;
                    width: auto;
                }
            </style>
            <div class="login-page mt-100">
                <div class="container">
                    <form action="{{ route('password.update') }}" method="POST" class="login-form common-form mx-auto">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <div class="section-header mb-3">
                            <div class="auth-brand-logo">
                                <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
                            </div>
                            <h2 class="section-heading text-center">Reset Password</h2>
                            <p class="text-center">
                                Create a new password below. This reset link expires 30 minutes after it is sent.
                            </p>
                            @include("partials.auth_alert_incs")
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', $email) }}" required />
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">New Password</label>
                                    <input type="password" name="password" required />
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" required />
                                </fieldset>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn-primary d-block mt-4 btn-signin">RESET PASSWORD</button>
                                <p class="mt-4 text-center">Back to account access? <a href="{{ route('pages.login') }}" class="text_14 text-primary">Sign In</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
@endsection
