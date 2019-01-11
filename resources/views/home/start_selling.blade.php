@extends('home.layout')
@section('content')
    <section class="parallax-window" data-parallax="scroll" data-image-src="{{url('images/home/about.jpg')}}" data-natural-width="1400" data-natural-height="">
        <div class="parallax-content-1">
            <div class="animated fadeInDown">
                <h1>Start Selling</h1>
            </div>
        </div>
    </section>
    <!-- End Section -->

    <main>
        @component('partials.home.position')
            @slot('title')
                Start Selling
        @endslot
    @endcomponent
        <!-- End Position -->

        <div class="container margin_60">

            <div class="main_title">
                <h2>Join <span>our</span> community</h2>
                <br>
                <p>We provide a ticket scanning App for free that is available on android phones only. This comes with instructions on how to use it and is directly linked to your event organiser account.</p>


            </div>
            <br>

            <div class="main_title">


                <div>
                    <br>

                    <div class="row">


                        <div class="col-lg-4 wow zoomIn" data-wow-delay="0.2s">
                            <div class="feature_home">
                                <a href="http://parareagency.com/fika/register.html"><i class="icon-laptop"></i></a>
                                <h3>Create Organizers Account </h3>
                                <p>
                                    Fika allows you to sell tickets online for a festival, concert, conference, movie, play or sports<br> tournament.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-4 wow zoomIn" data-wow-delay="0.4s">
                            <div class="feature_home">
                                <i class="icon-mobile"></i>
                                <h3>Download Fika Scanner App</h3>
                                <p>
                                    Several people can download the app and temporarily access your event in order to help you scan<br> tickets.
                                </p>
                            </div>
                        </div>

                        <div class="col-lg-4 wow zoomIn" data-wow-delay="0.6s">
                            <div class="feature_home">
                                <i class="icon-money"></i>
                                <h3><span>8 %</span> Per Transaction</h3>
                                <p>Settlements can take up to a maximum of 7 days to be completed. This is to act on any issues that may arise.
                                </p>
                            </div>
                        </div>

                    </div>
                    <!--End row -->


                </div>
                <!-- End container -->


                <div class="container-fluid" width="100%">
                    <div class="row">

                        <div >
                            <div class="features-content">
                                <h3>"For an additional fee we can add your event to the homepage with more traffic. For this service contact us on info@fikaplaces.com with the subject Homepage Events."</h3>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End container-fluid  -->
            </div>
        </div>

    </main>
    <!-- End main -->
@endsection