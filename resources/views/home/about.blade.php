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
            <p>Fika Places is a Kenyan based company that provides it's customers with information regarding nightlife, leisure and entertainment in Kenya. To be specific, we highlight the best places to spend your free time.
            </p>
            <p>
                You can download our App <a href="http://play.google.com/store/apps/details?id=com.fika.places">here</a> and begin to discover new venues that suit your style of entertainment. Our App (Fika Places) allows you to record and share pictures/videos from different venues, giving you the chance to show your friends and others the authentic feel of a venue.
            </p>
            <p>Fika Places provides users with venue contacts, as well as GPS location. Making it easier to make reservations and/or get directions. </p>
            <p>In addition to all of this, our customers can also buy tickets for various events through the Fika Places App. We provide the option of buying tickets from our website as well, but customers who buy using the App will qualify for gifts and offers in specific events.</p>
            <p>See Share and Love your City.</p>

            <p><a href="http://play.google.com/store/apps/details?id=com.fika.places">Download Fika Places now.</a></p>
        </div>
    </div>
    <!-- End container -->
</main>
<!-- End main -->
@endsection
