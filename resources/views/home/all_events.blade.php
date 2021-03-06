@extends('home.layout')
@section('content')
    <section class="parallax-window" data-parallax="scroll" data-image-src="{{url('images/home/about.jpg')}}"
             data-natural-width="1400" data-natural-height="470">
        <div class="parallax-content-1">
            <div class="animated fadeInDown">
                <h1>Buy Tickets</h1>
            </div>
        </div>
    </section>
    <!-- End section -->

    <main>
        <div id="position">
            <div class="container">
                <ul>
                    <li><a href="#">Home</a>
                    </li>
                    <li><a href="#">Buy Tickets</a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Position -->

        <div class="collapse" id="collapseMap">
            <div id="map" class="map"></div>
        </div>
        <!-- End Map -->

        <div class="container margin_60">
            <div class="row">
                <aside class="col-lg-3">


                    {{--<div class="box_style_cat">--}}
                    {{--<ul id="cat_nav">--}}
                    {{--<li><a href="#" id="active"><i class="icon_set_1_icon-51"></i>All Tickets <span>(141)</span></a>--}}
                    {{--</li>--}}
                    {{--<li><a href="#"><i class="icon_set_1_icon-3"></i>Entertainment <span>(20)</span></a>--}}
                    {{--</li>--}}
                    {{--<li><a href="#"><i class="icon_set_1_icon-4"></i>Movies <span>(16)</span></a>--}}
                    {{--</li>--}}
                    {{--<li><a href="#"><i class="icon_set_1_icon-44"></i>Conferencing <span>(12)</span></a>--}}
                    {{--</li>--}}
                    {{--<li><a href="#"><i class="icon_set_1_icon-37"></i>Sports <span>(11)</span></a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</div>--}}

                    {{--<div id="filters_col">--}}
                    {{--<a data-toggle="collapse" href="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters" id="filters_col_bt"><i class="icon_set_1_icon-65"></i>Filters</a>--}}
                    {{--<div class="collapse show" id="collapseFilters">--}}
                    {{--<div class="filter_type">--}}
                    {{--<h6>Price</h6>--}}
                    {{--<input type="text" id="range" name="range" value="">--}}
                    {{--</div>--}}


                    {{--</div>--}}
                    {{--<!--End collapse -->--}}
                    {{--</div>--}}
                    {{--<!--End filters col-->--}}
                    {{--<div class="box_style_2">--}}
                    {{--<i class="icon_set_1_icon-57"></i>--}}
                    {{--<h4>Need <span>Help?</span></h4>--}}
                    {{--<a href="tel://004542344599" class="phone">+254 722 123 456</a>--}}
                    {{--<small>Monday to Friday 9.00am - 7.30pm</small>--}}
                    {{--</div>--}}
                </aside>
                <!--End aside -->

                <div class="col-lg-9">

                {{--<div id="tools">--}}
                {{--<div class="row">--}}
                {{--<div class="col-md-3 col-sm-4 col-6">--}}
                {{--<div class="styled-select-filters">--}}
                {{--<select name="sort_price" id="sort_price">--}}
                {{--<option value="" selected>Sort by price</option>--}}
                {{--<option value="lower">Lowest price</option>--}}
                {{--<option value="higher">Highest price</option>--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-3 col-sm-4 col-6">--}}
                {{--<div class="styled-select-filters">--}}
                {{--<select name="sort_rating" id="sort_rating">--}}
                {{--<option value="" selected>Sort by ranking</option>--}}
                {{--<option value="lower">Lowest ranking</option>--}}
                {{--<option value="higher">Highest ranking</option>--}}
                {{--</select>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-md-6 col-sm-4 d-none d-sm-block text-right">--}}
                {{--<a href="#" class="bt_filters"><i class="icon-th"></i></a> --}}
                {{--<a href="all_tours_list.html" class="bt_filters"><i class=" icon-list"></i></a>--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <!--End tools -->


                    <div class="row">
                        @forelse ($events as $event)
                            <div class="col-md-6 wow zoomIn" data-wow-delay="0.{{$loop->iteration}}s">
                                <div class="tour_container">
                                    @if($event->type == 2 && $event->featured_event == 1)
                                        <div class="ribbon_3 popular"><span>Featured</span></div>
                                    @endif
                                    <div class="img_container">
                                        <a href="{{route('single_event',$event->slug)}}">
                                            <img src="{{url('storage/images/events/'.$event->media_url)}}" width="800"
                                                 height="533" class="img-fluid" alt="Image">
                                            <div class="short_info">

                                            </div>
                                        </a>
                                    </div>
                                    <div class="tour_title">
                                        <h3><strong>{{$event->name}}</strong></h3>
                                        <p>{!! str_limit($event->description, $limit = 90, $end = '...') !!}</p>
                                    </div>
                                </div>
                                <!-- End box tour -->
                            </div>
                            <!-- End col-md-6 -->

                            <!-- End row -->
                        @empty
                            <p>No Events</p>
                        @endforelse
                    </div>

                    <hr>
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            {{ $events->links() }}
                        </ul>
                    </nav>
                    <!-- end pagination-->
                </div>
                <!-- End col lg 9 -->
            </div>
            <!-- End row -->
        </div>
        <!-- End container -->
    </main>
    <!-- End main -->
@endsection