<form method="POST" action="{{ route('event_organizer_login_form_post') }}">
    @csrf
    <div class="sign-in-wrapper">
        <div class="form-group{{ $errors->has('email') ? ' has-error ' : '' }}">
            <label class="custom-control-label" for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Input email address" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <label class="control-label" for="password">Password</label>
            <input class="form-control" type="password" name="password" placeholder="Input password" required>
            {{--<i class="icon_lock_alt"></i>--}}
            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
        <div class="clearfix add_bottom_15">
            <div class="checkboxes float-left">
                <input id="remember" type="checkbox" name="remember">
                <label for="remember">Remember Me</label>
            </div>
            <div class="float-right"><a id="forgot" href="{{ route('event_organizer_email_form') }}">Forgot Password?</a></div>
        </div>
        <div class="text-center"><input type="submit" value="Log In" class="btn_login"></div>
        <div class="text-center">
            Don’t have an account? <a href="{{ route('event_organizer_register_form') }}">Sign up</a>
        </div>
    </div>
</form>
