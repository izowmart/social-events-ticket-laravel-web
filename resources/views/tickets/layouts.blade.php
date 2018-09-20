<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @yield('title')
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('css/tickets-style.css') }}">    
</head>
<body>
<nav class="navbar navbar-expand-sm nav-color navbar-dark">
  <!-- Brand -->
  <a class="navbar-brand" href="#">FIKA</a>

  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('tickets_home') }}">Home</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Event Organizer</a>
    </li>
  </ul>
</nav>
    @yield('content')

<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
</body>
</html>