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
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <form class="login-form" method="POST" action="{{ route('event_organizer_reset_form_post') }}">
                                {{ csrf_field() }}
                                <input type="hidden" name="token" value="{{ $token }}">
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

                                <div class="text-center"><input type="submit" value="Reset Password" class="btn_1"></div>

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
      {{--<div class="reset-box">--}}
        {{--@if (session('status'))--}}
            {{--<div class="alert alert-success">--}}
                {{--{{ session('status') }}--}}
            {{--</div>--}}
        {{--@endif --}}
        {{--<form class="login-form" method="POST" action="{{ route('event_organizer_reset_form_post') }}">--}}
           {{--{{ csrf_field() }}--}}

            {{--<input type="hidden" name="token" value="{{ $token }}">--}}
          {{--<h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>RESET PASSWORD</h3>--}}
          {{--<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">--}}
            {{--<label class="control-label">Email</label>--}}
            {{--<input class="form-control" type="text" name="email" placeholder="Input email address" value="{{ old('email') }}" required autofocus>--}}
            {{--@if ($errors->has('email'))--}}
                {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('email') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}
          {{--</div>--}}
          {{--<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">--}}
            {{--<label class="control-label">Password</label>--}}
            {{--<input class="form-control" type="password" name="password" placeholder="Input password" required>--}}
            {{--@if ($errors->has('password'))--}}
                {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('password') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}
          {{--</div>--}}
          {{--<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">--}}
            {{--<label class="control-label">Confirm Password</label>--}}
            {{--<input class="form-control" type="password" name="password_confirmation" placeholder="Confirm password" required>--}}
            {{--@if ($errors->has('password_confirmation'))--}}
                {{--<span class="help-block">--}}
                    {{--<strong>{{ $errors->first('password_confirmation') }}</strong>--}}
                {{--</span>--}}
            {{--@endif--}}
          {{--</div>--}}
          {{--<div class="form-group btn-container">--}}
            {{--<button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SUBMIT</button>--}}
          {{--</div>--}}
        {{--</form>--}}
      {{--</div>--}}
    {{--</section>--}}
{{--@endsection--}}
