@extends('layouts.app')

@section('title')
    <title>Event Organizer Login - Fika</title>
@endsection

@section('content')
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        <h1>Login</h1>
      </div>
      <div class="login-box">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif 
        <form class="login-form" method="POST" action="{{ route('event_organizer_login_form_process') }}">
           @csrf
          <h3 class="login-head">
            <i class="fa fa-lg fa-fw fa-user"></i><br>
              Event Organizer
          </h3>
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
          <div class="form-group">
            <div class="utility">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox"><span class="label-text" name="remember" {{ old('remember') ? 'checked' : ''}}>Remember me</span>
                </label>
              </div>
              <p class="semibold-text mb-2"><a href="{{ route('event_organizer_email_form') }}">Forgot Password ?</a></p>
            </div>
          </div>
          <div class="form-group btn-container">
            <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
          </div><br>
          <div class="form-group">
            <div class="utility">
              <p class="semibold-text mb-2"><a href="{{ route('event_organizer_registration_form') }}">Don't have an account?</a></p>
            </div>
          </div>
        </form>
      </div>
    </section>
@endsection
