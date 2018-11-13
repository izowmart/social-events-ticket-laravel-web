@extends('common_pages.layouts')

@section('styles')    
<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" rel="stylesheet">
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Events</h1>
          <p>List of all {{$type}} events</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          {{-- check if its admin or event organizer and set approriate route --}}
          @auth('web_admin')
              <li class="breadcrumb-item"><a href="{{ route('admin_'.str_replace(' ', '_', $type).'_events') }}">{{ucfirst(trans($type))}} Events</a></li>
          @endauth
          @auth('web_event_organizer')
              <li class="breadcrumb-item"><a href="{{ route('event_organizer_'.str_replace(' ', '_', $type).'_events') }}">{{ucfirst(trans($type))}} Events</a></li>
          @endauth
          
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
              @auth('web_event_organizer')
                <p><a class="btn btn-primary icon-btn float-right" href="{{ route('add_event') }}"><i class="fa fa-plus"></i>Add Event</a></p><br><br>
              @endauth
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Location</th>                   
                    <th>Type</th>
                    <th>Image</th>
                    @if ($type!=='free')
                    <th>Status</th>                        
                    @endif
                    @auth('web_event_organizer')                        
                    <th>Scanners</th> 
                    @endauth   
                    @auth('web_admin')                
                    <th>Added by</th>                       
                    @endauth           
                    <th>Featured event</th>      
                    <th>Created on</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                    <tr class="item">
                        <td>{{str_limit($event->name, $limit = 55, $end = '...')}}</td> 
                        <td>{{str_limit($event->location, $limit = 50, $end = '...')}}</td>                        
                        <td>
                            @if ($event->type==1)
                                {{'free'}}
                            @else                                
                                {{'paid'}}
                            @endif
                        </td>   
                        <td>
                            <div class="zoom-gallery">
                                <a href="{{ asset('storage/images/events/'.$event->media_url)}}" data-source="{{ asset('storage/images/events/'.$event->media_url)}}" class="btn btn-sm btn-outline-primary" data-info="{{$event->id}}">
                                    View
                                </a>
                            </div>
                        </td> 
                        @if ($type!=='free')                                           
                        <td>
                            @if ($event->status==1)
                                {{'verified'}}
                            @elseif ($event->status==2)
                                {{'deactivated'}}
                            @else                                
                                {{'unverified'}}
                            @endif
                        </td>                                             
                        @endif
                        @auth('web_event_organizer') 
                        <td>{{ $event->scanners->count() }}
                            @if ($event->scanners->count()>0)
                                <a href="{{ route('scanners',['event_slug'=>$event->slug]) }}" class="btn btn-sm btn-outline-primary">View</a>
                            @else
                                <a href="{{ route('add_scanner',['event_slug'=>$event->slug]) }}" class="btn btn-sm btn-outline-primary">Add</a>
                            @endif
                        </td>   
                        @endauth
                        @auth('web_admin')                                               
                        <td><a href="{{ route('single_event_organizer', ['id'=>Crypt::encrypt($event->event_organizer_id)]) }}">{{$event->first_name}} {{$event->last_name}}</a></td>  
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group with nested dropdown"> 
                            @if ($event->featured_event==2)
                                <button id="active_status_btn_{{$event->id}}" class="btn btn-primary" type="button">{{'No'}}</button>
                                <div class="btn-group" role="group">
                                <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" style="cursor: pointer;" onClick="event.preventDefault(); featuredEventBtn({{$event->id}},'yes')">Yes</a></div>
                                </div>
                            @else  
                                <button class="btn btn-success" type="button">{{'Yes'}}</button>    
                                <div class="btn-group" role="group">
                                <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                                <div class="dropdown-menu dropdown-menu-right"><a class="dropdown-item" style="cursor: pointer;" onClick="event.preventDefault(); featuredEventBtn({{$event->id}},'no')">No</a></div>
                                </div>              
                            @endif
                            </div>
                            <form id="update_featured_event_{{$event->id}}" action="{{ route('admin_update_featured_event') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$event->id}}">
                            <input id="featured_event_to_input_{{$event->id}}" type="hidden" name="featured_event_to" value="">
                            </form>
                        </td>                           
                        @endauth
                        <td>@if ($event->featured_event==2)
                            No
                        @else
                            Yes
                        @endif
                        </td>
                        @auth('web_admin')

                        @endauth
                        <td>{{date("jS M Y, g:i a", strtotime($event->created_at))}}</td>
                        <td>
                        {{-- check the user and set appropriate actions. --}}
                        @auth('web_admin')
                            @if ($event->status==0)
                                <a href="{{ route('admin_verify_event_post') }}" onclick="event.preventDefault(); document.getElementById('verify_form_{{$event->id}}').submit();" class="btn btn-sm btn-outline-primary">Verify</a>
                                <form id="verify_form_{{$event->id}}" action="{{ route('admin_verify_event_post') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$event->id}}">
                                    <input type="hidden" name="type" value="{{$type}}">
                                </form>
                            @endif 
                            @if ($event->status!==2)
                                <a href="{{ route('admin_deactivate_event_post') }}" onclick="event.preventDefault(); document.getElementById('deactivate_form_{{$event->id}}').submit();" class="btn btn-sm btn-outline-primary">Deactivate</a>
                                <form id="deactivate_form_{{$event->id}}" action="{{ route('admin_deactivate_event_post') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$event->id}}">
                                    <input type="hidden" name="type" value="{{$type}}">
                                </form>
                            @endif 
                            @if ($event->status==2)
                                <a href="{{ route('admin_activate_event_post') }}" onclick="event.preventDefault(); document.getElementById('activate_form_{{$event->id}}').submit();" class="btn btn-sm btn-outline-primary">Activate</a>
                                <form id="activate_form_{{$event->id}}" action="{{ route('admin_activate_event_post') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$event->id}}">
                                    <input type="hidden" name="type" value="{{$type}}">
                                </form>
                            @endif
                        @endauth

                        @auth('web_event_organizer')
                            <a href="{{ route('edit_event', ['slug'=>$event->slug]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            
                            <button onClick="deleteBtn({{$event->id}})" class="btn btn-sm btn-outline-danger">Delete</button>
                            <form id="delete_form_{{$event->id}}" action="{{ route('delete_event') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event->id}}">
                            </form>
                        @endauth
                        
                        </td>
                    </tr>                         
                    @endforeach                  
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('other-scripts')
<!-- Data table plugin-->
<script type="text/javascript" src="{{ asset('js/plugins/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript">$('#adminsTable').DataTable({
    "order": [[ 4, "asc" ]]
});</script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/sweetalert.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script>
@auth('web_admin')
  function featuredEventBtn(id,status_to) {
    // console.log("Property id: "+id+'\t'+"Status to be updated to: "+status_to+"\n");
    $('#featured_event_to_input_'+id).val(status_to);
    $('#update_featured_event_'+id).submit();
  }
@endauth
@auth('web_event_organizer')
  function deleteBtn(id) {    
    swal({
      		title: "Are you sure?",
      		text: "You will not be able to recover this record",
      		type: "warning",
      		showCancelButton: true,
      		confirmButtonText: "Yes, delete it!",
      		cancelButtonText: "No, cancel!",
      		closeOnConfirm: false,
      		closeOnCancel: true
      	}, function(isConfirm) {
          if (isConfirm) {
            $form ="delete_form_"+id;
      			document.getElementById($form).submit();
      		}
          
      		
      	});
  }
@endauth
  $(document).ready(function() {
      $('.zoom-gallery').magnificPopup({
          delegate: 'a',
          type: 'image',
          closeOnContentClick: false,
          closeBtnInside: false,
          mainClass: 'mfp-with-zoom mfp-img-mobile',
          image: {
              verticalFit: true
          }            
      });
  });
  
</script>
@if (session('status'))
    <script type="text/javascript">
      $.notify({
            title: "Success : ",
            message: "{{ session('status') }}",
            icon: 'fa fa-check' 
          },{
            type: "info"
      });
    </script>        
@endif 
@endsection