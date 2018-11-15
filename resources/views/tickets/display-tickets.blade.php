<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="{{ asset('css/tickets-style.css') }}">
	{{--    @yield('styles')--}}
</head>
<body>
{{--@section('content')--}}
<!-- //the ticket -->
<div class="ticket-position">
	<div class="tickets-content">
		<h1>{{$event_name}}</h1>
		<img class="tickets-qr" src="{{url('bought_tickets/qr_code_images/'.$ticket_qr_code_image_name)}}" alt="qr code here">
		<div class="ticket-info">

			<p>Venue:{{$event_location}}</p>
			<p>Date:{{$event_start_date_time}} - {{$event_end_date_time}}</p>
			{{--<p>Time:6 p.m to 8 p.m</p>--}}
			<p>Category:{{$ticket_type }}</p>
		</div>
{{-- please uncomment this code when using real variaables
                                                @if($event->sponsor_images)
                                                        <div class="ticket-end">
                                                                <div class="container-fluid">
                                                                        <div class="row">
                                                                                @foreach($event->sponsor_images as $sponsor_image)
                                                                                <div class="col">
                                                                                        <img class="ticket-sponsors" src="event">
                                                                                </div>
                                                                                @endforeach
                                                                        </div>
                                                                </div>
                                                        </div>
                                                @endif --}}
	<!-- This code goes when the real variables come in -->
		<!-- <div class="ticket-end">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm">
						<img class="ticket-sponsors" src="fika.jpg" alt="sponsors image here">
					</div>
					<div class="col-sm">
						<img class="ticket-sponsors" src="fika.jpg" alt="sponsors image here">
					</div>
					<div class="col-sm">
						<img class="ticket-sponsors" src="fika.jpg" alt="sponsors image here">
					</div>
					<div class="col-sm">
						<img class="ticket-sponsors" src="fika.jpg" alt="sponsors image here">
					</div>
				</div>
			</div>
		</div> -->
		<!-- End of code that goes -->
	</div>
</div>
<div class="row">
	<p class="ticket-powered-by">Powered by FIKA Places <img class="ticket-sponsors" src="FikaIconWhite120x120.jpg"></p>
</div>
</body>
</html>


