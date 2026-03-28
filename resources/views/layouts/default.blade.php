
<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="light" data-topbar-color="dark">

<head>
    <meta charset="utf-8" />
    <title>GloVans | @yield("title")</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="Myra Studio" name="author" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('admin_assets/images/favicon.ico') }}">

    <link href="{{ asset('admin_assets/libs/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('admin_assets/css/style.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin_assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin_assets/css/custom-admin.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    @livewireStyles
    @stack("styles")
    <script src="{{ asset('admin_assets/js/config.js') }}"></script>
</head>

<body>

    <!-- Begin page -->
    <div class="layout-wrapper">

        <!-- ========== Left Sidebar ========== -->
        @php
            $user = auth()->user();
            $showAgentSidebar = request()->routeIs("agent.*") || ($user && $user->role === "agent");
            $showAdminPaystackBadge = $user && request()->routeIs("root.*");
            $adminPaystackLive = $showAdminPaystackBadge
                ? (bool) \App\Models\Setting::query()->value("use_live_payment")
                : false;
        @endphp
        @include($showAgentSidebar ? "partials.agent.agent_sidebar_inc" : "partials.admin.admin_sidebar_inc")


        <div class="page-content">
            <div class="navbar-custom">
                <div class="topbar topbar-with-paystack-badge d-flex align-items-center position-relative">
                    <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1 flex-shrink-0">
                        <button type="button" class="button-toggle-menu waves-effect waves-light rounded-circle" aria-label="Toggle sidebar">
                            <i class="mdi mdi-menu"></i>
                        </button>
                    </div>

                    @if ($showAdminPaystackBadge)
                        <div class="topbar-paystack-mode position-absolute top-50 start-50 translate-middle px-2 d-flex align-items-center gap-2 gap-sm-3 text-start">
                            @if ($adminPaystackLive)
                                <span class="badge paystack-mode-badge paystack-mode-badge--live rounded-pill px-2 px-sm-3 py-1 py-sm-2 fw-bold text-uppercase flex-shrink-0 align-self-center">
                                    Live payments
                                </span>
                                <div class="topbar-paystack-mode__text d-flex flex-column justify-content-center gap-0 lh-sm min-w-0">
                                    <span class="d-none d-md-block small text-white-50 text-nowrap">Real Paystack keys — real money.</span>
                                    <a href="{{ route("root.settings") }}" class="topbar-paystack-mode__link small text-nowrap">Change in Settings</a>
                                </div>
                            @else
                                <span class="badge paystack-mode-badge paystack-mode-badge--test rounded-pill px-2 px-sm-3 py-1 py-sm-2 fw-bold text-uppercase flex-shrink-0 align-self-center">
                                    Test payments
                                </span>
                                <div class="topbar-paystack-mode__text d-flex flex-column justify-content-center gap-0 lh-sm min-w-0">
                                    <span class="d-none d-md-block small text-white-50 text-nowrap">Test keys — no real charges.</span>
                                    <a href="{{ route("root.settings") }}" class="topbar-paystack-mode__link small text-nowrap">Change in Settings</a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <ul class="topbar-menu d-flex align-items-center gap-2 ms-auto flex-shrink-0">
                        <li class="d-none d-md-inline-block">
                            <a class="nav-link waves-effect waves-light" href="#" data-bs-toggle="fullscreen">
                                <i class="mdi mdi-fullscreen font-size-24"></i>
                            </a>
                        </li>

                        <li class="dropdown">
                            <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown"
                               href="#" role="button" aria-haspopup="false" aria-expanded="false">
                                <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light text-dark" style="width:36px; height:36px;">
                                    <i class="fa fa-user"></i>
                                </span>
                                <span class="ms-1 d-none d-md-inline-block">
                                    {{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i>
                                </span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                <div class="dropdown-header noti-title">
                                    <h6 class="text-overflow m-0">Welcome!</h6>
                                </div>
                                <a class="dropdown-item notify-item" href="{{ route('pages.profile') }}">
                                    <i data-lucide="user" class="font-size-16 me-2"></i>
                                    <span>My Account</span>
                                </a>
                                <a class="dropdown-item notify-item" href="{{ route('pages.logout') }}">
                                    <i data-lucide="log-out" class="font-size-16 me-2"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="px-3 px-md-4 py-3 app-content-shell">
                @yield("content")
            </div>

            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <div><script>document.write(new Date().getFullYear())</script> © GloVans</div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>

    </div>
    <!-- END wrapper -->

    <!-- App js -->
    <script src="{{ asset('admin_assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/app.js') }}"></script>

    <!-- Jquery Sparkline Chart  -->
    <script src="{{ asset('admin_assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

    <!-- Jquery-knob Chart Js-->
    <script src="{{ asset('admin_assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>


    <!-- Morris Chart Js-->
    <script src="{{ asset('admin_assets/libs/morris.js/morris.min.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/raphael/raphael.min.js') }}"></script>
    <script src="{{ asset('admin_assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- Dashboard init-->
    <script src="{{ asset('admin_assets/js/pages/dashboard.js') }}"></script>
    @livewireScripts
    @stack("scripts")

</body>

</html>
