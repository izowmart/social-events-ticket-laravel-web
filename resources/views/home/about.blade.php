@extends('home.layout')
@section('content')
<section class="parallax-window" data-parallax="scroll" data-image-src="{{url('images/home/about.jpg')}}" data-natural-width="1400" data-natural-height="">
    <div class="parallax-content-1">
        <div class="animated fadeInDown">
            <h1>About us</h1>
        </div>
    </div>
</section>
<!-- End Section -->

<main>
    @component('partials.home.position')
    @slot('title')
        About Us
        @endslot
    @endcomponent

    <div class="container margin_60">

        <div class="main_title">
            <h2>Join <span>our</span> community</h2>
        </div>

        <div class="row">

            <div class="col-lg-4 wow zoomIn" data-wow-delay="0.2s">
                <div class="feature_home">
                    <i class="icon_set_1_icon-41"></i>
                    <h3><span>200+</span> Tickets</h3>
                    <p>
                        Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 wow zoomIn" data-wow-delay="0.4s">
                <div class="feature_home">
                    <i class="icon_set_1_icon-30"></i>
                    <h3><span>1000+</span> Customers</h3>
                    <p>
                        Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset.
                    </p>
                </div>
            </div>

            <div class="col-lg-4 wow zoomIn" data-wow-delay="0.6s">
                <div class="feature_home">
                    <i class="icon_set_1_icon-57"></i>
                    <h3><span>24h </span> Support</h3>
                    <p>
                        Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset.
                    </p>
                </div>
            </div>

        </div>
        <!--End row -->
    </div>
    <!-- End container -->

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 nopadding features-intro-img">
                <div class="features-bg">
                    <div class="features-img"></div>
                </div>
            </div>
            <div class="col-lg-6 nopadding">
                <div class="features-content">
                    <h3>"Briefly About Us"</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a lorem quis neque interdum consequat ut sed sem. Duis quis tempor nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a lorem quis neque interdum consequat ut sed sem. Duis quis tempor nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus.</p>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- End container-fluid  -->

    <div class="container margin_60">

        <div class="main_title">
            <h2>What <span>customers </span>says</h2>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="review_strip">
                    <img src="{{url('images/home/avatar2.jpg')}}" alt="Image" class="rounded-circle">
                    <h4>Jack Doe</h4>
                    <p>
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a lorem quis neque interdum consequat ut sed sem. Duis quis tempor nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus."
                    </p>
                    <div class="rating">
                        &nbsp;						</div>
                </div>
                <!-- End review strip -->
            </div>

            <div class="col-lg-6">
                <div class="review_strip">
                    <img src="{{url('images/home/avatar2.jpg')}}" alt="Image" class="rounded-circle">
                    <h4>Frank Rosso</h4>
                    <p>
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a lorem quis neque interdum consequat ut sed sem. Duis quis tempor nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus."
                    </p>
                    <div class="rating">
                        &nbsp;
                    </div>
                </div>
                <!-- End review strip -->
            </div>
        </div>
        <!-- End row -->
        <div class="row">
            <div class="col-lg-6">
                <div class="review_strip">
                    <img src="{{url('images/home/avatar3.jpg')}}" alt="Image" class="rounded-circle">
                    <h4>Marc twain</h4>
                    <p>
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a lorem quis neque interdum consequat ut sed sem. Duis quis tempor nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus."
                    </p>
                    <div class="rating">
                        &nbsp;
                    </div>
                </div>
                <!-- End review strip -->
            </div>

            <div class="col-lg-6">
                <div class="review_strip">
                    <img src="{{url('images/home/avatar2.jpg')}}" alt="Image" class="rounded-circle">
                    <h4>Peter Gabriel</h4>
                    <p>
                        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed a lorem quis neque interdum consequat ut sed sem. Duis quis tempor nunc. Interdum et malesuada fames ac ante ipsum primis in faucibus."
                    </p>
                    <div class="rating">
                        &nbsp;
                    </div>
                </div>
                <!-- End review strip -->
            </div>
        </div>
        <!-- End row -->

        <hr>

        <div class="row">
            <div class="col-md-6">
                <img src="{{url('images/home/app.png')}}" alt="Laptop" class="img-fluid laptop">
            </div>
            <div class="col-md-6">
                <h3><span>Get started</span> with Fika Places App</h3>
                <p>
                    Lorem ipsum dolor sit amet, vix erat audiam ei. Cum doctus civibus efficiantur in. Nec id tempor imperdiet deterruisset.
                </p>
                <ul class="list_order">
                    <li><span>1</span>Lorem ipsum dolor sit amet, vix erat audiam ei.</li>
                    <li><span>2</span>Lorem ipsum dolor sit amet, vix erat audiam ei.</li>
                    <li><span>3</span>Lorem ipsum dolor sit amet, vix erat audiam ei.</li>
                    <li><span>4</span>Lorem ipsum dolor sit amet, vix erat audiam ei.</li>

                </ul>
                <img src="{{url('images/home/playstore.png')}}">
                <br><br><br>
                {{--<img src="img/apple.png">--}}
            </div>
        </div>
        <!-- End row -->

    </div>
    <!-- End Container -->
</main>
<!-- End main -->
@endsection
