@extends('common_pages.layouts')

@section('title')
    <title>Events Report - Admin Fika Places</title>
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-briefcase"></i> Events Report</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('tickets_report') }}">Events Report</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          {{-- <div class="row">
            <div class="col-md-6 col-lg-3 home-widget">
                <a href="{{ route('tickets_report') }}">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                    <div class="info">
                    <h4>Total events</h4>
                    <p><b>{{$events->count()}}</b></p>
                    </div>
                </div>
                </a>
            </div> 
            <div class="col-md-6 col-lg-3 home-widget">
                <a href="{{ route('tickets_source', ['source_name'=>'website']) }}">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                    <div class="info">
                    <h4>Bought from website</h4>
                    <p><b>{{$tickets_from_web->count()}}</b></p>
                    </div>
                </div>
                </a>
            </div>  
            <div class="col-md-6 col-lg-3 home-widget">
                <a href="{{ route('tickets_source', ['source_name'=>'app']) }}">
                <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                    <div class="info">
                    <h4>Bought from app</h4>
                    <p><b>{{$tickets_from_app->count()}}</b></p>
                    </div>
                </div>
                </a>
            </div> 
          </div> --}}
          <div class="tile">  
            <div class="tile-body">
              <p>Report of all paid events @if ($filter=='website' || $filter=='app') sold from {{$filter}} @endif</p>
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Event name</th>
                    <th>Sold on Website</th>
                    <th>Sold on Mobile</th>
                    <th>Total sold</th>
                    <th>Amount of money</th>
                    <th>Customers</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($paid_events as $paid_event)
                    <tr class="item">
                        <td><a href="{{ route('event_organizer_single_event', ['slug'=>$paid_event->event->slug]) }}">{{$paid_event->event->name}}</a></td>
                        <td>{{$paid_events_from_web->count()}}</td>
                        <td>{{$paid_events_from_mobile->count()}}</td>
                        <td>{{$paid_events_from_web->count()+$paid_events_from_mobile->count()}}</td>
                        <td>{{$paid_event->price_sum}}</td>
                        <td>{{$paid_event->customers}}</td>
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
<script type="text/javascript">$('#adminsTable').DataTable();</script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/sweetalert.min.js') }}"></script>
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