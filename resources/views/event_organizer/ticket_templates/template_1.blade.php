<div class="ticket-template">
	<div class="ticket-position">
		<div class="tickets-content">
			<h1>{{$event->name}}</h1>
			<img class="tickets-qr" src="{{asset('qr.png')}}" alt="qr code here">
			<div class="ticket-info">
	
				<p><span>Venue:</span> {{$event->location}}</p>
				<p><span>Date:</span> {{date("m/d/Y", strtotime($event_dates->first()->start))}} - {{date("m/d/Y", strtotime($event_dates->first()->end))}}</p>
				<p><span>Time:</span> {{date("g:i a", strtotime($event_dates->first()->start))}} - {{date("g:i a", strtotime($event_dates->first()->end))}}</p>
				<p><span>Category:</span> @if ($event->type==1)
					Free
				@else
					Paid
				@endif</p>
				<p><span>No:</span> #12345678</p>
			</div>
		@if($event->sponsor_media)
		<div class="ticket-end">
			<div class="container-fluid">
				<div class="row">
					@foreach($event->sponsor_media as $sponsor_image)
					<div class="col">
						<img class="ticket-sponsors" src="{{ asset('storage/images/event_sponsors/'.$sponsor_image->media_url) }}">
					</div>
					@endforeach
				</div>
			</div>
		</div>
		@endif
		</div>
	</div>
	<div class="text-center">
		<p>Powered by FIKA Places
			<img class="rounded" src="{{ asset('FikaIconWhite120x120.jpg') }}" alt="logo" style="height: 5%; width: 5%"></p>
	</div>
</div>


