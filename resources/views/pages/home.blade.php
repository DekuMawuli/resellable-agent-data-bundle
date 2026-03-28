@extends("layouts.page_base")

@section("title", "Home")

@section("content")
<main id="MainContent" class="content-for-layout">
        <style>
          .hero-enhanced .slide-heading {
            font-size: clamp(2.4rem, 6vw, 5rem) !important;
            line-height: 1.05;
          }

          .hero-enhanced .slide-subheading {
            font-size: clamp(1.15rem, 2.3vw, 1.8rem) !important;
            font-weight: 600;
          }

          .hero-enhanced .slide-btn {
            font-size: 1.05rem;
            font-weight: 700;
            padding: 14px 28px;
            border-radius: 12px;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.25);
          }

          .hero-enhanced .slide-content {
            max-width: 620px;
          }

          .hero-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(8, 15, 25, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 999px;
            padding: 6px 12px;
            margin-bottom: 14px;
          }

          .hero-brand-badge img {
            height: 20px;
            width: auto;
          }

          .section-brand {
            height: 28px;
            width: auto;
            margin-bottom: 12px;
          }

          .recent-orders-marquee {
            overflow: hidden;
            padding: 10px 0;
          }

          .recent-orders-track {
            display: flex;
            align-items: stretch;
            gap: 18px;
            width: max-content;
            flex-wrap: nowrap;
            animation: recentOrdersTicker 34s linear infinite;
            will-change: transform;
          }

          .recent-orders-marquee:hover .recent-orders-track {
            animation-play-state: paused;
          }

          .recent-orders-slide {
            flex: 0 0 310px;
            width: 310px;
          }

          .recent-orders-card {
            min-height: 100%;
            background: #fff;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 18px;
            box-shadow: 12px 0 20px -18px rgba(15, 23, 42, 0.18), -12px 0 20px -18px rgba(15, 23, 42, 0.18);
            padding: 14px;
          }

          .recent-orders-card .public-product-body {
            padding: 0;
          }

          .recent-orders-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 0.76rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #0f376d;
            margin-bottom: 10px;
          }

          .recent-orders-kicker__dot {
            width: 9px;
            height: 9px;
            border-radius: 999px;
            background: #10b981;
            box-shadow: 0 0 0 6px rgba(16, 185, 129, 0.12);
            animation: recentOrdersPulse 1.8s ease-in-out infinite;
          }

          .recent-orders-card .public-network-chip {
            margin-bottom: 16px;
          }

          .recent-orders-time {
            margin-top: 8px;
            display: block;
            font-size: 0.92rem;
            color: #6b7a90;
          }

          @keyframes recentOrdersTicker {
            from {
              transform: translate3d(0, 0, 0);
            }
            to {
              transform: translate3d(calc(-50% - 9px), 0, 0);
            }
          }

          @keyframes recentOrdersPulse {
            0%, 100% {
              transform: scale(1);
              opacity: 1;
            }
            50% {
              transform: scale(0.72);
              opacity: 0.45;
            }
          }

          @media (max-width: 991.98px) {
            .recent-orders-track {
              animation-duration: 28s;
            }

            .recent-orders-slide {
              flex-basis: 280px;
              width: 280px;
            }
          }

          @media (max-width: 575.98px) {
            .recent-orders-track {
              gap: 14px;
              animation-duration: 24s;
            }

            .recent-orders-slide {
              flex-basis: 250px;
              width: 250px;
            }
          }
        </style>
        @include("partials.network_product_cards_styles")
        <!-- slideshow start -->
        <div class="slideshow-section position-relative hero-enhanced">
          <div
            class="slideshow-active activate-slider"
            data-slick='{
                    "slidesToShow": 1,
                    "slidesToScroll": 1,
                    "dots": true,
                    "arrows": false,
                    "autoplay": true,
                    "autoplaySpeed": 4000,
                    "responsive": [
                        {
                        "breakpoint": 768,
                        "settings": {
                            "arrows": false
                        }
                        }
                    ]
                }'
          >
            <!-- Slide 1: MTN -->
            <div class="slide-item slide-item-bag position-relative">
              <img
                class="slide-img"
                src="{{ asset('images/mtn.png') }}"
                alt="MTN Data Bundles"
                style="width:100%; height:74lvh; object-fit:cover; object-position:center;"
              />
              <div class="content-absolute content-slide">
                <div
                  class="container height-inherit d-flex align-items-center justify-content-end"
                >
                  <div class="content-box slide-content slide-content-1 py-4">
                    <span class="hero-brand-badge">
                      <img src="{{ asset('admin_assets/images/glovans-logo.svg') }}" alt="GloVans logo">
                    </span>
                    <h2
                      class="slide-heading heading_72 text-white animate__animated animate__fadeInUp"
                      data-animation="animate__animated animate__fadeInUp"
                    >
                      Buy Your MTN
                    </h2>
                    <p
                      class="slide-subheading heading_24 text-white animate__animated animate__fadeInUp"
                      data-animation="animate__animated animate__fadeInUp"
                    >
                      Unlimited Bundle Here
                    </p>
                    <a
                      class="btn-primary slide-btn animate__animated animate__fadeInUp"
                      href="{{ route('pages.login') }}"
                      data-animation="animate__animated animate__fadeInUp"
                      >Sign In to Buy</a
                    >
                  </div>
                </div>
              </div>
            </div>

            <!-- Slide 2: Telecel/Vodafone -->
            <div class="slide-item slide-item-bag position-relative">
              <img
                class="slide-img"
                src="{{ asset('images/telecel.png') }}"
                alt="Telecel Data Bundles"
                style="width:100%; height:74lvh; object-fit:cover; object-position:center;"
              />
              <div class="content-absolute content-slide">
                <div
                  class="container height-inherit d-flex align-items-center justify-content-end"
                >
                  <div
                    class="content-box slide-content slide-content-1 py-4 text-center"
                  >
                    <span class="hero-brand-badge">
                      <img src="{{ asset('admin_assets/images/glovans-logo.svg') }}" alt="GloVans logo">
                    </span>
                    <h2
                      class="slide-heading heading_72 text-white animate__animated animate__fadeInUp"
                      data-animation="animate__animated animate__fadeInUp"
                    >
                      Using Vodafone?
                    </h2>
                    <p
                      class="slide-subheading heading_24 text-white animate__animated animate__fadeInUp"
                      data-animation="animate__animated animate__fadeInUp"
                    >
                      No worries Kraa? We dey!
                    </p>
                    <a
                      class="btn-primary slide-btn animate__animated animate__fadeInUp"
                      href="{{ route('pages.login') }}"
                      data-animation="animate__animated animate__fadeInUp"
                      >Sign In to Buy</a
                    >
                  </div>
                </div>
              </div>
            </div>

            <!-- Slide 3: AirtelTigo -->
            <div class="slide-item slide-item-bag position-relative">
              <img
                class="slide-img"
                src="{{ asset('images/airtel_tigo.png') }}"
                alt="AirtelTigo Data Bundles"
                style="width:100%; height:74lvh; object-fit:cover; object-position:center;"
              />
              <div class="content-absolute content-slide">
                <div
                  class="container height-inherit d-flex align-items-center justify-content-center"
                >
                  <div
                    class="content-box slide-content slide-content-1 py-4 text-center"
                  >
                    <span class="hero-brand-badge">
                      <img src="{{ asset('admin_assets/images/glovans-logo.svg') }}" alt="GloVans logo">
                    </span>
                    <h2
                      class="slide-heading heading_72 text-white animate__animated animate__fadeInUp"
                      data-animation="animate__animated animate__fadeInUp"
                    >
                      AirtelTigo
                    </h2>
                    <p
                      class="slide-subheading heading_24 text-white animate__animated animate__fadeInUp"
                      data-animation="animate__animated animate__fadeInUp"
                    >
                      No Wahala ...
                    </p>
                    <a
                      class="btn-primary slide-btn animate__animated animate__fadeInUp"
                      href="{{ route('pages.login') }}"
                      data-animation="animate__animated animate__fadeInUp"
                      >Sign In to Buy</a
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="activate-arrows"></div>
          <div class="activate-dots dot-tools"></div>
        </div>
        <!-- slideshow end -->

        <!-- trusted badge start -->
        <div class="trusted-section mt-100 overflow-hidden">
          <div class="trusted-section-inner">
            <div class="container">
              <div class="row justify-content-center trusted-row">
                <div class="col-lg-4 col-md-6 col-12">
                  <div class="trusted-badge rounded p-0">
                    <div class="trusted-icon">
                      <i class="fa fa-fighter-jet fa-2x"></i>
                    </div>
                    <div class="trusted-content">
                      <h2 class="heading_18 trusted-heading">
                        Fast Delivery
                      </h2>
                      <p class="text_16 trusted-subheading trusted-subheading-2">
                        Data reaches in lightspeed
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                  <div class="trusted-badge rounded p-0">
                    <div class="trusted-icon">
                      <i class="fa fa-question-circle fa-2x"></i>
                    </div>
                    <div class="trusted-content">
                      <h2 class="heading_18 trusted-heading">
                        24/7 Stand-by
                      </h2>
                      <p class="text_16 trusted-subheading trusted-subheading-2">
                        Instant access to perfect support
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-6 col-12">
                  <div class="trusted-badge rounded p-0">
                    <div class="trusted-icon">
                      <i class="fa fa-lock fa-2x"></i>
                    </div>
                    <div class="trusted-content">
                      <h2 class="heading_18 trusted-heading">
                        100% Trusted
                      </h2>
                      <p class="text_16 trusted-subheading trusted-subheading-2">
                        Your data is delivered. We mean business!
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- trusted badge end -->

        <!-- recent orders start -->
        <div class="grid-banner mt-100 overflow-hidden">
          <div class="collection-tab-inner mt-0">
            <div class="container">
              <div class="section-header text-center mb-5">
                <span class="section-heading primary-color">Recent Orders</span>
              </div>
              @if($recentOrders->isNotEmpty())
                @php
                  $recentOrdersLoop = $recentOrders->concat($recentOrders);
                @endphp
                <div class="recent-orders-marquee" aria-label="Live recent orders feed">
                  <div class="recent-orders-track">
                    @foreach($recentOrdersLoop as $order)
                      @php
                        $recentNetwork = strtoupper($order->product->category->name ?? "NETWORK");
                        $recentNetworkClass = "is-default";
                        if (in_array($recentNetwork, ["MTN", "YELLO"])) {
                            $recentNetworkClass = "is-mtn";
                        } elseif (in_array($recentNetwork, ["VODAFONE", "TELECEL"])) {
                            $recentNetworkClass = "is-telecel";
                        } elseif (in_array($recentNetwork, ["AT_PREMIUM", "AIRTELTIGO", "AT_BIGTIME"])) {
                            $recentNetworkClass = "is-at";
                        }
                      @endphp
                      <article class="recent-orders-slide" @if($loop->index >= $recentOrders->count()) aria-hidden="true" @endif>
                        <div class="recent-orders-card">
                          <div class="recent-orders-kicker">
                            <span class="recent-orders-kicker__dot"></span>
                            Live order feed
                          </div>
                          <div class="public-network-chip {{ $recentNetworkClass }}">{{ $recentNetwork }}</div>
                          <div class="public-product-body">
                            <h5 class="public-product-title mb-1">{{ $order->product->name }} GB delivered</h5>
                            <p class="public-product-subtitle mb-0">
                              Order ref: {{ strtoupper(\Illuminate\Support\Str::limit((string) $order->code, 10, '')) }}
                            </p>
                            <span class="recent-orders-time">
                              Completed {{ $order->updated_at?->diffForHumans() }}
                            </span>
                          </div>
                        </div>
                      </article>
                    @endforeach
                  </div>
                </div>
              @else
                <div class="text-center text-muted">
                  No completed orders yet.
                </div>
              @endif
            </div>
          </div>
        </div>
        <!-- recent orders end -->

        <!-- data bundles start -->
        <div class="featured-collection mt-100 overflow-hidden">
          <div class="collection-tab-inner">
            <div class="container">
              <div class="section-header text-center">
                <span class="section-heading primary-color">Our Data Bundles</span>
              </div>
              <div class="row">
                @foreach($allProducts as $product)
                  @php
                    $networkName = strtoupper($product->category->name ?? "NETWORK");
                    $networkClass = "is-default";
                    if (in_array($networkName, ["MTN", "YELLO"])) {
                        $networkClass = "is-mtn";
                    } elseif (in_array($networkName, ["VODAFONE", "TELECEL"])) {
                        $networkClass = "is-telecel";
                    } elseif (in_array($networkName, ["AT_PREMIUM", "AIRTELTIGO", "AT_BIGTIME"])) {
                        $networkClass = "is-at";
                    }
                    $buyLink = auth()->check() ? route("agent.products") : route("pages.login");
                  @endphp
                  <div
                    class="col-lg-3 col-md-6 col-12 mb-4"
                    data-aos="fade-up"
                    data-aos-duration="700"
                  >
                    <div class="public-product-card">
                      <div class="public-network-chip {{ $networkClass }}">{{ $networkName }}</div>
                      <div class="public-product-body">
                        <h3 class="public-product-title">{{ $product->name }} GB</h3>
                        <p class="public-product-subtitle">{{ $product->category->name }} Bundle</p>

                        <div class="public-product-footer">
                          <div>
                            <span class="public-price-label">Price</span>
                            <h4 class="public-price-value">GHS {{ number_format((float) $product->agent_price, 2) }}</h4>
                          </div>
                          @if(!$product->out_to_stock)
                            <a href="{{ $buyLink }}" class="btn-primary slide-btn public-buy-btn">Buy Now</a>
                          @else
                            <span class="public-stock-badge">Out of Stock</span>
                          @endif
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
              <div class="text-center mt-4">
                <a href="{{ route("pages.products") }}?view=all" class="btn-primary slide-btn">View All &raquo;</a>
              </div>
            </div>
          </div>
        </div>
        <!-- data bundles end -->
      </main>
@endsection
