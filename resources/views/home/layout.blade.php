<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Fika allows you to see and contact venues near you, get updated on the latest events, buy tickets to those events and benefit from offers only available for fika users">
    <meta name="author" content="Agoya">
    <title>Fika Places | Kenya's #1 Ticket Platforms</title>

    <!-- Favicons-->
    {{--<link rel="shortcut icon" href="{{url('favicon.ico')}}" type="image/x-icon">--}}
    {{--<link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">--}}
    {{--<link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">--}}
    {{--<link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">--}}
    {{--<link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">--}}
    <link rel="apple-touch-icon" sizes="57x57" href="{{url('apple-icon-57x57.png')}}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{url('apple-icon-60x60.png')}}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{url('apple-icon-72x72.png')}}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{url('apple-icon-76x76.png')}}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{url('apple-icon-114x114.png')}}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{url('apple-icon-120x120.png')}}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{url('apple-icon-144x144.png')}}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{url('apple-icon-152x152.png')}}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{url('apple-icon-180x180.png')}}">
    <link rel="icon" type="image/png" sizes="192x192"  href="{{url('android-icon-192x192.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{url('favicon-32x32.png')}}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{url('favicon-96x96.png')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{url('favicon-16x16.png')}}">
    <link rel="manifest" href="{{'manifest.json'}}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{url('ms-icon-144x144.png')}}">
    <meta name="theme-color" content="#ffffff">
    
    <!-- COMMON CSS -->
    <link href="{{'css/bootstrap.min.css'}}" rel="stylesheet">
    <link href="{{'css/home/style.css'}}" rel="stylesheet">
    <link href="{{'css/home/vendors.css'}}" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link href="{{'css/home/custom.css'}}" rel="stylesheet">

    <!-- REVOLUTION SLIDER CSS -->
    <link rel="stylesheet" type="text/css" href="{{'rev_slider_files/fonts/font-awesome/css/font-awesome.css'}}">
    <link rel="stylesheet" type="text/css" href="{{'rev_slider_files/css/settings.css'}}">

    <!-- REVOLUTION LAYERS STYLES -->
    <style>
        .tp-caption.NotGeneric-Title,
        .NotGeneric-Title {
            color: rgba(255, 255, 255, 1.00);
            font-size: 70px;
            line-height: 70px;
            font-weight: 800;
            font-style: normal;
            text-decoration: none;
            background-color: transparent;
            border-color: transparent;
            border-style: none;
            border-width: 0px;
            border-radius: 0 0 0 0px
        }

        .tp-caption.NotGeneric-SubTitle,
        .NotGeneric-SubTitle {
            color: rgba(255, 255, 255, 1.00);
            font-size: 13px;
            line-height: 20px;
            font-weight: 500;
            font-style: normal;
            text-decoration: none;
            background-color: transparent;
            border-color: transparent;
            border-style: none;
            border-width: 0px;
            border-radius: 0 0 0 0px;
            letter-spacing: 4px
        }

        .tp-caption.NotGeneric-Icon,
        .NotGeneric-Icon {
            color: rgba(255, 255, 255, 1.00);
            font-size: 30px;
            line-height: 30px;
            font-weight: 400;
            font-style: normal;
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0);
            border-color: rgba(255, 255, 255, 0);
            border-style: solid;
            border-width: 0px;
            border-radius: 0px 0px 0px 0px;
            letter-spacing: 3px
        }

        .tp-caption.NotGeneric-Button,
        .NotGeneric-Button {
            color: rgba(255, 255, 255, 1.00);
            font-size: 14px;
            line-height: 14px;
            font-weight: 500;
            font-style: normal;
            text-decoration: none;
            background-color: rgba(0, 0, 0, 0);
            border-color: rgba(255, 255, 255, 0.50);
            border-style: solid;
            border-width: 1px;
            border-radius: 0px 0px 0px 0px;
            letter-spacing: 3px
        }

        .tp-caption.NotGeneric-Button:hover,
        .NotGeneric-Button:hover {
            color: rgba(255, 255, 255, 1.00);
            text-decoration: none;
            background-color: transparent;
            border-color: rgba(255, 255, 255, 1.00);
            border-style: solid;
            border-width: 1px;
            border-radius: 0px 0px 0px 0px;
            cursor: pointer
        }
    </style>

</head>

<body>



<div class="layer"></div>
<!-- Mobile menu overlay mask -->

<!-- Header================================================== -->
<header id="plain">
    <div id="top_line">
        <div class="container">
            <div class="row">
                <div class="col-6"><i class="icon-phone"></i><strong>+254 722 123 456</strong></div>

                <div class="col-6">
                    <ul id="top_links">
                        <li><a href="{{route('start-selling')}}">Sell Tickets</a></li>

                        <li><a href="#sign-in-dialog" id="access_link">Sign in</a></li>
                    </ul>
                </div>
            </div><!-- End row -->
        </div><!-- End container-->
    </div><!-- End top line-->

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
                <ul id="top_tools">
                    <li>
                        <a href="javascript:void(0);" class="search-overlay-menu-btn"><i class="icon_search"></i></a>
                        &nbsp;&nbsp;&nbsp;
                    </li>

                </ul>
            </nav>
        </div>
    </div><!-- container -->
</header><!-- End Header -->

@yield('content')
<!-- End main -->

<footer class="revealed">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <h3>Need help?</h3>
                <a href="tel://+254722123456" id="phone">+254 722 123 456</a>
                <a href="mailto:help@fikaplaces.com" id="email_footer">help@fikaplaces.com</a>
            </div>
            <div class="col-md-3">
                <h3>About</h3>
                <ul>
                    <li><a href="{{route('about')}}">About us</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Login</a></li>
                    <li><a href="#">Register</a></li>
                    <li><a href="#">Terms and condition</a></li>
                </ul>
            </div>
            <div class="col-md-3">
                <h3>Discover</h3>
                <ul>
                    <li><a href="#">Community blog</a></li>
                    <li><a href="#">Event guide</a></li>
                    <li><a href="#">Gallery</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h3>Download</h3>
                <ul>
                    <li><a href="#">Andriod App</a></li>
                    <li><a href="#">iOS App</a></li>
                </ul>

            </div>
        </div><!-- End row -->
        <div class="row">
            <div class="col-md-12">
                <div id="social_footer">
                    <ul>
                        <li><a href="#"><i class="icon-facebook"></i></a></li>
                        <li><a href="#"><i class="icon-twitter"></i></a></li>
                        <li><a href="#"><i class="icon-instagram"></i></a></li>
                        <li><a href="#"><i class="icon-youtube-play"></i></a></li>
                    </ul>
                    <p>© FikaPlaces 2018</p>
                </div>
            </div>
        </div><!-- End row -->
    </div><!-- End container -->
</footer><!-- End footer -->

<div id="toTop"></div><!-- Back to top button -->

<!-- Search Menu -->
<div class="search-overlay-menu">
    <span class="search-overlay-close"><i class="icon_set_1_icon-77"></i></span>
    <form role="search" id="searchform" method="get">
        <input value="" name="q" type="search" placeholder="Search..." />
        <button type="submit"><i class="icon_set_1_icon-78"></i>
        </button>
    </form>
</div><!-- End Search Menu -->

<!-- Sign In Popup -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide">
    <div class="small-dialog-header">
        <h3>Sign In</h3>
    </div>
    <form>
        <div class="sign-in-wrapper">
            {{--<a href="#0" class="social_bt facebook">Login with Facebook</a>--}}
            {{--<a href="#0" class="social_bt google">Login with Google</a>--}}
            {{--<div class="divider"><span>Or</span></div>--}}
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" id="email">
                <i class="icon_mail_alt"></i>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" name="password" id="password" value="">
                <i class="icon_lock_alt"></i>
            </div>
            <div class="clearfix add_bottom_15">
                <div class="checkboxes float-left">
                    <input id="remember-me" type="checkbox" name="check">
                    <label for="remember-me">Remember Me</label>
                </div>
                <div class="float-right"><a id="forgot" href="javascript:void(0);">Forgot Password?</a></div>
            </div>
            <div class="text-center"><input type="submit" value="Log In" class="btn_login"></div>
            <div class="text-center">
                Don’t have an account? <a href="javascript:void(0);">Sign up</a>
            </div>
            <div id="forgot_pw">
                <div class="form-group">
                    <label>Please confirm login email below</label>
                    <input type="email" class="form-control" name="email_forgot" id="email_forgot">
                    <i class="icon_mail_alt"></i>
                </div>
                <p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
                <div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
            </div>
        </div>
    </form>
    <!--form -->
</div>
<!-- /Sign In Popup -->

<!-- Common scripts -->
<script src="{{'js/jquery-2.2.4.min.js'}}"></script> {{--NOTE:: using any other version of js causes the search, login & mobile menu not to work--}}
<script src="{{'js/home/common_scripts_min.js'}}"></script>
<script src="{{'js/home/functions.js'}}"></script>

<!-- SLIDER REVOLUTION SCRIPTS  -->
<script src="{{url('js/rev-slider/jquery.themepunch.tools.min.js')}}"></script>
<script src="{{url('js/rev-slider/jquery.themepunch.revolution.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.actions.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.carousel.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.kenburn.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.layeranimation.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.migration.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.navigation.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.parallax.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.slideanims.min.js')}}"></script>
<script src="{{url('js/rev-slider/extensions/revolution.extension.video.min.js')}}"></script>
<script>
    var tpj = jQuery;
    var revapi54;
    tpj(document).ready(function () {
        if (tpj("#rev_slider_54_1").revolution == undefined) {
            revslider_showDoubleJqueryError("#rev_slider_54_1");
        } else {
            revapi54 = tpj("#rev_slider_54_1").show().revolution({
                sliderType: "standard",
                jsFileLocation: "rev-slider-files/js/",
                sliderLayout: "fullwidth",
                dottedOverlay: "none",
                delay: 9000,
                navigation: {
                    keyboardNavigation:"off",
                    keyboard_direction: "horizontal",
                    mouseScrollNavigation:"off",
                    mouseScrollReverse:"default",
                    onHoverStop:"off",
                    touch:{
                        touchenabled:"on",
                        touchOnDesktop:"off",
                        swipe_threshold: 75,
                        swipe_min_touches: 50,
                        swipe_direction: "horizontal",
                        drag_block_vertical: false
                    }
                    ,
                    arrows: {
                        style:"uranus",
                        enable:true,
                        hide_onmobile:true,
                        hide_under:778,
                        hide_onleave:true,
                        hide_delay:200,
                        hide_delay_mobile:1200,
                        tmp:'',
                        left: {
                            h_align:"left",
                            v_align:"center",
                            h_offset:20,
                            v_offset:0
                        },
                        right: {
                            h_align:"right",
                            v_align:"center",
                            h_offset:20,
                            v_offset:0
                        }
                    }
                },
                responsiveLevels: [1240, 1024, 778, 480],
                visibilityLevels: [1240, 1024, 778, 480],
                gridwidth: [1240, 1024, 778, 480],
                gridheight: [700, 550, 860, 480],
                lazyType: "none",
                parallax: {
                    type: "mouse",
                    origo: "slidercenter",
                    speed: 2000,
                    levels: [2, 3, 4, 5, 6, 7, 12, 16, 10, 50, 47, 48, 49, 50, 51, 55],
                    disable_onmobile: "on"
                },
                shadow: 0,
                spinner: "off",
                stopLoop: "on",
                stopAfterLoops: 0,
                stopAtSlide: 1,
                shuffle: "off",
                autoHeight: "off",
                disableProgressBar: "on",
                hideThumbsOnMobile: "off",
                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                debugMode: false,
                fallbacks: {
                    simplifyAll: "off",
                    nextSlideOnWindowFocus: "off",
                    disableFocusListener: false,
                }
            });
        }
    }); /*ready*/
</script>




</body>

</html>