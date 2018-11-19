@extends('common_pages.layouts')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="{{ asset('css/summernote-bs4.css') }}" /> 
<link rel="stylesheet" href="{{ asset('css/slim.min.css') }}">
<script src="{{ asset('js/plugins/slim.jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/slim.kickstart.min.js') }}"></script>
@endsection

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
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_free_events') }}">Events</a></li>
          <li class="breadcrumb-item"><a href="{{ route('edit_event', ['slug'=>$event->slug]) }}">Edit</a></li>
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
                            <textarea class="form-control summernote" placeholder="The event decription here" id="description" name="description" rows="7" required>{{ $event->description }}</textarea>
                            @if ($errors->has('description'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div id="date_fields" class=" m-t-20"></div>
                  @foreach ($event_dates as $event_date)
                  <div class="row @if ($loop->last) @else {{'removeclass'.$event_date->id}} @endif
                  ">
                    <div class="col-md-10">
                      <div class="row">
                        <div class="col-md-4">
                            <label class="control-label">Event Start</label>
                            <div class="form-group{{ $errors->has('start') ? ' has-error' : '' }}">
                                <div class='input-group date'>
                                    <input class="form-control datetimepicker" type="text" name="dates[{{$event_date->id}}][start]" value="{{date('Y-m-d H:i',strtotime($event_date->start))}}" placeholder="Select start date"  required>
                                   
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
                                <input class="form-control datetimepicker" type="text" name="dates[{{$event_date->id}}][stop]" value="{{date('Y-m-d H:i',strtotime($event_date->end))}}" placeholder="Select stop date"  required>
                                @if ($errors->has('stop'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('stop') }}</strong>
                                    </span>
                                @endif
                            </div>                          
                        </div>
                        <div class="col-sm-1 pt-4">
                            <div class="form-group">
                                @if ($loop->last)
                                    <button class="btn btn-primary" style="text-align: center" type="button" onclick="date_fields();"><i class="fa fa-plus"></i></button>                 
                                @else                                    
                                    <button class="btn btn-success" type="button" onclick="remove_date_fields({{$event_date->id}});"><i class="fa fa-minus"></i></button>
                                @endif
                            </div>
                        </div>
                       </div>
                    </div>
                  </div>  
                  @endforeach                 
                  <div class="row">
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('event_image') ? ' has-error' : '' }}">
                            <label for="event_image">Event Image</label>
                            <div class="slim" style="width: 300px; height: 400px"
                                    data-label="Drop your image here or click to choose"
                                    data-size="590,780"
                                    data-min-size="430,730">
                                    <img src="{{ asset('storage/images/events') }} {{'/'.$event->media_url}}" >
                                    <input type="file" name="event_image[]"/>
                            </div>  
                            @if ($errors->has('event_image'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('event_image') }}</strong>
                                </span>
                            @endif       
                        </div>
                    </div>
                   </div>      
                   
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" aria-describedby="LocationHelp" value="{{ $event->location}}" name="location" id="location-address" aria-describedby="locationHelp" data-latitude-input="#location-lat" data-longitude-input="#location-lon" placeholder="The location of event" required>
                                
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
                                <input type="radio" value="2" @if($event->type==2) checked @endif name="type" id="paid" required><span class="label-text">Paid</span>
                              </label>   
                            </div>
                            <small class="form-text" id="event_type_error" style="color: red"></small> 
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
                            <label class="control-label">Ticket Type</label>
                            <div class="form-group{{ $errors->has('category') ? ' has-error' : '' }}">    
                                <div class="row">
                                    @foreach ($ticket_categories as $ticket_category)
                                        <div class="col-md-2">
                                            <div class="animated-checkbox">
                                                <label>
                                                    <input type="checkbox" id="{{$ticket_category->id}}" class="ticket_type_checkbox" data-text="{{$ticket_category->name}}" name="category[]" value="{{$ticket_category->id}}"><span class="label-text">{{$ticket_category->name}}</span>
                                                </label>
                                            </div>
                                        </div>                                       
                                    @endforeach                                    
                                </div>
                                @if ($errors->has('type'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('category') }}</strong>
                                    </span>
                                @endif
                                <small class="form-text text-muted" id="ticket_type_help">You can choose one or more ticket types.</small> 
                            </div>
                        </div>
                      </div>
                    </div>
                    <div id="append-row">
                    
                    </div>
                    <div class="row" id="ticket_sale_end_date_container">
                        <div class='col-md-4'>
                            <label class='control-label'>Tickets sale closes at</label>
                            <div class='form-group'><div class='input-group date' id='datetimepicker1'>
                                <input class='form-control datetimepicker' type='text' id='ticket_sale_end_date' name='ticket_sale_end_date' value='' placeholder='Select date'>
                            </div>
                        </div>
                    </div>   
                  </div>
                  <div class="row" id="sponsor_images_checkbox_row">
                        <div class="col-md-10">
                            <div class="animated-checkbox">
                                <label>
                                    <input type="checkbox" id="sponsor_images_checkox" name="sponsor_images_checkbox"><span class="label-text">I have logos for event sponsors</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="event_sponsor_image_row">
                        <div class="col-md-12">
                            <div class="row" id="append_event_sponsor_image">
                                @if (!empty($first_image = $event->getEventSponsorMedia()->first()))
                                {{-- @foreach ($event->getEventSponsorMedia as $single_sponsor_media) --}}
                                @foreach ($event->sponsor_media as $single_sponsor_media)
                                <div class="col-md-3">
                                    <label for="event_image">Event Sponsor Image</label>
                                    <div class="form-group{{ $errors->has('event_image') ? ' has-error' : '' }}">
                                        <div class="slim" id="slim-{{$single_sponsor_media->id}}" style="width: 250px; height: 250px"
                                            data-ratio="1:1"
                                            data-label="Drop your image here or click to choose"
                                            data-size="300,500"
                                            data-min-size="200,200">
                                            {{-- <img src="{{ asset('storage/images/event_sponsors/'.$single_sponsor_media->media_url) }}" alt="sponsor image"> --}}
                                            
                                            <input id="sponsor_image_input" type="file" name="event_sponsor_image[]"/>
                                        </div>  
                                        <script>
                                            var cropper = new Slim(document.getElementById('slim-{{$single_sponsor_media->id}}'));
                                            cropper.load("{{ asset('storage/images/event_sponsors/'.$single_sponsor_media->media_url) }}");
                                        </script>
                                        @if ($errors->has('event_image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('event_image') }}</strong>
                                            </span>
                                        @endif       
                                    </div>
                                </div>                                    
                                @endforeach
                                @else
                                <div class="col-md-3">
                                    <label for="event_image">Event Sponsor Image</label>
                                    <div class="form-group{{ $errors->has('event_image') ? ' has-error' : '' }}">
                                        <div class="slim" style="width: 250px; height: 250px"
                                            data-ratio="1:1"
                                            data-label="Drop your image here or click to choose"
                                            data-size="300,500"
                                            data-min-size="200,200">
                                            <input id="sponsor_image_input" type="file" name="event_sponsor_image[]"/>
                                        </div>  
                                        @if ($errors->has('event_image'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('event_image') }}</strong>
                                            </span>
                                        @endif       
                                    </div>
                                </div>
                                @endif
                            </div> 
                            <small class="form-text" id="event_sponsor_image_error" style="color: red"></small>                            
                            <button class="btn btn-primary" style="text-align: center" type="button" onclick="event_sponsor_image();"><i class="fa fa-plus"></i></button>
                            <small class="form-text text-muted" id="ticket_type_help">To add more event sponsor images click the plus button.</small> 
                        </div>
                        <div class="col-md-10">
                            <div class="animated-checkbox">
                                <label>
                                    <input type="checkbox" name="update_ticket_template_checkbox"><span class="label-text">I need to update the ticket template too</span>
                                </label>
                            </div>
                        </div>
                    </div>
                  
                  <input type="hidden" name="id" value="{{$event->id}}">  
                  <input type="hidden" name="previous_image_url" value="{{$event->media_url}}">                  
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
<script src="{{ asset('js/plugins/jquery.placepicker.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('js/plugins/summernote-bs4.min.js') }}"></script>
<script src="{{ asset('js/plugins/slim.jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/slim.kickstart.min.js') }}"></script>
<script>
    var room = {{$event_dates->last()->id}};

    function date_fields() {

        room++;
        var objTo = document.getElementById('date_fields')
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "form-group removeclass" + room);
        var rdiv = 'removeclass' + room;
        divtest.innerHTML = '<div class="row" id="date-row'+room+'"> <div class="col-md-10"> <div class="row"> <div class="col-md-4"> <label class="control-label">Event Start</label> <div class="form-group"> <div class="input-group" date> <input class="form-control datetimepicker" type="text" name="dates['+room+'][start]" placeholder="Select start date" required> </div> </div> </div> <div class="col-md-3"></div> <div class="col-md-4"> <div class="form-group"> <label class="control-label">Event Stop</label> <input class="form-control datetimepicker" type="text" name="dates['+room+'][stop]" placeholder="Select stop date" required> </div> </div> <div class="col-sm-1 pt-4"> <div class="form-group"> <button class="btn btn-success" type="button" onclick="remove_date_fields(' + room + ');"><i class="fa fa-minus"></i></button> </div> </div> </div> </div> </div>';

        objTo.appendChild(divtest);

        $("#date-row"+room).hide();
        $("#date-row"+room).slideDown("slow");

        $(".datetimepicker").flatpickr({
            enableTime: true,
            altInput: true,
            altFormat: "J F, Y h:i K",
            dateFormat: "Y-m-d H:i",
        });
    }
    function remove_date_fields(rid) {
        $('.removeclass' + rid).slideUp("normal", function() { $(this).remove(); } );
    }
</script>

    @if (!empty($first_image = $event->getEventSponsorMedia()->first()))
    {{-- we set the value of event_sponsor_image to match those of already existing sponsor images --}}
    <script>
        var event_sponsor_images = {{$event->getEventSponsorMedia()->count()}}-1;
    </script>
    @else
    <script>
        var event_sponsor_images = 0;
    </script>
    @endif
<script>
    var maximum = 2;
    function event_sponsor_image(){
        if(event_sponsor_images>maximum){
            $("#event_sponsor_image_error").text('You can upload a maximum of four images');   
        }else{            
            event_sponsor_images++;
            var content = '<div class="col-md-3" id="slim-div-'+event_sponsor_images+'"><label style="display: flex; justify-content: flex-end;"><i class="fa fa-remove" style="color: red; font-size:20px; cursor:pointer; margin-right: 20px;" data-toogle="tooltip" title="delete" onclick="deleteSponsorDiv('+event_sponsor_images+')"></i></label> <div class="form-group"> <div class="slim" id="slim-'+event_sponsor_images+'" style="width: 250px; height: 250px" data-ratio="1:1" data-label="Drop your image here or click to choose" data-size="300,500" data-min-size="200,300"> <input type="file" name="event_sponsor_image[]" required/> </div> </div> </div>';
            $("#append_event_sponsor_image").append(content);
            $('#slim-'+event_sponsor_images).slim({
                ratio: '1:1',
                minSize: {
                    width: 200,
                    height: 200,
                },
                crop: {
                    x: 0,
                    y: 0,
                    width: 300,
                    height: 500
                },
                download: false,
                label: 'Drop your image here or click to choose'
            });
            // $('#slim-'+event_sponsor_images).slim('parse');
        }
    }
    function deleteSponsorDiv(id){
        maximum++;
        $('#slim-div-'+id).remove();

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
</script>

@if($event->type==2)
    <script>
        //show the category and append row div
        $(document).ready(function() {
            $("#category-row").slideDown("slow");
            $("#append-row").slideDown("slow");
            $("#ticket_sale_end_date_container").slideDown("slow");
            $('#ticket_sale_end_date').val('{{$event->ticket_sale_end_date}}');
            $('#ticket_sale_end_date').attr('required', 'required');
        });

    </script>

    @foreach ($ticket_category_details as $ticket_category_detail)
        <script>
            $(document).ready(function() {
                //fetch the ids for selected categories  
                var category_id = '{{$ticket_category_detail->category_id}}';    
                // add selected attribute to options that were selected     
                $('#'+category_id).attr('checked', 'checked');
                //get the text for selected attribute and passit to appendRows method so that it adds its rows to the interface
                var text = $('#'+category_id).data('text');
                appendRows(text);
                //get and set the input values to the inputs created by the appendRows
                var price = '{{$ticket_category_detail->price}}';
                var no_of_tickets = {{$ticket_category_detail->no_of_tickets}};
                var ticket_sale_end_date = "{{date('Y-m-d H:i',strtotime($ticket_category_detail->ticket_sale_end_date))}}";
                var slug = '{{$ticket_category_detail->slug}}';

                $('#'+slug+'_category_amount').val(price);
                $('#'+slug+'_category_tickets').val(no_of_tickets);
                $('#'+slug+'_category_ticket_sale_end_date').val(ticket_sale_end_date);
            });
        </script>         
            
    @endforeach
    
    @else
        <script>   
        $(document).ready(function() {  
            $("#category-row").hide();
            $("#append-row").hide();            
            $("#ticket_sale_end_date_container").hide();
        });
        </script>
    @endif 

<script>

  $(document).ready(function() {   
    $(".ticket_type_checkbox").change(function() { 
        if(this.checked) {
            appendRows($(this).data('text'));
        }else{
            var name = $(this).data('text').toLowerCase().split(' ').join('_');
            var div = name+'_div'; 
            $('#'+div).slideUp("normal", function() { $(this).remove(); } );
            if ($(".ticket_type_checkbox:checked").length == 0){
                // none of checkobx is checked
                $("#ticket_sale_end_date_container").slideUp("slow");
                $('#ticket_sale_end_date').attr('required', false);
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

    $(".datetimepicker").flatpickr({
        enableTime: true,
        altInput: true,
        altFormat: "J F, Y h:i K",
        dateFormat: "Y-m-d H:i",
    });

    $('input[type=radio][name=type]').change(function() {
        $("#sponsor_images_checkbox_row").slideDown("slow");
        if (this.value == '2') {
            $("#category-row").slideDown("slow");
        }else {            
            $("#append-row").empty().slideUp("slow");  
            $("#category-row").slideUp("slow");
            $("#ticket_sale_end_date_container").slideUp("slow");
            $('#ticket_sale_end_date').attr('required', false);
        }
    });

    $("#sponsor_images_checkox").change(function() { 
        if(this.checked) {
            $("#event_sponsor_image_row").slideDown("slow");
            $('#sponsor_image_input').attr('required', 'required');
        }else{
            $("#event_sponsor_image_row").slideUp("slow");
            $('#sponsor_image_input').attr('required', false);
        }        

    });

  }); 

  function appendRows(category_name) {
      name = category_name.toLowerCase().split(' ').join('_');
      id = name+"_category";
      label = category_name;
      var content = "<div class='row' id='"+name+"_div'><div class='col-md-4'><div class='form-group'><label class='control-label'>"+label+" Amount</label><div class='form-group'><label class='sr-only' for='exampleInputAmount'>Amount (in shillings)</label><div class='input-group'><div class='input-group-prepend'><span class='input-group-text'>Ksh</span></div><input class='form-control' id='"+id+"_amount' value='' name='"+name+"_amount' type='number' min='1' placeholder='Amount to be paid' required><div class='input-group-append'><span class='input-group-text'>.00</span></div></div></div></div></div><div class='col-md-3'><div class='form-group'><label class='control-label'>"+label+" No. of tickets</label><input class='form-control' type='number' id='"+id+"_tickets' name='"+name+"_tickets' value='' placeholder='Maximum number of tickets' required></div></div></div></div>";     
      $("#append-row").append(content).slideDown("slow");
      $("#"+name+"_div").hide();
      $("#"+name+"_div").slideDown("slow");
      $("#ticket_sale_end_date_container").slideDown("slow");
      $('#ticket_sale_end_date').attr('required', 'required');
  }
  $("form").submit(function(e){
    if($('#paid').is(':checked') && $('.ticket_type_checkbox:checkbox:checked').length < 1){
        e.preventDefault();            
        $('#event_type_error').text('For paid event, you have to select at least one ticket type')
    } else{
        $('form').unbind('submit').submit();
    }        
  });

</script>
@if (!empty($event->getEventSponsorMedia()->first()))
@foreach ($event->getEventSponsorMedia()->get() as $single_event_sponsor_media)

    
@endforeach

<script>
    $('#sponsor_images_checkox').attr('checked', 'checked');
    $("#event_sponsor_image_row").slideDown("slow");
    // $('#sponsor_image_input').attr('required', 'required');
</script>
@else
<script>    
    $("#event_sponsor_image_row").hide();
</script>
@endif

@endsection