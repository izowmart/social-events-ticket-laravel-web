<h1>Hi, {{$name}}</h1>
<p>You were added as an admin on FIKA web portal. Your credentials are as follows:</p>

<p> Email: {{$email}}<br>
Password: {{$password}}</p>

Follow the link below to login<br>
<a href="{{ route('admin_login_form') }}">{{ route('admin_login_form') }}</a>