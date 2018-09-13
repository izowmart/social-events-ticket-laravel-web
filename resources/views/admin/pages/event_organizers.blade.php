@extends('common_pages.layouts')

@section('testing-styles')
<style>

</style>
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-th-list"></i> Event Organizers</h1>
          <p>List of all {{$type}} event organizers</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route($type.'_event_organizers') }}">{{ucfirst(trans($type))}} Event Organizers</a></li>
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
                    <th>Email</th>                    
                    <th>Created on</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($event_organizers as $event_organizer)
                    <tr class="item">
                        <td>{{$event_organizer->first_name}} {{$event_organizer->last_name}}</td>
                        <td>{{ $event_organizer->email}}</td>                        
                        <td>{{date("jS M Y, g:i a", strtotime($event_organizer->created_at))}}</td>
                        <td>
                            @if ($event_organizer->status==1)
                                {{'verified'}}
                            @elseif ($event_organizer->status==2)
                                {{'deactivated'}}
                            @else                                
                                {{'unverified'}}
                            @endif
                        </td>
                        <td>
                        @if ($event_organizer->status==0)
                            <a href="{{ route('verify_event_organizer_post') }}" onclick="event.preventDefault(); document.getElementById('verify-form').submit();" class="btn btn-sm btn-outline-primary">Verify</a>
                            <form id="verify-form" action="{{ route('verify_event_organizer_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event_organizer->id}}">
                                <input type="hidden" name="type" value="{{$type}}">
                            </form>
                        @endif 
                        @if ($event_organizer->status!==2)
                            <a href="{{ route('deactivate_event_organizer_post') }}" onclick="event.preventDefault(); document.getElementById('deactivate-form').submit();" class="btn btn-sm btn-outline-primary">Deactivate</a>
                            <form id="deactivate-form" action="{{ route('deactivate_event_organizer_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event_organizer->id}}">
                                <input type="hidden" name="type" value="{{$type}}">
                            </form>
                        @endif 
                        @if ($event_organizer->status==2)
                            <a href="{{ route('activate_event_organizer_post') }}" onclick="event.preventDefault(); document.getElementById('activate-form').submit();" class="btn btn-sm btn-outline-primary">Activate</a>
                            <form id="activate-form" action="{{ route('activate_event_organizer_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$event_organizer->id}}">
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