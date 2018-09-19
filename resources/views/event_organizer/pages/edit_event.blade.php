@extends('common_pages.layouts')

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Edit Event</h1>
          <p>Edit event details below</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_verified_free_events') }}">Events</a></li>
          <li class="breadcrumb-item"><a href="{{ route('edit_event') }}">Edit</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" autocomplete="off" action="{{ route('edit_event_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label">Event name</label>
                            <input class="form-control" type="text" name="name" value="{{ $event->name }}" placeholder="Input name of the event"  required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label">Description</label>
                            <textarea class="form-control" placeholder="The event decription here" id="description" name="description" rows="7" required>{{ $event->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                      <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Event Start</label>
                            <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                <input class="form-control" type="text" id="start_date" name="start_date" value="{{ $event_date->start_date}}" placeholder="Select start date"  required>
                                @if ($errors->has('start_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                                <input class="form-control" type="text" id="start_time" name="start_time" value="{{ $event_date->end_date}}" placeholder="Select start time"  required>
                                @if ($errors->has('start_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start_time') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="form-group{{ $errors->has('stop_date') ? ' has-error' : '' }}">
                                <label class="control-label">Event Stop</label>
                                <input class="form-control" type="text" id="stop_date" name="stop_date" value="{{ $event_date->end_date}}" placeholder="Select stop date"  required>
                                @if ($errors->has('stop'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stop_date') }}</strong>
                                    </span>
                                @endif
                            </div> 
                            <div class="form-group{{ $errors->has('stop_time') ? ' has-error' : '' }}">
                                <input class="form-control" type="text" id="stop_time" name="stop_time" value="{{ $event_date->end_time}}" placeholder="Select stop time"  required autofocus>
                                @if ($errors->has('stop_time'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stop_time') }}</strong>
                                    </span>
                                @endif
                            </div>                           
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label for="location">Location</label>
                            <input type="text" class="form-control" aria-describedby="LocationHelp" value="{{ $event->location}}" name="location" id="location-address" aria-describedby="locationHelp" data-latitude-input="#location-lat" data-longitude-input="#location-lon" placeholder="The name of the venue" required>
                           
                            @if ($errors->has('location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                            @endif
                            
                            <small class="form-text text-muted" id="LocationHelp">Type the name or click the location on the map.</small> 
                        </div>  
                        <div class="form-group">
                          <div id="location" style="height: 400px;"></div>
                          <input type="hidden" class="form-control" value="{{ $event->latitude}}" name="latitude" style="width: 110px" id="location-lat" />
                          <input type="hidden" class="form-control" name="longitude" value="{{ $event->longitude}}" style="width: 110px" id="location-lon" />
                        </div>                                                      
                        
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                          <label for="image">Image</label>                                    
                          <div id="hidden">
                              <br>
                          <img id="blah" src="{{ asset('storage/images/events') }} {{'/'.$event_sponsor_media->media_url}}" width="565"><br><br>
                          <div class="input-group">
                              <div class="custom-file">
                                  <input type="file" aria-describedby="ImageHelp" name="image" onchange="readURL(this);" class="custom-file-input" id="image">
                                  <label class="custom-file-label" for="image">Click to choose different image</label>                                                
                              </div>
                              <div class="invalid-feedback">
                                  Please select an image.
                              </div>
                          </div>   
                          @if ($errors->has('image'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('image') }}</strong>
                              </span>
                          @endif                         
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                          <label class="control-label">Event type</label>
                          <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                            <div class="animated-radio-button form-check-inline">
                              <label>
                                <input type="radio" value="1" @if($event->type==1) checked @endif name="type" required><span class="label-text">Free</span>
                              </label>
                            </div>
                            <div class="animated-radio-button form-check-inline">
                              <label>
                                <input type="radio" value="2" @if($event->type==2) checked @endif name="type" required><span class="label-text">Paid</span>
                              </label>
                            </div>
                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="row" id="amount-row">                    
                    <div class="col-md-4">
                      <div class="form-group">
                        <label class="control-label">Amount</label>
                        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                          <label class="sr-only" for="exampleInputAmount">Amount (in shillings)</label>
                          <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">Ksh</span></div>
                            <input class="form-control" id="amount" value="@if($event->type==2){{$event_price->price}}@endif" name="amount" type="number" min="1" placeholder="Amount to be paid">
                            <div class="input-group-append"><span class="input-group-text">.00</span></div>
                          </div>
                          @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('amount') }}</strong>
                                </span>
                            @endif
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group{{ $errors->has('tickets') ? ' has-error' : '' }}">
                          <label class="control-label">No. of tickets</label>
                          <input class="form-control" type="number" id="tickets" name="tickets" value="@if($event->type==2){{$event->no_of_tickets}}@endif" placeholder="Maximum number of tickets">
                          @if ($errors->has('tickets'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('tickets') }}</strong>
                              </span>
                          @endif
                      </div>    
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                          <label class="control-label">Category</label>
                          <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">
                            <div class="animated-radio-button form-check-inline">
                              <label>
                                <input type="radio" value="1" @if($event->type==2 && $paid_event_category->category==1) checked @endif name="category" id="regular"><span class="label-text">Regular</span>
                              </label>
                            </div>
                            <div class="animated-radio-button form-check-inline">
                              <label>
                                <input type="radio" value="2" @if($event->type==2 && $paid_event_category->category==2) checked @endif name="category" id="vip"><span class="label-text">VIP</span>
                              </label>
                            </div>
                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('category') }}</strong>
                                </span>
                            @endif
                          </div>
                      </div>
                    </div>
                  </div>
                  <input type="hidden" name="id" value="{{$event->id}}">  
                  <input type="hidden" name="previous_image_url" value="{{$event_sponsor_media->media_url}}">                  
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
<script src="{{ asset('js/plugins/jquery.placepicker.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-timepicker.min.js') }}"></script>

@if ($event->type==1)
  <script>
    $(document).ready(function() {
      //only hide if its free event
      $("#amount-row").hide();
    });
  </script>    
@endif

<script>
  function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#blah')
                .attr('src', e.target.result)
                .width(400);            
            $("#hidden").slideDown("slow");
        };

        reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).ready(function() {
           
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

    $('#start_date,#stop_date').datepicker({
      	format: "dd/mm/yyyy",
      	autoclose: true,
      	todayHighlight: true
      });
    $('#start_time,#stop_time').timepicker({
      defaultTime : false,
      icons: {
                up: 'fa fa-angle-up',
                down: 'fa fa-angle-down'
            }
    });

    $(document).ready(function(){
        $('input[type=radio][name=type]').change(function() {
            if (this.value == '2') {
                $("#amount-row").slideDown("slow");
                $('#amount').attr('required','required');
                $('#regular').attr('required','required');                
                $('#vip').attr('required','required');
            }else {                
                $("#amount-row").slideUp("slow");
                $('#amount').removeAttr('required');
                $('#regular').removeAttr('required');
                $('#vip').removeAttr('required');
            }
        });
     });

  }); 

</script>
@endsection