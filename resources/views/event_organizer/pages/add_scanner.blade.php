@extends('common_pages.layouts')

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-user-secret"></i> Add Scanner</h1>
          <p>Fill the form below to add a new scanner</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_unverified_events') }}">Events</a></li>
          <li class="breadcrumb-item"><a href="{{ route('add_scanner',['event_slug',$event->slug]) }}">Add scanner</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('add_scanner_post',['event_slug',$event->slug]) }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>First name</label>
                        <input type="text" class="form-control" placeholder="The scanner's first name" name="first_name" required/>  
                        <input type="hidden" name="event_id" value="{{Crypt::encrypt($event->id)}}"> 
                        <input type="hidden" name="event_name" value="{{$event->name}}">
                        <input type="hidden" name="event_slug" value="{{$event->slug}}">
                        @if ($errors->has('first_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>Last name</label>
                        <input type="text" class="form-control" maxlength="13" placeholder="The scanner's last name" name="last_name" required/>  
                        @if ($errors->has('last_name'))
                            <span class="help-block">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                      </div>
                    </div>                      
                  </div>
                  <div class="row"> 
                    <div class="col-md-6">                   
                      <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" placeholder="Email of the person to be contact" name="email" required/> 
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
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