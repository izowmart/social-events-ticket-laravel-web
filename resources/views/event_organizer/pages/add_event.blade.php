@extends('common_pages.layouts')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-timepicker.min.css') }}" />   
<link rel="stylesheet" type="text/css" href="{{ asset('css/summernote-bs4.css') }}" /> 
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Add Event</h1>
          <p>Fill the form below to add a new event</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_verified_free_events') }}">Events</a></li>
          <li class="breadcrumb-item"><a href="{{ route('add_event') }}">Add</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" autocomplete="off" action="{{ route('add_event_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label">Event name</label>
                            <input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Input name of the event"  required autofocus>
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
                            <textarea class="form-control summernote" placeholder="The event decription here" id="description" name="description" rows="7" required>{{ old('description') }}</textarea>
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
                                <input class="form-control" type="text" id="start_date" name="start_date" value="{{ old('start_date') }}" placeholder="Select start date"  required>
                                @if ($errors->has('start_date'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('start_time') ? ' has-error' : '' }}">
                                <input class="form-control" type="text" id="start_time" name="start_time" value="{{ old('start_time') }}" placeholder="Select start time"  required>
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
                                <input class="form-control" type="text" id="stop_date" name="stop_date" value="{{ old('stop_date') }}" placeholder="Select stop date"  required>
                                @if ($errors->has('stop'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stop_date') }}</strong>
                                    </span>
                                @endif
                            </div> 
                            <div class="form-group{{ $errors->has('stop_time') ? ' has-error' : '' }}">
                                <input class="form-control" type="text" id="stop_time" name="stop_time" value="{{ old('stop_time') }}" placeholder="Select stop time"  required autofocus>
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
                            <input type="text" class="form-control" aria-describedby="LocationHelp" value="Nairobi, Kenya" name="location" id="location-address" aria-describedby="locationHelp" data-latitude-input="#location-lat" data-longitude-input="#location-lon" placeholder="The name of the venue" required>
                           
                            @if ($errors->has('location'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                            @endif
                            
                            <small class="form-text text-muted" id="LocationHelp">Type the name or click the location on the map.</small> 
                        </div>  
                        <div class="form-group">
                          <div id="location" style="height: 400px;"></div>
                          <input type="hidden" class="form-control" value="-1.2920659" name="latitude" style="width: 110px" id="location-lat" />
                          <input type="hidden" class="form-control" value="36.8219462" name="longitude" style="width: 110px" id="location-lon" />
                        </div>                                                      
                        
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-md-10">
                      <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                          <label for="image">Image</label>
                          <div class="input-group">
                              <div class="custom-file">
                                  <input type="file" aria-describedby="ImageHelp" name="image" onchange="readURL(this);" class="custom-file-input" id="image" required>
                                  <label class="custom-file-label" for="image">Click to choose image</label>                                                
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
                          <small class="form-text text-muted" id="ImageHelp">Preview of the image will be shown below.</small>                                        
                          <div id="hidden">
                              <br>
                              <img id="blah" src="" width="565"><br><br>
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
                                <input type="radio" value="1" @if(old('type')) checked @endif name="type" required><span class="label-text">Free</span>
                              </label>
                            </div>
                            <div class="animated-radio-button form-check-inline">
                              <label>
                                <input type="radio" value="2" name="type" required><span class="label-text">Paid</span>
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
                            <input class="form-control" id="amount" value="{{ old('amount') }}" name="amount" type="number" min="1" placeholder="Amount to be paid">
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
                          <input class="form-control" type="number" id="tickets" name="tickets" value="{{ old('tickets') }}" placeholder="Maximum number of tickets">
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
                                <input type="radio" value="1" name="category" id="regular"><span class="label-text">Regular</span>
                              </label>
                            </div>
                            <div class="animated-radio-button form-check-inline">
                              <label>
                                <input type="radio" value="2" name="category" id="vip"><span class="label-text">VIP</span>
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
<script src="{{ asset('js/plugins/summernote-bs4.min.js') }}"></script>
<script>
    $('.summernote').summernote({
        height: 350, // set editor height
        minHeight: null, // set minimum height of editor
        maxHeight: null, // set maximum height of editor
        focus: false, // set focus to editable area after initializing summernote
        placeholder: 'Event description',
        toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear']],
        ['fontname', ['fontname']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link', 'hr']],
        ['view', ['fullscreen', ]],  
        ['help', ['help']]
        ]          
    });
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
    $("#amount-row").hide();
        
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
      	format: "yyyy-mm-dd",
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
                $('#tickets').attr('required','required');                  
                $('#vip').attr('required','required');
            }else {                
                $("#amount-row").slideUp("slow");
                $('#amount').removeAttr('required');
                $('#regular').removeAttr('required');
                $('#tickets').removeAttr('required');
                $('#vip').removeAttr('required');
            }
        });
     });

  }); 

</script>
@endsection