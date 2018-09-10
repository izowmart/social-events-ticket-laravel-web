@extends('admin.layouts')

@section('other-styles')
<style>
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
</style>
    
@endsection

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Add Venue</h1>
          <p>Fill the form below to add a new venue</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('venues') }}">Venues</a></li>
          <li class="breadcrumb-item"><a href="{{ route('add_venue') }}">Add</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('add_venue_post') }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="location">Venue name</label>
                            <input type="text" class="form-control" aria-describedby="VenueHelp" name="venue_name" id="location-address" aria-describedby="locationHelp" data-latitude-input="#location-lat" data-longitude-input="#location-lon" placeholder="The name of the venue" required>
                            <input type="hidden" class="form-control" name="latitude" style="width: 110px" value="" id="location-lat" />                                          
                            <input type="hidden" class="form-control" name="longitude" style="width: 110px" id="location-lon" value="" />
                            @if ($errors->has('venue_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('venue_name') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('latitude'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('latitude') }}</strong>
                                </span>
                            @endif
                            @if ($errors->has('longitude'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('longitude') }}</strong>
                                </span>
                            @endif
                            <small class="form-text text-muted" id="VenueHelp">Make sure you choose the venue from the dropdown suggestions.</small> 
                        </div>                                                        
                        
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label">Town</label>
                          <div class="form-group{{ $errors->has('town_id') ? ' has-error' : '' }}">
                            <select aria-describedby="townHelp" name="town_id" class="form-control" id="town_select" autofocus required>
                              @foreach ($towns as $town)
                              <option value="">Select town of the venue</option>
                              <option value="{{$town->id}}">{{$town->name}}</option>                                    
                              @endforeach                                
                            </select>
                            @if ($errors->has('town_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('town_id') }}</strong>
                                </span>
                            @endif
                            <small class="form-text text-muted" id="townHelp">This only displays towns that are added to the system.</small>
                          </div>
                      </div>                      
                    </div> 
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Contact person name</label>
                        <input type="text" class="form-control" placeholder="Name of the person to be contact" name="contact_person_name" required/>  
                        @if ($errors->has('contact_person_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_name') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Contact person phone</label>
                        <input type="tel" class="form-control" maxlength="13" placeholder="Phone of the person to be contact" name="contact_person_phone" required/>  
                        @if ($errors->has('contact_person_phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_phone') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                  </div>
                  <div class="row"> 
                    <div class="col-md-6">                   
                      <div class="form-group">
                        <label>Contact person email</label>
                        <input type="email" class="form-control" placeholder="Email of the person to be contact" name="contact_person_email" required/> 
                        @if ($errors->has('contact_person_email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_email') }}</strong>
                            </span>
                        @endif 
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
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=true&libraries=places&key=AIzaSyBO5Else2rW4UNyXiCMp3y20JV7BseTMys"></script>
<script src="https://cdn.jsdelivr.net/npm/places.js@1.10.0"></script>
<script src="{{ asset('js/plugins/jquery.placepicker.js') }}"></script>
<script>

  $(document).ready(function() {

    // Advanced usage
    $("#location-address").each(function() {
      var target = this;
      var $map = $('#location');

      var placepicker = $(this).placepicker({
        map: $map.get(0),
        placeChanged: function(place) {
          //console.log("place changed: ", place.formatted_address, this.getLocation());
        }
      }).data('placepicker');
    });

  }); 

</script>

@endsection