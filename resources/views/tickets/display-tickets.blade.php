@extends('tickets.layouts')

@section('content')
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
	<!-- //the ticket -->
	<div class="ticket-position">
		<div class="tickets-content">
			<h1>Event Name</h1>
			<img class="tickets-qr" src="qr.png" alt="qr code here">
			<div class="ticket-info">
				
				<p >Venue:Juja, Kenya</p>
				<p >Date:6th November</p>
				<p >Time:6 p.m to 8 p.m</p>
				<p >Category:6 p.m to 8 p.m</p>
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
			<div class="ticket-end">
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
			</div>
			<!-- End of code that goes -->
		</div>

		<div>
			<p class="ticket-powered-by">Powered by FIKA Places <img class="ticket-sponsors" src="FikaIconWhite120x120.jpg"></p>
		</div>
	</div>
</body>
</html>
@endsection

