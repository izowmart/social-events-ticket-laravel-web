<div class="container">
    <div class="row">
        <div class="col-3">
            <div id="logo_home">
                <h1><a href="{{route('home')}}" title="Fika Places">Fika Places</a></h1>
            </div>
        </div>
        <nav class="col-9">
            <a class="cmn-toggle-switch cmn-toggle-switch__htx open_close" href="javascript:void(0);"><span>Menu mobile</span></a>
            <div class="main-menu">
                <div id="header_menu">
                    <img src="{{url('images/home/logo_sticky.png')}}" width="160" height="34" alt="logo" data-retina="true">
                </div>
                <a href="#" class="open_close" id="close_in"><i class="icon_set_1_icon-77"></i></a>
                <ul>
                    <li><a href="{{route('home')}}" >Home</a></li>
                    <li><a href="{{route('about')}}" >About Us</a></li>
                    <li><a href="{{route('tickets-info')}}" >Tickets</a></li>
                    <li><a href="{{route('blog')}}" >Blog</a></li>

                    <li><a href="{{route('contact')}}" >Contacts</a></li>



                </ul>
            </div><!-- End main-menu -->
            {{--<ul id="top_tools">--}}
                {{--<li>--}}
                    {{--<a href="javascript:void(0);" class="search-overlay-menu-btn"><i class="icon_search"></i></a>--}}
                    {{--&nbsp;&nbsp;&nbsp;--}}
                {{--</li>--}}

            {{--</ul>--}}
        </nav>
    </div>
</div><!-- container -->