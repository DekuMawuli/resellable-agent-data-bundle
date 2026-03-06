@extends("layouts.page_base")


@section("title", "Sign In To Your Account")

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
                    <form action="{{ route("pages.processLogin") }}" method="POST" class="login-form common-form mx-auto">
                        @csrf
                        <div class="section-header mb-3">
                            <div class="auth-brand-logo">
                                <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
                            </div>
                            <h2 class="section-heading text-center">Sign In</h2>
                            <p class="text-center">
                            Welcome back, Sign in to access your portal
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
                                    <input type="text" name="phone" required/>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">Password</label>
                                    <input type="password" name="password"  required>
                                </fieldset>
                            </div>
                            <div class="col-12 mt-3">
                                <a href="login.html#" class="text_14 d-block">Forgot your password?</a>
                                <button type="submit" class="btn-primary d-block mt-4 btn-signin">SIGN IN</button>
                                <p class="mt-5 text-center">Need an Account? <a href="{{ route("pages.register") }}" class="text_14 text-primary">Register Here</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
@endsection
