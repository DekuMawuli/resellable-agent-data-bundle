@extends("layouts.page_base")


@section("title", "Forgot Password")

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
                    @if ($verifiedPhone)
                        <form action="{{ route('password.email') }}" method="POST" class="login-form common-form mx-auto">
                            @csrf
                            <div class="section-header mb-3">
                                <div class="auth-brand-logo">
                                    <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
                                </div>
                                <h2 class="section-heading text-center">Send Reset Link</h2>
                                <p class="text-center">
                                    Phone number verified. Enter the email address that should receive your 30-minute password reset link.
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
                                        <label class="label">Verified Phone Number</label>
                                        <input type="text" value="{{ $verifiedPhone }}" disabled />
                                    </fieldset>
                                </div>
                                <div class="col-12">
                                    <fieldset>
                                        <label class="label">Email Address</label>
                                        <input type="email" name="email" value="{{ old('email') }}" required />
                                    </fieldset>
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn-primary d-block mt-4 btn-signin">EMAIL RESET LINK</button>
                                    <p class="mt-4 text-center">Need to use another phone number? <a href="{{ route('password.request', ['change_phone' => 1]) }}" class="text_14 text-primary">Change Phone Number</a></p>
                                    <p class="mt-3 text-center">Remember your password? <a href="{{ route('pages.login') }}" class="text_14 text-primary">Back to Sign In</a></p>
                                </div>
                            </div>
                        </form>
                    @else
                        <form action="{{ route('password.phone.verify') }}" method="POST" class="login-form common-form mx-auto">
                            @csrf
                            <div class="section-header mb-3">
                                <div class="auth-brand-logo">
                                    <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
                                </div>
                                <h2 class="section-heading text-center">Forgot Password</h2>
                                <p class="text-center">
                                    Enter the phone number you used when registering. Once we verify it, we will ask for the email address to send your 30-minute reset link.
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
                                        <label class="label">Phone Number</label>
                                        <input type="text" name="phone" value="{{ old('phone') }}" required />
                                    </fieldset>
                                </div>
                                <div class="col-12 mt-3">
                                    <button type="submit" class="btn-primary d-block mt-4 btn-signin">VERIFY PHONE</button>
                                    <p class="mt-4 text-center">Remember your password? <a href="{{ route('pages.login') }}" class="text_14 text-primary">Back to Sign In</a></p>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </main>
@endsection
