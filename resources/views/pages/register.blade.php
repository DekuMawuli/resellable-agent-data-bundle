@extends("layouts.page_base")


@section("title", "Register New Account")

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
                    <form action="{{ route("pages.processSignUp") }}" method="POST" class="login-form common-form mx-auto">
                        @csrf
                        <div class="section-header mb-3">
                            <div class="auth-brand-logo">
                                <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
                            </div>
                            <h2 class="section-heading text-center">Register</h2>
                            <p class="text-center">
                                Join the Family for the realest data packages today !
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
                                    <label class="label">Full Name</label>
                                    <input type="text" name="name" required/>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">Phone</label>
                                    <input type="text"  name="phone" required/>
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">Password</label>
                                    <input type="password" name="password" required />
                                </fieldset>
                            </div>
                            <div class="col-12">
                                <fieldset>
                                    <label class="label">Confirm Password</label>
                                    <input type="password"  name="confirm-password" required/>
                                </fieldset>
                            </div>
                            <div class="col-12 mt-3">
                                <button type="submit" class="btn-secondary mt-2 btn-signin">CREATE AN ACCOUNT</button>
                                <p class="mt-5 text-center">Already Have a Account? <a href="{{ route("pages.login") }}" class="text_14 text-primary">Sign In</a></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
@endsection
