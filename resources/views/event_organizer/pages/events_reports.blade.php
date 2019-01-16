@extends('common_pages.layouts')

@section('title')
    <title>Tickets Report - Event Organizer Fika Places</title>
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
                <li class="breadcrumb-item"><a href="{{ route('event_organizer_home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('event_organizer_events_report') }}">Events Report</a></li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 col-lg-3 home-widget">
                        <a href="{{ route('event_organizer_verified_paid_events') }}">
                            <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                                <div class="info">
                                    <h4>Total Paid Event</h4>
                                    <p><b>{{$events->count()}}</b></p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3 home-widget">
                    <a href="{{ route('tickets_report') }}">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                    <div class="info">
                    <h4>Total Tickets Sold</h4>
                    <p><b>{{$total_tickets_sold}}</b></p>
                    </div>
                    </div>
                    </a>
                    </div>
                    <div class="col-md-6 col-lg-3 home-widget">
                    <a href="javascript:void(0);">
                    <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
                    <div class="info">
                    <h4>Total Revenue Earned</h4>
                    <p><b>{{$total_revenue_earned}}</b></p>
                    </div>
                    </div>
                    </a>
                    </div>
                </div>
                <div class="tile">
                    <div class="tile-body">
                        <p>List of all tickets</p>
                        <table class="table table-hover table-bordered" id="adminsTable">
                            <thead>
                            <tr>
                                <th>Event name</th>
                                <th>Tickets Bought</th>
                                <th>Revenue Earned</th>
                                {{--<th>Phone number</th>--}}
                                {{--<th>Bought from</th>--}}
                                {{--<th>Bought on</th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($events as $event)
                                <tr class="item">
                                    <td><a href="{{ route('event_organizer_single_event', ['slug'=>$event->slug]) }}">{{$event->name}}</a></td>
                                    <td>
                                        {{$event->tickets_bought}}
                                    </td>
                                    <td>
                                        {{$event->total_revenue}}
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