@extends('common_pages.layouts')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/summernote-bs4.css') }}" />   
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datetimepicker-standalone.min.css') }}" /> 
<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-select.min.css') }}" /> 
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
                  <div id="date_fields" class=" m-t-20"></div>
                  <div class="row">
                    <div class="col-md-10">
                      <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Event Start</label>
                            <div class="form-group{{ $errors->has('start') ? ' has-error' : '' }}">
                                <div class='input-group date'>
                                    <input class="form-control datetimepicker" type="text" name="dates[0][start]" value="{{ old('start') }}" placeholder="Select start date"  required>
                                   
                                    @if ($errors->has('start'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('start') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-4">
                            <div class="form-group{{ $errors->has('stop') ? ' has-error' : '' }}">
                                <label class="control-label">Event Stop</label>
                                <input class="form-control datetimepicker" type="text" name="dates[0][stop]" value="{{ old('stop') }}" placeholder="Select stop date"  required>
                                @if ($errors->has('stop'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stop') }}</strong>
                                    </span>
                                @endif
                            </div>                          
                        </div>
                        <div class="col-sm-1 pt-4">
                            <div class="form-group">
                                <button class="btn btn-primary" style="text-align: center" type="button" onclick="date_fields();"><i class="fa fa-plus"></i></button>
                            </div>
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
                  <div id="paid-row">
                    <div class="row" id="category-row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">    
                                <select class="selectpicker" id="category" name="category[]" multiple>
                                    @foreach ($ticket_categories as $ticket_category)
                                        <option value="{{$ticket_category->id}}">{{$ticket_category->name}}</option>                                        
                                    @endforeach
                                </select>
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                      </div>
                    </div>
                    <div id="append-row">
                    
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
                              <img id="blah" src="" height="400""><br><br>
                          </div>
                      </div>
                    </div>
                  </div>
                  <div class="container">
          
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
<script src="{{ asset('js/plugins/moment.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-datetimepicker.min.js') }}"></script>
<script src="{{ asset('js/plugins/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('js/plugins/bootstrap-select.min.js') }}"></script>
<script>    
    var room = 0;

    function date_fields() {

        room++;
        var objTo = document.getElementById('date_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + room);
        var rdiv = 'removeclass' + room;
        divtest.innerHTML = '<div class="row"> <div class="col-md-10"> <div class="row"> <div class="col-md-4"> <label class="control-label">Event Start</label> <div class="form-group"> <div class="input-group" date> <input class="form-control datetimepicker" type="text" name="dates['+room+'][start]" placeholder="Select start date" required> </div> </div> </div> <div class="col-md-3"></div> <div class="col-md-4"> <div class="form-group"> <label class="control-label">Event Stop</label> <input class="form-control datetimepicker" type="text" name="dates['+room+'][stop]" placeholder="Select stop date" required> </div> </div> <div class="col-sm-1 pt-4"> <div class="form-group"> <button class="btn btn-success" type="button" onclick="remove_date_fields(' + room + ');"><i class="fa fa-minus"></i></button> </div> </div> </div> </div> </div>';

        objTo.appendChild(divtest)

        $('.datetimepicker').datetimepicker({
            icons: {
                time: "fa fa-clock-o",
                date: "fa fa-calendar",
                up: "fa fa-arrow-up",
                down: "fa fa-arrow-down",
                previous: "	fa fa-angle-left",
                next: "	fa fa-angle-right"
            }
        });
    }

    function remove_date_fields(rid) {
        $('.removeclass' + rid).remove();
    }

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
                .height(400);            
            $("#hidden").slideDown("slow");
        };

        reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).ready(function() {
    var selectedItems = [];
    $("#hidden").hide();
    $("#category-row").hide();
    $("#append-row").hide();
    $('#category').selectpicker();
    $('#category').on('changed.bs.select', function (e, clickedIndex, isSelected, previousValue) {
        
        if (isSelected==true) {
             $('#category option:selected').each(function () {
                  var text = $(this).text();
                 if (!selectedItems.includes(text)) {
                    selectedItems.push(text);
                    var value = $(this).val();
                    //console.log("Clicked index: "+clickedIndex+"\tSelected value:"+value+"\tSelected text: "+text+"\t"+"\n");
                    appendRows(text);
                 }
             }); 
            
        }else{                       
            previousValue.forEach(Individual);
            function Individual(value) {
                var deselected_text = $('#category option').eq(clickedIndex).text();
                var name = deselected_text.toLowerCase().split(' ').join('_');
                var div = name+'_div';        

                for(var i = selectedItems.length - 1; i >= 0; i--) {
                    if(selectedItems[i] === deselected_text) {
                        selectedItems.splice(i, 1);
                        $('#'+div).slideUp("slow").remove();
                    }
                }         
                
            }

        }
        
    });
        
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

    $('.datetimepicker').datetimepicker({
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: "	fa fa-angle-left",
            next: "	fa fa-angle-right"
        }
    });

        $('input[type=radio][name=type]').change(function() {
            if (this.value == '2') {
                $("#category-row").slideDown("slow");
            }else {            
                $("#append-row").empty().slideUp("slow");  
                $('#category').selectpicker('deselectAll');
                $("#category-row").slideUp("slow");
            }
        });

  }); 

  function appendRows(category_name) {
      name = category_name.toLowerCase().split(' ').join('_');
      id = name+"_category".split(' ').join('_');
      label = category_name;
      var content = "<div class='row' id='"+name+"_div'><div class='col-md-4'><div class='form-group'><label class='control-label'>"+label+" Amount</label><div class='form-group'><label class='sr-only' for='exampleInputAmount'>Amount (in shillings)</label><div class='input-group'><div class='input-group-prepend'><span class='input-group-text'>Ksh</span></div><input class='form-control' id='"+id+"_amount' value='' name='"+name+"_amount' type='number' min='1' placeholder='Amount to be paid' required><div class='input-group-append'><span class='input-group-text'>.00</span></div></div></div></div></div><div class='col-md-3'><div class='form-group'><label class='control-label'>"+label+" No. of tickets</label><input class='form-control' type='number' id='"+id+"_tickets' name='"+name+"_tickets' value='' placeholder='Maximum number of tickets' required></div></div><div class='col-md-3'><label class='control-label'>"+label+" Ticket sale end date</label><div class='form-group'><div class='input-group date' id='datetimepicker1'><input class='form-control datetimepicker' type='text' id='"+id+"_ticket_sale_end_date' name='"+name+"_ticket_sale_end_date' value='' placeholder='Select date'  required></div></div></div></div>";     
      $("#append-row").append(content).slideDown("slow");
      $("#"+name+"_div").hide();
      $("#"+name+"_div").slideDown("slow");

      $('.datetimepicker').datetimepicker({
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down",
            previous: "	fa fa-angle-left",
            next: "	fa fa-angle-right"
        }
    });
  }

</script>
@endsection