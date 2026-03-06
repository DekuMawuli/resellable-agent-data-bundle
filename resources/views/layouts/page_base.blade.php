<!DOCTYPE html>
<html lang="en" class="no-js">
  <head>
    <title>@yield('title', 'GloVans') &mdash; GloVans</title>
    <!-- meta tags -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="meta description" />
    <link
      rel="shortcut icon"
      href="assets/img/favicon.png"
      type="image/x-icon"
    />
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
      rel="stylesheet"
    />
    <!-- all css -->
    <style>
      :root {
        --primary-color: #00234d;
        --secondary-color: #f76b6a;

        --btn-primary-border-radius: 0.25rem;
        --btn-primary-color: #fff;
        --btn-primary-background-color: #00234d;
        --btn-primary-border-color: #00234d;
        --btn-primary-hover-color: #fff;
        --btn-primary-background-hover-color: #00234d;
        --btn-primary-border-hover-color: #00234d;
        --btn-primary-font-weight: 500;

        --btn-secondary-border-radius: 0.25rem;
        --btn-secondary-color: #00234d;
        --btn-secondary-background-color: transparent;
        --btn-secondary-border-color: #00234d;
        --btn-secondary-hover-color: #fff;
        --btn-secondary-background-hover-color: #00234d;
        --btn-secondary-border-hover-color: #00234d;
        --btn-secondary-font-weight: 500;

        --heading-color: #000;
        --heading-font-family: "Poppins", sans-serif;
        --heading-font-weight: 700;

        --title-color: #000;
        --title-font-family: "Poppins", sans-serif;
        --title-font-weight: 400;

        --body-color: #000;
        --body-background-color: #fff;
        --body-font-family: "Poppins", sans-serif;
        --body-font-size: 14px;
        --body-font-weight: 400;

        --section-heading-color: #000;
        --section-heading-font-family: "Poppins", sans-serif;
        --section-heading-font-size: 48px;
        --section-heading-font-weight: 600;

        --section-subheading-color: #000;
        --section-subheading-font-family: "Poppins", sans-serif;
        --section-subheading-font-size: 16px;
        --section-subheading-font-weight: 400;
      }
    </style>

    <link rel="stylesheet" href="assets/css/vendor.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
      .logo-main.logo-with-svg {
        display: inline-flex;
        align-items: center;
      }

      .logo-main.logo-with-svg img {
        height: 30px;
        width: auto;
      }
    </style>
    @livewireStyles
  </head>

  <body>
    <div class="body-wrapper">
      {{-- <!-- announcement bar start -->
      <div class="announcement-bar bg-1 py-1 py-lg-2">
        <div class="container">
          <div class="row align-items-center justify-content-between">
            <div class="col-lg-3 d-lg-block d-none">
              <div class="announcement-call-wrapper">
                <div class="announcement-call">
                  <a class="announcement-text text-white" href="tel:+1-078-2376"
                    >Call: +1 078 2376</a
                  >
                </div>
              </div>
            </div>
            <div class="col-lg-6 col-12">
              <div
                class="announcement-text-wrapper d-flex align-items-center justify-content-center"
              >
                <p class="announcement-text text-white">
                  New year sale - 30% off
                </p>
              </div>
            </div>
            <div class="col-lg-3 d-lg-block d-none">
              <div
                class="announcement-meta-wrapper d-flex align-items-center justify-content-end"
              >
                <div class="announcement-meta d-flex align-items-center">
                  <a
                    class="announcement-login announcement-text text-white"
                    href="login.html"
                  >
                    <svg
                      class="icon icon-user"
                      width="10"
                      height="11"
                      viewBox="0 0 10 11"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        d="M5 0C3.07227 0 1.5 1.57227 1.5 3.5C1.5 4.70508 2.11523 5.77539 3.04688 6.40625C1.26367 7.17188 0 8.94141 0 11H1C1 8.78516 2.78516 7 5 7C7.21484 7 9 8.78516 9 11H10C10 8.94141 8.73633 7.17188 6.95312 6.40625C7.88477 5.77539 8.5 4.70508 8.5 3.5C8.5 1.57227 6.92773 0 5 0ZM5 1C6.38672 1 7.5 2.11328 7.5 3.5C7.5 4.88672 6.38672 6 5 6C3.61328 6 2.5 4.88672 2.5 3.5C2.5 2.11328 3.61328 1 5 1Z"
                        fill="#fff"
                      />
                    </svg>
                    <span>Login</span>
                  </a>
                  <span class="separator-login d-flex px-3">
                    <svg
                      width="2"
                      height="9"
                      viewBox="0 0 2 9"
                      fill="none"
                      xmlns="http://www.w3.org/2000/svg"
                    >
                      <path
                        opacity="0.4"
                        d="M1 0.5V8.5"
                        stroke="#FEFEFE"
                        stroke-linecap="round"
                      />
                    </svg>
                  </span>
                  <div class="currency-wrapper">
                    <button
                      type="button"
                      class="currency-btn btn-reset text-white"
                      data-bs-toggle="dropdown"
                      aria-expanded="false"
                    >
                      <img
                        class="flag"
                        src="assets/img/flag/usd.jpg"
                        alt="img"
                      />
                      <span>USD</span>
                      <span>
                        <svg
                          class="icon icon-dropdown"
                          xmlns="http://www.w3.org/2000/svg"
                          width="24"
                          height="24"
                          viewBox="0 0 24 24"
                          fill="none"
                          stroke="#fff"
                          stroke-width="1"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                        >
                          <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                      </span>
                    </button>

                    <ul
                      class="currency-list dropdown-menu dropdown-menu-end px-2"
                    >
                      <li class="currency-list-item">
                        <a
                          class="currency-list-option"
                          href="index.html#"
                          data-value="USD"
                        >
                          <img
                            class="flag"
                            src="assets/img/flag/usd.jpg"
                            alt="img"
                          />
                          <span>USD</span>
                        </a>
                      </li>
                      <li class="currency-list-item">
                        <a
                          class="currency-list-option"
                          href="index.html#"
                          data-value="CAD"
                        >
                          <img
                            class="flag"
                            src="assets/img/flag/cad.jpg"
                            alt="img"
                          />
                          <span>CAD</span>
                        </a>
                      </li>
                      <li class="currency-list-item">
                        <a
                          class="currency-list-option"
                          href="index.html#"
                          data-value="EUR"
                        >
                          <img
                            class="flag"
                            src="assets/img/flag/eur.jpg"
                            alt="img"
                          />
                          <span>EUR</span>
                        </a>
                      </li>
                      <li class="currency-list-item">
                        <a
                          class="currency-list-option"
                          href="index.html#"
                          data-value="JPY"
                        >
                          <img
                            class="flag"
                            src="assets/img/flag/jpy.jpg"
                            alt="img"
                          />
                          <span>JPY</span>
                        </a>
                      </li>
                      <li class="currency-list-item">
                        <a
                          class="currency-list-option"
                          href="index.html#"
                          data-value="GBP"
                        >
                          <img
                            class="flag"
                            src="assets/img/flag/gbp.jpg"
                            alt="img"
                          />
                          <span>GBP</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- announcement bar end --> --}}

      <!-- header start -->
      <header class="sticky-header border-btm-black header-1">
        <div class="header-bottom">
          <div class="container">
            <div class="row align-items-center">
              <div class="col-lg-3 col-md-4 col-4">
                <div class="header-logo">
                  <a href="{{ route('pages.home') }}" class="logo-main logo-with-svg" aria-label="GloVans">
                    <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
                  </a>
                </div>
              </div>
              <div class="col-lg-6 d-lg-block d-none">
                <nav class="site-navigation">
                  <ul class="main-menu list-unstyled justify-content-center">
                    <li class="menu-list-item nav-item {{ request()->routeIs('pages.home') ? 'active' : '' }}">
                      <a class="nav-link" href="{{ route('pages.home') }}">Home</a>
                    </li>
                    <li class="menu-list-item nav-item {{ request()->routeIs('pages.products') ? 'active' : '' }}">
                      <a class="nav-link" href="{{ route('pages.products') }}">Data Bundles</a>
                    </li>
                  </ul>
                </nav>
              </div>
              <div class="col-lg-3 col-md-8 col-8">
                <div class="header-action d-flex align-items-center justify-content-end gap-3">
                  @auth
                    <a class="btn-primary slide-btn d-none d-lg-inline-block" href="{{ route('agent.dashboard') }}">My Dashboard</a>
                    <a class="nav-link d-none d-lg-inline-block" href="{{ route('pages.logout') }}">Sign Out</a>
                  @else
                    <a class="nav-link d-none d-lg-inline-block" href="{{ route('pages.login') }}">Sign In</a>
                    <a class="nav-link d-none text-dark d-lg-inline-block" href="{{ route('pages.register') }}">Register</a>
                  @endauth
                  <a class="header-action-item header-hamburger ms-2 d-lg-none"
                     href="#drawer-menu"
                     data-bs-toggle="offcanvas">
                    <svg class="icon icon-hamburger" xmlns="http://www.w3.org/2000/svg"
                         width="24" height="24" viewBox="0 0 24 24" fill="none"
                         stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <line x1="3" y1="12" x2="21" y2="12"></line>
                      <line x1="3" y1="6" x2="21" y2="6"></line>
                      <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <!-- header end -->

      @yield("content")

     @include("partials.footer_inc")
     @include("partials.public_contact_widget")

      <!-- scrollup start -->
      <button id="scrollup">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
             viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2"
             stroke-linecap="round" stroke-linejoin="round">
          <polyline points="18 15 12 9 6 15"></polyline>
        </svg>
      </button>
      <!-- scrollup end -->

      <!-- drawer menu start -->
      <div class="offcanvas offcanvas-start d-flex d-lg-none" tabindex="-1" id="drawer-menu">
        <div class="offcanvas-wrapper">
          <div class="offcanvas-header border-btm-black">
            <h5 class="drawer-heading">Menu</h5>
            <button type="button" class="btn-close text-reset"
                    data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
            <nav class="site-navigation">
              <ul class="main-menu list-unstyled">
                <li class="menu-list-item nav-item {{ request()->routeIs('pages.home') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('pages.home') }}">Home</a>
                </li>
                <li class="menu-list-item nav-item {{ request()->routeIs('pages.products') ? 'active' : '' }}">
                  <a class="nav-link" href="{{ route('pages.products') }}">Data Bundles</a>
                </li>
              </ul>
            </nav>
            <ul class="utility-menu list-unstyled">
              @auth
                <li class="utilty-menu-item">
                  <a class="nav-link" href="{{ route('agent.dashboard') }}">My Dashboard</a>
                </li>
                <li class="utilty-menu-item">
                  <a class="nav-link" href="{{ route('pages.logout') }}">Sign Out</a>
                </li>
              @else
                <li class="utilty-menu-item">
                  <a class="nav-link" href="{{ route('pages.login') }}">Sign In</a>
                </li>
                <li class="utilty-menu-item">
                  <a class="btn-primary slide-btn d-block text-center" href="{{ route('pages.register') }}">Register</a>
                </li>
              @endauth
            </ul>
          </div>
        </div>
      </div>
      <!-- drawer menu end -->

      <!-- all js -->
      <script src="{{ asset('assets/js/vendor.js') }}"></script>
      <script src="{{ asset('assets/js/main.js') }}"></script>
      @livewireScripts
    </div>
  </body>
</html>
