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
                            Welcome back, <b>Gee!</b>, Sign in to access your portal
                        </p>
                        <div class="tab tab-nav-simple tab-nav-boxed form-tab">
                            <div class="tab-content">
                                <div class="tab-pane active" id="signin">
                                    @include("partials.auth_alert_incs")
                                    <form action="{{ route("pages.processLogin") }}" method="POST">
                                        @csrf
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control" id="singin-email"
                                                   name="phone" placeholder="Phone Number *"
                                                   required/>
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control" id="singin-password"
                                                   name="password" placeholder="Password *" required/>
                                        </div>
                                        <div class="form-footer">
                                            <a href="#" class="lost-link">Lost your password?</a>
                                        </div>
                                        <button class="btn btn-dark btn-block btn-rounded" type="submit">Login</button>
                                    </form>
                                    <div class="form-choice text-center">
                                        <label class="ls-m">or Want to be an agent ?</label>
                                        <a href="{{ route("pages.register") }}" class="btn btn-link">Register Now
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



