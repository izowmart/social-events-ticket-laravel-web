@extends('tickets.layouts')

@section('content')
<div class="row page-row">
    <div class="col-md-4">
        <div class="image-details-container">   
            <img src="{{ asset('storage/images/events') }}{{'/'.$event->media_url}}" width="400" alt="Event image">
        </div> 
    </div>
    <div class="col-md-8">
        <div class="details-content">            
            <h1 class="details-title">{{$event->name}}</h1>
            <p class="details-dates"><i class="fa fa-calendar-check-o"></i> {{date("jS M Y", strtotime($event->start_date))}}, {{date("g:i a", strtotime($event->start_time))}} - {{date("jS M Y", strtotime($event->end_date))}}, {{date("g:i a", strtotime($event->end_time))}}</p>
            <p class="details-location"><i style="font-size: 18px;" class="fa fa-map-marker"></i> {{$event->location}}</p><br>

            <p class="details-description">
                {{$event->description}}
            </p>
        </div>
    </div>         
</div>
<div class="row page-row">
    <div class="col-md-12">        
        <div id="map" style="width:100%;height:500px"></div>
        <input type="hidden" value="{{$event->latitude}}" id="latitude">
        <input type="hidden" value="{{$event->longitude}}" id="longitude">
    </div>
</div>
@endsection

@section('scripts')
<script>
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();

    function myMap() {
    var myCenter = new google.maps.LatLng(latitude,longitude);
    var mapCanvas = document.getElementById("map");
    var mapOptions = {center: myCenter, zoom: 15};
    var map = new google.maps.Map(mapCanvas, mapOptions);
    var marker = new google.maps.Marker({position:myCenter});
    marker.setMap(map);

    var infowindow = new google.maps.InfoWindow({
        content: "Event location"
    });
    infowindow.open(map,marker);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBO5Else2rW4UNyXiCMp3y20JV7BseTMys&callback=myMap"></script>  
@endsection