@extends('common_pages.layouts')

@section('title')
    <title>Tickets Report - Admin Fika Places</title>
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-briefcase"></i> Tickets Report</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('tickets_report') }}">Tickets Report</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="row">
          <div class="col-md-6 col-lg-3 home-widget">
            <a href="{{ route('tickets_report') }}">
              <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                <div class="info">
                  <h4>Total tickets sold</h4>
                  <p><b>{{$tickets->count()}}</b></p>
                </div>
              </div>
            </a>
          </div> 
          {{--<div class="col-md-6 col-lg-3 home-widget">--}}
            {{--<a href="{{ route('tickets_source', ['source_name'=>'website']) }}">--}}
              {{--<div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>--}}
                {{--<div class="info">--}}
                  {{--<h4>Bought from website</h4>--}}
                  {{--<p><b>{{$tickets_from_web->count()}}</b></p>--}}
                {{--</div>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</div>  --}}
          {{--<div class="col-md-6 col-lg-3 home-widget">--}}
            {{--<a href="{{ route('tickets_source', ['source_name'=>'mobile']) }}">--}}
              {{--<div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>--}}
                {{--<div class="info">--}}
                  {{--<h4>Bought from mobile</h4>--}}
                  {{--<p><b>{{$tickets_from_mobile->count()}}</b></p>--}}
                {{--</div>--}}
              {{--</div>--}}
            {{--</a>--}}
          {{--</div> --}}
          </div>
          <div class="tile">  
            <div class="tile-body">
              <p>List of all tickets @if ($filter=='website' || $filter=='app') sold from {{$filter}} @endif</p>
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Event name</th>
                    {{--<th>Customer Name</th>--}}
                    {{--<th>Email</th>--}}
                    {{--<th>Phone number</th>--}}
                    {{--<th>Bought from</th>--}}
                    {{--<th>Bought on</th>--}}
                  </tr>
                </thead>
                <tbody>
                    @foreach ($events as $ticket)
                    <tr class="item">
                        <td><a href="{{ route('event_organizer_single_event', ['slug'=>$ticket->event->slug]) }}">{{$ticket->event->name}}</a></td>
                        <td>{{$ticket->ticket_customer->first_name}} {{$ticket->ticket_customer->last_name}}</td>
                        <td><a href="mailto:{{$ticket->ticket_customer->email}}">{{$ticket->ticket_customer->email}}</a></td>
                        <td><a href="tel:{{$ticket->ticket_customer->phone_number}}">{{$ticket->ticket_customer->phone_number}}</a></td>
                        <td>
                          @if ($ticket->ticket_customer->source==1)
                            Web                              
                          @else
                            App                              
                          @endif
                        </td>
                        <td>{{date("jS M Y, g:i a", strtotime($ticket->ticket_customer->created_at))}}</td>
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