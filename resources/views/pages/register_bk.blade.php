@extends("layouts.auth_base_layout")


@section("title", "Portal Login")


@section("content")
    <main class="main">
        <div class="page-content mt-6 pb-2 mb-10">
            <div class="container">
                <div class="login-popup">
                    <div class="form-box">
                        <h2 class="text-center">
                            <img src="{{ asset("favicon/geebess.png") }}" height="50" width="50">
                            <span style="font-size: 20px;">
                                GloVans<span class="text-realest">Net</span>
                            </span>
                        </h2>
                        <p class="text-center">
                            Join the <b>GloVans 🐝</b> Family for the realest data packages today !
                        </p>

                        <div class="tab tab-nav-simple tab-nav-boxed form-tab">
                            <div class="tab-content">
                                <div class="tab-pane active" id="signin">
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
                                    <form action="{{ route("pages.processSignUp") }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="singin-email"
                                                   name="name" placeholder="Full Name *"
                                                   required/>
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="number" class="form-control" id="singin-email"
                                                   name="phone" placeholder="Phone *"
                                                   required/>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="singin-password"
                                                   name="password" placeholder="Password *" required/>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="singin-password"
                                                   name="confirm-password" placeholder="Confirm Password *" required/>
                                        </div>
                                        <div class="form-footer">
                                            <a href="#" class="lost-link">Lost your password?</a>
                                        </div>
                                        <button class="btn btn-dark btn-block btn-rounded" type="submit">Register</button>
                                    </form>
                                    <div class="form-choice text-center">
                                        <label class="ls-m">or Already have an account ?</label>
                                        <a href="{{ route("pages.login") }}" class="btn btn-link" type="submit">Sign In
                                        </a>
                                    </div>
                                    <div class="form-choice text-center">
                                        <label class="ls-m">or</label>
                                        <a href="{{ route("pages.home") }}" class="btn btn-link" type="submit">
                                            Go Home
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
