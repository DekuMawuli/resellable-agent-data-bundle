@extends("layouts.page_base")

@section("title", "Home")


@section("content")

    <section class="intro-section">
                <div class="owl-carousel owl-theme row owl-dot-inner owl-dot-white intro-slider animation-slider cols-1 gutter-no"
                    data-owl-options="{
                        'nav': false,
                        'dots': true,
                        'loop': true,
                        'items': 1,
                        'autoplay': true,
                        'autoplayTimeout': 8000
                    }">
                    <div class="banner banner-fixed intro-slide1" style="background-color: #46b2e8;">
                        <figure>
                            <img src="{{ asset('images/mtn.png') }}" alt="intro-banner" width="1903"
                                height="850" style="background-color: #fffb16;" />
                        </figure>
                        <div class="container">
                            <div class="banner-content y-50">
                                <h4 class="banner-subtitle font-weight-bold ls-l">
                                    <span class="d-inline-block slide-animate text-white"
                                        data-animation-options="{'name': 'fadeInRightShorter', 'duration': '1s', 'delay': '.2s'}">
                                        Buy Your MTN </span>

                                </h4>
                                <h2 class="banner-title font-weight-bold text-white lh-1 ls-md slide-animate"
                                    data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                    Unlimited Bundle</h2>
                                <h3 class="font-weight-normal lh-1 ls-l text-white slide-animate"
                                    data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1.2s', 'delay': '1s'}">
                                    Here</h3>
                                <a href="{{ route('pages.login') }}" class="btn btn-dark btn-rounded slide-animate"
                                    data-animation-options="{'name': 'fadeInUpShorter', 'duration': '1s', 'delay': '1.8s'}">
                                    Sign In to Buy<i class="fa fa-cart-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="banner banner-fixed intro-slide2" style="background-color: #dddee0;">
                        <figure>
                            <img src="{{ asset('images/telecel.png') }}" alt="intro-banner" width="1903"
                                height="850" style="background-color: #d8d9d9;" />
                        </figure>
                        <div class="container">
                            <div class="banner-content y-50 ml-auto text-center">
                                <h4 class="banner-subtitle ls-s mb-1 slide-animate"
                                    data-animation-options="{'name': 'fadeInUp', 'duration': '.7s'}"><span
                                        class="d-block text-white text-uppercase mb-2">Using Vodafone ?</span><strong
                                        class="text-white font-weight-semi-bold ls-m">No worries Kraa ?</strong></h4>
                                <h2 class="banner-title text-white mb-2 d-inline-block font-weight-bold  slide-animate"
                                    data-animation-options="{'name': 'fadeInRight', 'duration': '1.2s', 'delay': '.5s'}">
                                    We dey!</h2>
                                <a href="{{ route('pages.login') }}" class="btn btn-dark btn-rounded slide-animate"
                                    data-animation-options="{'name': 'fadeInUp', 'duration': '1s', 'delay': '1.4s'}">
                                    Sign In to Buy<i class="fa fa-cart-plus"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="banner banner-fixed video-banner intro-slide3" style="background-color: #dddee0;">
                        <figure>
                            <img src="{{ asset('images/airtel_tigo.png') }}" alt="intro-banner"
                                width="1903" height="850" style="background-color: #d8d9d9;" />
                        </figure>
                        <div class="container">
                            <div class="banner-content x-50 y-50 text-center">
                                <h4 class="banner-subtitle text-white text-uppercase mb-3 ls-normal slide-animate"
                                    data-animation-options="{'name': 'fadeIn', 'duration': '.7s'}">AirtelTigo
                                </h4>
                                <h2 class="banner-title mb-3 text-white font-weight-bold text-uppercase ls-m slide-animate"
                                    data-animation-options="{'name': 'fadeInUp', 'duration': '.7s', 'delay': '.5s'}">
                                    No Wahala ...</h2>
                                <a href="{{ route('pages.login') }}" class="btn btn-dark btn-rounded slide-animate mb-1"
                                    data-animation-options="{'name': 'fadeInLeft', 'duration': '1s', 'delay': '1.5s'}">
                                    Sign In to Buy<i class="fa fa-cart-plus"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="container mt-6 appear-animate">
                    <div class="service-list">
                        <div class="owl-carousel owl-theme row cols-lg-3 cols-sm-2 cols-1"
                            data-owl-options="{
                                    'items': 3,
                                    'nav': false,
                                    'dots': false,
                                    'loop': true,
                                    'autoplay': false,
                                    'autoplayTimeout': 5000,
                                    'responsive': {
                                        '0': {
                                            'items': 1
                                        },
                                        '576': {
                                            'items': 2
                                        },
                                        '768': {
                                            'items': 3,
                                            'loop': false
                                        }
                                    }
                                }">
                            <div class="icon-box icon-box-side icon-box1 appear-animate"
                                data-animation-options="{
                                        'name': 'fadeInRightShorter',
                                        'delay': '.3s'
                                    }">
                                <i class="icon-box-icon fa fa-fighter-jet"></i>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-capitalize ls-normal lh-1">Fast Delivery &amp;

                                    </h4>
                                    <p class="ls-s lh-1">Data reaches in lightspeed
                                    </p>
                                </div>
                            </div>
                            <div class="icon-box icon-box-side icon-box2 appear-animate"
                                data-animation-options="{
                                        'name': 'fadeInRightShorter',
                                        'delay': '.4s'
                                    }">
                                <i class="icon-box-icon fa fa-question-circle"></i>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-capitalize ls-normal lh-1">24/7 Stand-by
                                    </h4>
                                    <p class="ls-s lh-1">Instant access to perfect support</p>
                                </div>
                            </div>
                            <div class="icon-box icon-box-side icon-box3 appear-animate"
                                data-animation-options="{
                                        'name': 'fadeInRightShorter',
                                        'delay': '.5s'
                                    }">
                                <i class="icon-box-icon fa fa-lock"></i>
                                <div class="icon-box-content">
                                    <h4 class="icon-box-title text-capitalize ls-normal lh-1">100% Trusted
                                    </h4>
                                    <p class="ls-s lh-1">Your data will is delivered. We mean business!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="pt-10 mt-7">
                <h2 class="title title-center mb-5">Recent Orders</h2>
                <div class="container owl-carousel" data-owl-options="{
                        'nav': true,
                        'dots': true,
                        'loop': true,
                        'items': 3,
                        'autoplay': true,
                        'autoplayTimeout': 5000
                    }">
                    @foreach($recentOrders as $order)
                            <div class="col-12 mr-2">
                                <div class="card " style="border: solid 1px #efefef; padding: 1lvw; border-radius: 2lvh" >
                                    <div class="card-body">
                                        <h5 class="card-title"> 🐝 Alert 🐝</h5>
                                        <p class="card-text">
                                            <b>{{ $order->product->category->name }} {{ $order->product->name }}</b>
                                            <br>
                                            <em>Just {{ $order->updated_at->diffForHumans() }}</em>
                                        </p>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                </div>
            </section>
            <section class="product-wrapper mt-6 mt-md-4 pt-4 mb-10 pb-2 container appear-animate">
                <h2 class="title title-center">Our Data Bundles</h2>
                <div class="row">
                    @foreach($allProducts as $product)
                        <div class="col-6 col-md-2 mb-3">
                            <div class="card" style="border: solid 1px #efefef; padding: 1lvw; border-radius: 2lvh">
                                <div class="card-body">
                                    @if(in_array(strtoupper($product->category->name), ["MTN", "YELLO"]))
                                        <div class="mtn-card" style="height: 16lvh; padding-top: 5vh;">
                                            <h2 class="text-white">MTN</h2>
                                        </div>
                                    @elseif(in_array(strtoupper($product->category->name), ["VODAFONE", "TELECEL"]))
                                        <div class="vodafone-card" style="height: 16lvh; padding-top: 5vh;">
                                            <h3 class="text-white">Telecel</h3>
                                        </div>
                                    @elseif(in_array(strtoupper($product->category->name), ["AT_PREMIUM", "AIRTELTIGO", "AT_BIGTIME"]))
                                        <div class="airtel-tigo" style="height: 16lvh; padding-top: 5vh;">
                                            <h2 class="text-white">A. TIGO</h2>
                                        </div>
                                    @endif
                                    <p class="card-title text-center" style="font-size: 16px">

                                        {{ $product->name }}GB @ <b>{{ $product->agent_price }}</b>
                                    </p>
                                    @if(!$product->out_to_stock)
                                        <a href="{{ route("agent.products") }}" class="btn btn-dark btn-block">
                                            <i class="lnr lnr-cart"></i> Buy Now
                                        </a>
                                    @else
                                        <button class="btn text-danger btn-block"><b>Out of Stock</b></button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                        <a href="{{ route("pages.products") }}?view=all" class="text-center mt-4">View All >>></a>
                </div>

            </section>
@endsection
