@extends('home.layout')
@section('content')
<main>
    <div id="rev_slider_54_1_wrapper" class="rev_slider_wrapper fullwidthbanner-container" data-alias="notgeneric1" data-source="gallery" style="margin:0px auto;background:transparent;padding:0px;margin-top:0px;margin-bottom:0px;">
        <!-- START REVOLUTION SLIDER 5.4.1 fullwidth mode -->
        <div id="rev_slider_54_1" class="rev_slider fullwidthabanner" style="display:none;" data-version="5.4.1">
            <ul>
                <!-- SLIDE  -->
                <li data-index="rs-140" data-transition="zoomout" data-slotamount="default" data-hideafterloop="0" data-hideslideonmobile="off" data-easein="Power4.easeInOut" data-easeout="Power4.easeInOut" data-masterspeed="2000" data-thumb="rev-slider-files/assets/100x50_notgeneric_bg1.jpg" data-rotate="0" data-fstransition="fade" data-fsmasterspeed="1500" data-fsslotamount="7" data-saveperformance="off" data-title="Intro" data-param1="" data-param2="" data-param3="" data-param4="" data-param5="" data-param6="" data-param7="" data-param8="" data-param9="" data-param10="" data-description="">
                    <!-- MAIN IMAGE -->
                    <img src="{{url('images/home/slider/slider1.jpg')}}" alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" data-bgparallax="10" class="rev-slidebg" data-no-retina>
                    <!-- LAYERS -->

                    <!-- LAYER NR. 1 -->
                    <div class="tp-caption NotGeneric-Title   tp-resizeme" id="slide-140-layer-1" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['0','0','0','0']" data-fontsize="['50','46','36','28']" data-lineheight="['46','46','36','28']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1000,"split":"chars","split_direction":"forward","splitdelay":0.05,"speed":2000,"frame":"0","from":"x:[105%];z:0;rX:45deg;rY:0deg;rZ:90deg;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' data-textAlign="['left','left','left','left']" data-paddingtop="[10,10,10,10]" data-paddingright="[0,0,0,0]" data-paddingbottom="[10,10,10,10]" data-paddingleft="[0,0,0,0]" style="z-index: 5; white-space: nowrap; font-size: 50px; line-height: 46px; font-weight: 700;font-family:Montserrat;">WELCOME TO FIKAPLACES </div>

                    <!-- LAYER NR. 2 -->
                    <div class="tp-caption NotGeneric-SubTitle   tp-resizeme" id="slide-140-layer-4" data-x="['center','center','center','center']" data-hoffset="['0','0','0','0']" data-y="['middle','middle','middle','middle']" data-voffset="['52','52','52','51']" data-fontweight="['400','500','500','500']" data-width="none" data-height="none" data-whitespace="nowrap" data-type="text" data-responsive_offset="on" data-frames='[{"delay":1500,"speed":2000,"frame":"0","from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;opacity:0;","mask":"x:0px;y:[100%];s:inherit;e:inherit;","to":"o:1;","ease":"Power4.easeInOut"},{"delay":"wait","speed":1000,"frame":"999","to":"y:[100%];","mask":"x:inherit;y:inherit;s:inherit;e:inherit;","ease":"Power2.easeInOut"}]' data-textAlign="['left','left','left','left']" data-paddingtop="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" style="z-index: 6; white-space: nowrap; font-weight: 400;font-family:Montserrat;">KENYA'S #1 TICKET PLATFORM</div>
                </li>

            </ul>
            <div class="tp-bannertimer tp-bottom" style="visibility: hidden !important;"></div>
        </div>
    </div>
    <!-- END REVOLUTION SLIDER -->

    @if($featured_events->count()>0 || $non_featured_events->count()>0 || $free_events->count()>0)
    <div class="container margin_60">

        @if($featured_events->count()>0)
        <div class="main_title">
            <h2>Featured <span>Events</span></h2>
        </div>

        <div class="row">
            @foreach($featured_events as $index => $featured_event)
            <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.{{$index+1}}s">
                <div class="tour_container">
                    <div class="ribbon_3 popular"><span>Featured</span></div>
                    <div class="img_container">
                        <a href="single_event.html">
                            <img src="{{url('storage/images/events/'.$featured_event->media_url)}}" width="800" height="533" class="img-fluid" alt="image">
                            {{--<div class="short_info">--}}
                                {{--<span class="price"><sup>KES</sup>1500</span>--}}
                            {{--</div>--}}
                        </a>
                    </div>
                    <div class="tour_title">
                        <h3><strong>{{$featured_event->name}}</strong></h3>
                        {{--<div class="rating">--}}
                            {{--&nbsp;							</div>--}}
                        {{--<!-- end rating -->--}}

                    </div>
                </div>
                <!-- End box tour -->
            </div>
            <!-- End col -->
            @endforeach

            {{--<div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.2s">--}}
                {{--<div class="tour_container">--}}
                    {{--<div class="ribbon_3 popular"><span>Featured</span></div>--}}
                    {{--<div class="img_container">--}}
                        {{--<a href="single_tour.html">--}}
                            {{--<img src="{{url('images/home/featured.jpg')}}" width="800" height="533" class="img-fluid" alt="image">--}}
                            {{--<div class="short_info">--}}
                                {{--<span class="price"><sup>KES</sup>500</span>--}}
                            {{--</div>--}}
                        {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="tour_title">--}}
                        {{--<h3><strong>Featured Event</strong> 2</h3>--}}
                        {{--<div class="rating">--}}
                            {{--&nbsp;							</div>--}}
                        {{--<!-- end rating -->--}}

                    {{--</div>--}}
                {{--</div>--}}
                {{--<!-- End box tour -->--}}
            {{--</div>--}}
            {{--<!-- End col -->--}}

            {{--<div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.3s">--}}
                {{--<div class="tour_container">--}}
                    {{--<div class="ribbon_3 popular"><span>Featured</span></div>--}}
                    {{--<div class="img_container">--}}
                        {{--<a href="single_tour.html">--}}
                            {{--<img src="{{url('images/home/featured.jpg')}}" width="800" height="533" class="img-fluid" alt="image">--}}
                            {{--<div class="short_info">--}}
                                {{--<span class="price"><sup>KES</sup>1500</span>--}}
                            {{--</div>--}}
                        {{--</a>--}}
                    {{--</div>--}}
                    {{--<div class="tour_title">--}}
                        {{--<h3><strong>Featured Event</strong> 3</h3>--}}
                        {{--<div class="rating">--}}
                            {{--&nbsp;							</div>--}}
                        {{--<!-- end rating -->--}}

                    {{--</div>--}}
                {{--</div>--}}
                {{--<!-- End box tour -->--}}
            {{--</div>--}}
            {{--<!-- End col -->--}}



        </div>
        <!-- End row -->

        <p class="text-center add_bottom_30">
            <a href="#" class="btn_1 medium"><i class="icon-eye-7"></i>View all events</a>
        </p>

        <hr>
        @endif

        @if($non_featured_events->count()>0)
        <div class="main_title">
            <h2><span>Paid</span> Events</h2>
        </div>

        <div class="row">
            @foreach($non_featured_events as $index => $non_featured_event)
            <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.{{$index+1}}s">
                <div class="hotel_container">
                    {{--<div class="ribbon_3"><span>Top Rated</span></div>--}}
                    <div class="img_container">
                        <a href="single_hotel.html">
                            <img src="{{url('storage/images/events/'.$non_featured_event->media_url)}}" width="800" height="533" class="img-fluid" alt="image">
                            <div class="short_info hotel">
                            </div>
                        </a>
                    </div>
                    <div class="hotel_title">
                        <h3><strong>{{$non_featured_event->name}} </strong></h3>
                        <div class="rating">
                            &nbsp;							</div>
                        <!-- end rating -->
                    </div>
                </div>
                <!-- End box -->
            </div>
            <!-- End col -->
            @endforeach

        </div>
        <!-- End row -->

        <p class="text-center nopadding">
            <a href="all_hotels_list.html" class="btn_1 medium"><i class="icon-eye-7"></i>View all venues</a>
        </p>
            @endif

            @if($free_events->count()>0)
                <div class="main_title">
                    <h2><span>Free</span> Events</h2>
                </div>

                <div class="row">
                    @foreach($free_events as $index => $free_event)
                        <div class="col-lg-4 col-md-6 wow zoomIn" data-wow-delay="0.{{$index+1}}s">
                            <div class="hotel_container">
                                {{--<div class="ribbon_3"><span>Top Rated</span></div>--}}
                                <div class="img_container">
                                    <a href="single_hotel.html">
                                        <img src="{{url('storage/images/events/'.$free_event->media_url)}}" width="800" height="533" class="img-fluid" alt="image">
                                        <div class="short_info hotel">
                                        </div>
                                    </a>
                                </div>
                                <div class="hotel_title">
                                    <h3><strong>{{$free_event->name}} </strong></h3>
                                    <div class="rating">
                                        &nbsp;							</div>
                                    <!-- end rating -->
                                </div>
                            </div>
                            <!-- End box -->
                        </div>
                        <!-- End col -->
                    @endforeach

                </div>
                <!-- End row -->

                <p class="text-center nopadding">
                    <a href="all_hotels_list.html" class="btn_1 medium"><i class="icon-eye-7"></i>View all venues</a>
                </p>
            @endif

    </div>
    <!-- End container -->
    @endif

    <div class="white_bg">
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
                    <a href="https://play.google.com/store/apps/details?id=com.fika.places" target="_blank"><img src="{{url('images/home/playstore.png')}}" alt="play store"></a>
                    <br><br><br>
                    {{--<img src="img/apple.png">--}}
                </div>
            </div>
            <!-- End row -->


        </div>
        <!-- End container -->
    </div>
    <!-- End white_bg -->
</main>
@endsection