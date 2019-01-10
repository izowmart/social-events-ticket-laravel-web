@extends('home.layout')
@section('content')
    <section class="parallax-window" data-parallax="scroll" data-image-src="{{url('images/home/contact.jpg')}}"
             data-natural-width="1400" data-natural-height="">
        <div class="parallax-content-1">
            <div class="animated fadeInDown">
                <h1>Contact us</h1>
            </div>
        </div>
    </section>
    <!-- End Section -->

    <main>
        @component('partials.home.position')
            @slot('title')
                Ticket Information
        @endslot
    @endcomponent
    <!-- End Position -->
        <div class="container margin_60">
            <div class="main_title">
                <h2>Events <span>and Ticket </span>Information</h2>
            </div>

            <div class="row justify-content-between">
                <p>We provide a ticket scanning App for free that is available on android phones only. This comes with
                    instructions on how to use it and is directly linked to your event organiser account. To create an
                    Event Organiser account click here. To download our Fika Ticket Scanner App click here. Several
                    people can download the app and temporarily access your event in order to help you scan tickets.</p>
            </div>

            <div class="row">
                <div class="col-md-8 offset-2">
                    <ul class="list_ok">
                        <li>You must first create an Event in your event organiser profile</li>
                        <li>Add their email to your team list for the specific event</li>
                        <li>They will receive an email with the app and log in credentials, which will allow them to
                            scan tickets for your event
                        </li>
                        <li>They can also just download the app from Google play store</li>
                    </ul>
                </div>
            </div>

            <div class="row justify-content-between">
                <ul class="list_orders">
                    <li>Fika Places charges 8% for transactions.</li>
                    <li>Settlements can take up to a maximum of 7 days to be completed. This is to act a time bigger for
                        any issues that may arise with the event and/or with customers of the event.
                    </li>
                    <li>For an additional fee we can add your event to home page with more traffic. For this service
                        contact us on info@fikaplaces.com with the subject Homepage Events.
                    </li>
                </ul>
            </div>

        </div>

    </main>

@endsection