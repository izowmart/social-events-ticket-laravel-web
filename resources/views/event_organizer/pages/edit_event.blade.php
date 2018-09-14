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
              <form method="POST" action="{{ route('edit_event_post') }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-8">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label">Event name</label>
                            <input class="form-control" type="text" value="{{$event->name}}" name="name" placeholder="Input name of the event"  required autofocus>
                            <input type="hidden" name="id" value="{{$event->id}}">
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-8">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label">Description</label>
                            <textarea class="form-control" placeholder="The event decription here" id="description" name="description" rows="7" required>{{$event->description}}</textarea>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                        <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }}">
                            <label class="control-label">Location</label>
                            <input type="text" class="form-control" placeholder="The event location here" value="{{$event->location}}" id="location" name="location"  required>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('location') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>                    
                  </div>
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                          <label class="control-label">Type</label>
                          <div class="form-group{{ $errors->has('town_id') ? ' has-error' : '' }}">
                            <select aria-describedby="townHelp" name="type" class="form-control" id="type" required> 
                              {{-- check current active selection --}}
                              @if ($event->type==2)                             
                                <option value="2">Paid</option>   
                              @else                           
                                <option value="1">Free</option>                          
                              @endif  

                              {{-- check which option to show on dropdown --}}
                              @if ($event->type==1)                             
                                <option value="2">Paid</option>   
                              @else                           
                                <option value="1">Free</option>                          
                              @endif                                       
                            </select>
                            @if ($errors->has('type'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('type') }}</strong>
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
<script>
  $(document).ready(function() {

    // Advanced usage
    $("#location").each(function() {
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