@extends('common_pages.layouts')

@section('other-styles')
<style>
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
</style>
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Edit Venue</h1>
          <p>Edit the fields below to update the venue</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('venues') }}">Venues</a></li>
          <li class="breadcrumb-item"><a href="{{ route('edit_venue',['slug'=>$venue->slug]) }}">Edit</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('edit_venue_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="location">Venue name</label>
                            <input type="text" class="form-control" aria-describedby="VenueHelp" value="{{$venue->venue_name}}" name="venue_name" id="location-editress" aria-describedby="locationHelp" data-latitude-input="#location-lat" data-longitude-input="#location-lon" placeholder="The name of the venue" autofocus required>
                           
                            @if ($errors->has('venue_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('venue_name') }}</strong>
                                </span>
                            @endif
                            
                            <small class="form-text text-muted" id="VenueHelp">Type the name or click the location on the map.</small> 
                        </div>  
                        <div class="form-group">
                          <div id="location" style="height: 400px;"></div>
                          <input type="hidden" class="form-control" name="latitude" style="width: 110px" id="location-lat" />
                          <input type="hidden" class="form-control" name="longitude" style="width: 110px" id="location-lon" />                          
                          <input type="hidden" name="id" value="{{$venue->venue_id}}" />
                        </div>                                                      
                        
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label">Town</label>
                          <div class="form-group{{ $errors->has('town_id') ? ' has-error' : '' }}">
                            <select aria-describedby="townHelp" name="town_id" class="form-control" id="town_select" required>
                              <option value="{{$venue->town_id}}">{{$venue->town_name}}</option>
                              @foreach ($towns as $town)
                              <option value="{{$town->id}}">{{$town->name}}</option>                                    
                              @endforeach                                
                            </select>
                            @if ($errors->has('town_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('town_id') }}</strong>
                                </span>
                            @endif
                            <small class="form-text text-muted" id="townHelp">This only displays towns that are edited to the system.</small>
                          </div>
                      </div>                      
                    </div> 
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Contact person name</label>
                        <input type="text" class="form-control" value="{{$venue->contact_person_name}}" placeholder="Name of the person to be contact" name="contact_person_name" required/>  
                        @if ($errors->has('contact_person_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_name') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    
                  </div>
                  <div class="row"> 
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Contact person phone</label>
                        <input type="tel" class="form-control" value="{{$venue->contact_person_phone}}" maxlength="13" placeholder="Phone of the person to be contact" name="contact_person_phone" required/>  
                        @if ($errors->has('contact_person_phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_phone') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6">                   
                      <div class="form-group">
                        <label>Contact person email</label>
                        <input type="email" class="form-control" value="{{$venue->contact_person_email}}" placeholder="Email of the person to be contact" name="contact_person_email" required/> 
                        @if ($errors->has('contact_person_email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_email') }}</strong>
                            </span>
                        @endif 
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group">
                        <label>Image of the venue (optional)</label>
                        <input type="file" class="form-control-file" name="venue_image" value="{{ $venue->venue_image}}" >
                      </div>
                    </div> 
                  </div>
                  <div class="tile-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>

              </form>              
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('other-scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=true&libraries=places&key=AIzaSyAWMiw7tAqWan2iOUqRzzM2BGQ9z6Pe8wI"></script>
<script src="https://cdn.jsdelivr.net/npm/places.js@1.10.0"></script>
<script src="{{ asset('js/plugins/jquery.placepicker.js') }}"></script>
<script>

  $(document).ready(function() {

    // Advanced usage
    $("#location-editress").each(function() {
      var target = this;
      var $map = $('#location');

      var placepicker = $(this).placepicker({
        map: $map.get(0),
        placeChanged: function(place) {
          //console.log("place changed: ", place.formatted_editress, this.getLocation());
          var latitude = this.getLocation().latitude;
          var longitude = this.getLocation().longitude;
          $('#location-lat').val(latitude);
          $('#location-lon').val(longitude);
        }
      }).data('placepicker');
    });

  }); 

</script>

@endsection