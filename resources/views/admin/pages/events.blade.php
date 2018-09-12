@extends('admin.layouts')

@section('testing-styles')
<style>

</style>
    
@endsection

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Events</h1>
          <p>List of all {{$type}} events</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route(str_replace(' ', '_', $type).'_events') }}">{{ucfirst(trans($type))}} Events</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Location</th>                   
                    <th>Type</th>
                    <th>Status</th>                   
                    <th>Added by</th>                
                    <th>Created on</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $event)
                    <tr class="item">
                        <td>{{ $event->name}}</td>                        
                        <td>{{ $event->description}}</td> 
                        <td>{{ $event->location}}</td>
                        <td>
                            @if ($event->type==1)
                                {{'free'}}
                            @else                                
                                {{'paid'}}
                            @endif
                        </td>                      
                        <td>
                            @if ($event->status==1)
                                {{'verified'}}
                            @elseif ($event->status==2)
                                {{'deactivated'}}
                            @else                                
                                {{'unverified'}}
                            @endif
                        </td>                        
                        <td>{{$event->first_name}} {{$event->last_name}}</td> 
                        <td>{{date("jS M Y, g:i a", strtotime($event->created_at))}}</td>
                        <td>
                        @if ($event->status==0)
                            <a href="{{ route('verify_event_post') }}" onclick="event.preventDefault(); document.getElementById('verify-form').submit();" class="btn btn-sm btn-outline-primary">Verify</a>
                            <form id="verify-form" action="{{ route('verify_event_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event->id}}">
                                <input type="hidden" name="type" value="{{$type}}">
                            </form>
                        @endif 
                        @if ($event->status!==2)
                            <a href="{{ route('deactivate_event_post') }}" onclick="event.preventDefault(); document.getElementById('deactivate-form').submit();" class="btn btn-sm btn-outline-primary">Deactivate</a>
                            <form id="deactivate-form" action="{{ route('deactivate_event_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event->id}}">
                                <input type="hidden" name="type" value="{{$type}}">
                            </form>
                        @endif 
                        @if ($event->status==2)
                            <a href="{{ route('activate_event_post') }}" onclick="event.preventDefault(); document.getElementById('activate-form').submit();" class="btn btn-sm btn-outline-primary">Activate</a>
                            <form id="activate-form" action="{{ route('activate_event_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event->id}}">
                                <input type="hidden" name="type" value="{{$type}}">
                            </form>
                        @endif
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