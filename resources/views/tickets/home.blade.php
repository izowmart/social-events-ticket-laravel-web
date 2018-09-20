@extends('tickets.layouts')

@section('content')
<div class="row container">
    <div class="col-md-4">
        <div class="card">
        <div class="image-container">        
            <img class="card-image" src="{{ asset('storage/images/events/event_1537356834.jpg') }}" alt="Event">
        </div>
        <div class="card-content">      
            <p class="title">Freshas night</p>
            <p class="description">Some text about description of the event</p>
            <p class="location">JKUAT Main campus juja</p>
            <p class="dates">25th sep 2018</p>
        </div>
        <button>Buy Now</button>
        </div>
    </div>
</div>
@endsection