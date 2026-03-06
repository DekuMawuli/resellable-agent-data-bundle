@extends("layouts.page_base")

@section("title", "All Products")


@section("content")
    @include("partials.network_product_cards_styles")
    <style>
        .hero-enhanced .slide-heading {
            font-size: clamp(2.2rem, 5.2vw, 4.4rem) !important;
            line-height: 1.05;
        }

        .hero-enhanced .slide-subheading {
            font-size: clamp(1.05rem, 2.1vw, 1.5rem) !important;
            font-weight: 600;
        }

        .hero-enhanced .slide-btn {
            font-size: 1rem;
            font-weight: 700;
            padding: 12px 24px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.22);
        }

        .hero-brand-badge {
            display: inline-flex;
            align-items: center;
            background: rgba(8, 15, 25, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.32);
            border-radius: 999px;
            padding: 6px 12px;
            margin-bottom: 12px;
        }

        .hero-brand-badge img {
            height: 20px;
            width: auto;
        }

        .products-header-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 18px;
        }

        .products-header-brand img {
            height: 28px;
            width: auto;
            margin-bottom: 8px;
        }
    </style>

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
            <div class="slide-item slide-item-bag position-relative">
                <img
                    class="slide-img"
                    src="{{ asset('images/mtn.png') }}"
                    alt="MTN Data Bundles"
                    style="width:100%; height:52lvh; object-fit:cover; object-position:center;"
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
                            >Sign In to Buy</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-item slide-item-bag position-relative">
                <img
                    class="slide-img"
                    src="{{ asset('images/telecel.png') }}"
                    alt="Telecel Data Bundles"
                    style="width:100%; height:52lvh; object-fit:cover; object-position:center;"
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
                            >Sign In to Buy</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="slide-item slide-item-bag position-relative">
                <img
                    class="slide-img"
                    src="{{ asset('images/airtel_tigo.png') }}"
                    alt="AirtelTigo Data Bundles"
                    style="width:100%; height:52lvh; object-fit:cover; object-position:center;"
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
                            >Sign In to Buy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="activate-arrows"></div>
        <div class="activate-dots dot-tools"></div>
    </div>
    <section class="product-wrapper mt-6 mt-md-4 pt-4 mb-10 pb-2 container appear-animate">
        <div class="products-header-brand">
            <img src="{{ asset('admin_assets/images/glovans-logo-dark.svg') }}" alt="GloVans logo">
            <h3 class="mb-0">Available Data Bundles</h3>
        </div>
        @livewire("public-products-list-component", ["initialCategoryCode" => request()->query("view", "all")])
    </section>
@endsection
