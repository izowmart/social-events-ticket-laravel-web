@extends('home.layout')
@section('content')
    <section class="parallax-window" data-parallax="scroll" data-image-src="{{url('images/home/contact.jpg')}}" data-natural-width="1400" data-natural-height="">
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
                Contact Us
        @endslot
    @endcomponent
        <!-- End Position -->

        <div class="container margin_60">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-8">
                    <div class="form_title">
                        <h3><strong><i class="icon-pencil"></i></strong>Fill the form below</h3>
                        <p>
                            If you have inquiries, comments or want to speak to any of our representatives please feel free to fill out the form below and submit it.
                        </p>
                        <br>
                        <p>
                            We aim to reply to all queries, to your given Email Address below, within 24 – 48 hours. Your email address will not be published*.
                        </p>
                    </div>
                    <div class="step">

                        <div id="message-contact"></div>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="post" action="{{route('contact_post')}}" id="contactform">
                            @csrf
                            @honeypot
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>First Name</label>
                                        <input type="text" class="form-control" id="name_contact" name="name_contact" placeholder="Enter Name" value="{{old('name_contact')}}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Last Name</label>
                                        <input type="text" class="form-control" id="lastname_contact" name="lastname_contact" placeholder="Enter Last Name" value="{{old('lastname_contact')}}">
                                    </div>
                                </div>
                            </div>
                            <!-- End row -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" id="email_contact" name="email_contact" class="form-control" placeholder="Enter Email" value="{{old('email_contact')}}">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label>Phone</label>
                                        <input type="text" id="phone_contact" name="phone_contact" class="form-control" placeholder="Enter Phone number" value="{{old('phone_contact')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label>Message</label>
                                        <textarea rows="5" id="message_contact" name="message_contact" class="form-control" placeholder="Write your message" style="height:200px;">{{old('message_contact')}}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label>Human verification</label>
                                    <input type="text" id="verify_contact" name="verify_contact" class="form-control add_bottom_30" placeholder="Are you human? 3 + 1 =">
                                    <input type="submit" value="Submit" class="btn_1" id="submit-contact">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- End col-md-8 -->

                <div class="col-md-4">
                    <div class="box_style_1">
                        <span class="tape"></span>
                        <h4>Address <span><i class="icon-pin pull-right"></i></span></h4>
                        <p>
                            Ngong Road, Nairobi - Kenya.
                        </p>
                        <hr>
                        <h4>Help center <span><i class="icon-help pull-right"></i></span></h4>
                        <ul id="contact-info">
                            <li>+ 254 722 123 456</li>
                            <li><a href="#">help@fikaplaces.com</a>
                            </li>
                        </ul>
                    </div>

                </div>
                <!-- End col-md-4 -->
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->


    </main>
    <!-- End main -->
@endsection