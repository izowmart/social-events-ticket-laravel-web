@extends('home.layout')

{{--@section('title')--}}
    {{--<title>Event Organizer Register - Fika</title>--}}
{{--@endsection--}}

@section('content')
    <main>
        <section id="hero" class="login">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-4 col-lg-5 col-md-6 col-sm-8">
                        <div id="login">
                            <div class="text-center"><img src="{{url('images/home/logo_sticky.png')}}" alt="Image" data-retina="true" ></div>
                            <hr>
                            <form class="register-form" method="POST" action="{{ route('event_organizer_register_form_post') }}">
                                @csrf
                                {{--<div class="form-group">--}}
                                    {{--<label>First Name</label>--}}
                                    {{--<input type="text" class=" form-control"  placeholder="First Name">--}}
                                {{--</div>--}}
                                <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                                    <label class="control-label">First Name</label>
                                    <input class="form-control" type="text" name="first_name" placeholder="Input first name" value="{{ old('first_name') }}" required autofocus>
                                    @if ($errors->has('first_name'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                                <label class="control-label">Last Name</label>
                                <input class="form-control" type="text" name="last_name" placeholder="Input last name" value="{{ old('last_name') }}" required autofocus>
                                @if ($errors->has('last_name'))
                                <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                                @endif
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="control-label">Email</label>
                                <input class="form-control" type="text" name="email" placeholder="Input email address" value="{{ old('email') }}" required autofocus>
                                @if ($errors->has('email'))
                                <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                <label class="control-label">Password</label>
                                <input class="form-control" type="password" name="password" placeholder="Input password" required>
                                @if ($errors->has('password'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                                </div>
                                <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                <label class="control-label">Confirm Password</label>
                                <input class="form-control" type="password" name="password_confirmation" placeholder="Confirm password" required>
                                @if ($errors->has('password_confirmation'))
                                <span class="help-block">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                                </div>
                                <div id="pass-info" class="clearfix"></div>
                                <button type="submit" class="btn_full"><i class="fa fa-sign-in fa-lg fa-fw"></i>Create an account</button>
                                <div class="text-center">
                                    Have an account already? <a href="{{ route('event_organizer_login_form') }}">Log In</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End main -->

    {{--<section class="material-half-bg">--}}
      {{--<div class="cover"></div>--}}
    {{--</section>--}}
    {{--<section class="login-content">--}}
      {{--<div class="logo">--}}
        {{--<h1>Register</h1>--}}
      {{--</div>--}}
      {{--<div class="register-box">--}}
        {{--@if (session('status'))--}}
            {{--<div class="alert alert-success">--}}
                {{--{{ session('status') }}--}}
            {{--</div>--}}
        {{--@endif   --}}
        {{--<form class="register-form" method="POST" action="{{ route('event_organizer_register_form_post') }}">--}}
           {{--@csrf--}}
          {{--<h3 class="register-head">--}}
              {{--<i class="fa fa-lg fa-fw fa-user"></i><br>--}}
              {{--Event Organizer--}}
          {{--</h3>--}}
          {{--<div class="row">--}}
            {{--<div class="col-md-6">--}}
                {{--<div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">--}}
                    {{--<label class="control-label">First Name</label>--}}
                    {{--<input class="form-control" type="text" name="first_name" placeholder="Input first name" value="{{ old('first_name') }}" required autofocus>--}}
                    {{--@if ($errors->has('first_name'))--}}
                        {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('first_name') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-md-6">                  --}}
                {{--<div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">--}}
                    {{--<label class="control-label">Last Name</label>--}}
                    {{--<input class="form-control" type="text" name="last_name" placeholder="Input last name" value="{{ old('last_name') }}" required autofocus>--}}
                    {{--@if ($errors->has('last_name'))--}}
                        {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('last_name') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
            {{--</div>--}}
           {{--</div>--}}
           {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
            {{--<label class="control-label">Email</label>--}}
            {{--<input class="form-control" type="text" name="email" placeholder="Input email address" value="{{ old('email') }}" required autofocus>--}}
            {{--@if ($errors->has('email'))--}}
                {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('email') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}
          {{--</div>--}}
          {{--<div class="row">--}}
              {{--<div class="col-md-6">--}}
                {{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
                    {{--<label class="control-label">Password</label>--}}
                    {{--<input class="form-control" type="password" name="password" placeholder="Input password" required>--}}
                    {{--@if ($errors->has('password'))--}}
                        {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('password') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
              {{--</div>--}}
              {{--<div class="col-md-6">--}}
                {{--<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">--}}
                    {{--<label class="control-label">Confirm Password</label>--}}
                    {{--<input class="form-control" type="password" name="password_confirmation" placeholder="Confirm password" required>--}}
                    {{--@if ($errors->has('password_confirmation'))--}}
                        {{--<span class="help-block">--}}
                            {{--<strong>{{ $errors->first('password_confirmation') }}</strong>--}}
                        {{--</span>--}}
                    {{--@endif--}}
                {{--</div>--}}
              {{--</div>--}}
          {{--</div>--}}
          {{--<div class="form-group btn-container">--}}
            {{--<center>--}}
                {{--<button type="submit" class="btn btn-primary btn-block col-md-6"><i class="fa fa-sign-in fa-lg fa-fw"></i>SUBMIT</button>--}}
            {{--</center>--}}
          {{--</div><br>--}}
          {{--<div class="form-group">--}}
            {{--<div class="utility">--}}
              {{--<p class="semibold-text mb-2"><a href="{{ route('event_organizer_login_form') }}">Already have an account?</a></p>--}}
            {{--</div>--}}
          {{--</div>--}}
          {{----}}
        {{--</form>--}}
      {{--</div>--}}
    {{--</section>--}}
@endsection
