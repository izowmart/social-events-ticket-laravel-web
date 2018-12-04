@extends('common_pages.layouts')

@section('title')
    <title>Edit Scanner - Event Organizer Fika Places</title>
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Edit Scanner</h1>
          <p>Edit scanner below</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_unverified_events') }}">Events</a></li>
          <li class="breadcrumb-item"><a href="{{ route('scanners',['event_slug',$event_slug]) }}">Scanners</a></li>
          <li class="breadcrumb-item"><a href="{{ route('edit_scanner',['event_slug',$event_slug,'scanner_id'=>Crypt::encrypt($scanner->id)]) }}">Edit</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('edit_scanner_post',['event_slug',$event_slug]) }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label>First name</label>
                        <input type="text" class="form-control" value="{{$scanner->first_name}}" placeholder="The scanner's first name" name="first_name" required/>  
                        <input type="hidden" name="scanner_id" value="{{$scanner->id}}"> 
                        <input type="hidden" name="event_slug" value="{{$event_slug}}">
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
                        <input type="text" class="form-control" value="{{$scanner->last_name}}" maxlength="13" placeholder="The scanner's last name" name="last_name" required/>  
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
                        <input type="email" class="form-control" value="{{$scanner->email}}" placeholder="Email of the person to be contact" name="email" required/> 
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