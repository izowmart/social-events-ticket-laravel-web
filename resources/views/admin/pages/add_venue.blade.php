@extends('common_pages.layouts') 

@section('title')
    <title>Add Venue - Admin Fika Places</title>
@endsection

@section('styles')
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
<link rel="stylesheet" type="text/css" href="{{ asset('css/venues.css') }}" />
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

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
              <form method="POST" action="{{ route('add_venue_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="location">Venue name</label>
                            <input type="text" class="form-control" aria-describedby="VenueHelp" value="Nairobi, Kenya" name="venue_name" id="location-address" aria-describedby="locationHelp" data-latitude-input="#location-lat" data-longitude-input="#location-lon" placeholder="The name of the venue" autofocus required>                           
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
                        </div>                                                      
                        
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label class="control-label">Town</label>
                          <div class="form-group{{ $errors->has('town_id') ? ' has-error' : '' }}">
                            <select aria-describedby="townHelp" name="town_id" class="form-control" id="town_select" required>                              
                              <option value="">Select town of the venue</option>
                              @foreach ($towns as $town)
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
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Contact person name</label>
                        <input type="text" class="form-control" placeholder="Name of the person to be contacted" name="contact_person_name" required/>  
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
                        <input type="tel" class="form-control" maxlength="13" placeholder="Phone of the person to be contact" name="contact_person_phone" required/>  
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
                        <input type="email" class="form-control" placeholder="Email of the person to be contact" name="contact_person_email" required/> 
                        @if ($errors->has('contact_person_email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('contact_person_email') }}</strong>
                            </span>
                        @endif 
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      {{--<div class="form-group">--}}
                        {{--<label>Image of the venue (optional)</label>--}}
                        {{--<input type="file" class="form-control-file" name="venue_image">--}}
                      {{--</div>--}}
                        <div class="form-group">
                            <label for="image">Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" aria-describedby="ImageHelp" name="venue_image" onchange="readURL(this);" class="custom-file-input" id="image" required>
                                    <label class="custom-file-label" for="venue_image">Click to choose image</label>
                                </div>
                                <div class="invalid-feedback">
                                    Please select an image.
                                </div>
                            </div>
                            <small class="form-text text-muted" id="ImageHelp">Preview of the image will be shown below.</small>
                            <div id="hidden">
                                <br>
                                <img id="blah" src="" width="565"><br><br>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <label for="featured" class="btn btn-link">Tick if venue is featured</label>
                      <input type="checkbox" value="1" id="featured" name="featured">
                      <input type="text" class="form-control featured_description" placeholder="Enter the description of the venue" name="featured_description">
                    </div>
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
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $("#hidden").slideDown("slow");
                $('#blah')
                    .attr('src', e.target.result)
                    .width(400);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
  $(document).ready(function() {

    // Advanced usage
    $("#location-address").each(function() {
      var target = this;
      var $map = $('#location');

      var placepicker = $(this).placepicker({
        map: $map.get(0),
        placeChanged: function(place) {
          //console.log("place changed: ", place.formatted_address, this.getLocation());
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