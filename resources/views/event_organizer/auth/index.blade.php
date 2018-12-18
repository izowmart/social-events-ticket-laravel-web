<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Fika allows you to see and contact venues near you, get updated on the latest events, buy tickets to those events and benefit from offers only available for fika users">
    <meta name="author" content="Agoya">
    <title>Fika Places | Login</title>

    <!-- Favicons-->
    <link rel="shortcut icon" href="{{url('favicon.ico')}}" type="image/x-icon">
    <link rel="apple-touch-icon" type="image/x-icon" href="{{url('apple-touch-icon-57x57-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{url('apple-touch-icon-72x72-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{url('apple-touch-icon-114x114-precomposed.png')}}">
    <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{url('apple-touch-icon-144x144-precomposed.png')}}">

    <!-- COMMON CSS -->
    <link href="{{url('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{url('css/home/style.css')}}" rel="stylesheet">
    <link href="{{'css/home/vendors.css'}}" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link href="{{url('css/home/custom.css')}}" rel="stylesheet">

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

    @include('partials.mobile_home_menu')
</header><!-- End Header -->

<main>
    <section id="hero" class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                    <div id="login">
                        <div class="text-center"><img src="{{url('images/home/logo_sticky.png')}}" alt="Image" data-retina="true" ></div>
                        <hr>
                        <form>
                            {{--<a href="#0" class="social_bt facebook">Login with Facebook</a>--}}
                            {{--<a href="#0" class="social_bt google">Login with Google</a>--}}
                            {{--<div class="divider"><span>Or</span></div>--}}
                            <div class="form-group">
                                <label>Username</label>
                                <input type="text" class=" form-control " placeholder="Username">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" class=" form-control" placeholder="Password">
                            </div>
                            <p class="small">
                                <a href="forgot.html">Forgot Password?</a>
                            </p>
                            <a href="#" class="btn_full">Sign in</a>
                            <a href="register.html " class="btn_full_outline">Register</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- End main -->

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
                    <li><a href="#">About us</a></li>
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
            <a href="#0" class="social_bt facebook">Login with Facebook</a>
            <a href="#0" class="social_bt google">Login with Google</a>
            <div class="divider"><span>Or</span></div>
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
<script src="js/jquery-2.2.4.min.js"></script>
<script src="js/common_scripts_min.js"></script>
<script src="js/functions.js"></script>


</body>

</html>