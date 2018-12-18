@extends('home.layout')
@section('content')
    <main>
        <section id="hero" class="login">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                        <div id="login">
                            <div class="text-center"><img src="{{url('images/home/logo_sticky.png')}}" alt="Image" data-retina="true" ></div>
                            <hr>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form class="forget-form" method="POST" action="{{ route('event_organizer_email_form_post') }}">
                                {{ csrf_field() }}
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label">Email</label>
                                <input class="form-control" name="email" type="email" value="{{ old('email') }}" placeholder="Input your email address" required>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                                </div>
                                <p>You will receive an email containing a link allowing you to reset your password to a new preferred one.</p>
                                <div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>
                                <hr>
                                <br>
                                <div class="text-center">
                                    <a href="{{ route('event_organizer_login_form') }}">Back to Login</a>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
            </div>
        </section>
    </main><!-- End main -->
@endsection
{{--@extends('common_pages.layouts')--}}

{{--@section('title')--}}
    {{--<title>Event Organizer Reset Password - Fika Places</title>--}}
{{--@endsection--}}

{{--@section('content')--}}
    {{--<section class="material-half-bg">--}}
      {{--<div class="cover"></div>--}}
    {{--</section>--}}
    {{--<section class="login-content">--}}
      {{--<div class="logo">--}}
        {{--<h1>Reset Password</h1>--}}
      {{--</div>--}}
      {{--<div class="email-box">--}}
        {{--@if (session('status'))--}}
            {{--<div class="alert alert-success">--}}
                {{--{{ session('status') }}--}}
            {{--</div>--}}
        {{--@endif        --}}
        {{--<form class="forget-form" method="POST" action="{{ route('event_organizer_email_form_post') }}">--}}
          {{--{{ csrf_field() }}--}}
          {{--<h3 class="login-head">--}}
            {{--<i class="fa fa-lg fa-fw fa-lock"></i><br>--}}
            {{--Event Organizer--}}
          {{--</h3>--}}
          {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
            {{--<label class="control-label">EmailL</label>--}}
            {{--<input class="form-control" name="email" type="email" value="{{ old('email') }}" placeholder="Input your email address" required>--}}
            {{--@if ($errors->has('email'))--}}
                {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('email') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}
          {{--</div>--}}
          {{--<div class="form-group btn-container">--}}
            {{--<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-unlock fa-lg fa-fw"></i>RESET</button>--}}
          {{--</div>--}}
          {{--<div class="form-group mt-3">--}}
            {{--<p class="semibold-text mb-0"><a href="{{ route('event_organizer_login_form') }}" data-toggle="flip"><i class="fa fa-angle-left fa-fw"></i> Back to Login</a></p>--}}
          {{--</div>--}}
        {{--</form>--}}
      {{--</div>--}}
    {{--</section>--}}
{{--@endsection--}}

{{--<script type="text/javascript">--}}
    {{--$('.login-box').toggleClass('flipped');   --}}
{{--</script>--}}
