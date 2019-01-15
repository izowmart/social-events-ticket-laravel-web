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

        <div class="post">
            <p> Fika Places is a Kenyan based company that provides people with information regarding the
            entertainment sector in Kenya. More specifically we identify and highlight the best places to eat and the
            best places to party  </p>
            <p> You can download our App <a href="http://play.google.com/store/apps/details?id=com.fika.places">here</a> and begin to discover new establishments for your entertainment.
            Our app allows you to take and share pictures/videos in different venues. The content will be seen by
            others and used almost as a review, allowing them to understand what that particular venue looks/feels
            like before they visit.</p>
            <p>Fika Places also displays venue contact details and GPS location, allowing users to contact the venue to
            make bookings or use its location for directions.</p>
            <p>All content on the Fika Places mobile application is user generated and therefore images and videos that
            you see are accurate representations of the venues you visit.</p>
            <p>Sharing the app with friends will enable you to see a variety of venues, allowing you to experience more.</p>

            <p>In addition to all those functions, Fika Places also has tickets for various events, which users can buy.
            Tickets can be bought directly from the app, or from our website. However, App ticket holders will have
            more benefits in some events.</p>

            <p>See Share and Love you City</p>
            <p>Download Fika Places now.</p>
        </div>
    </div>
    <!-- End container -->
</main>
<!-- End main -->
@endsection
