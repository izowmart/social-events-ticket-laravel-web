@extends('common_pages.layouts')

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-home"></i> Home</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3 home-widget">
            <a href="{{ route('users') }}" @if ($new_users->count()>0) data-toggle="tooltip" title="{{$new_users->count()}} new user(s)" @endif>
              @if ($new_users->count()>0)
                  <span class="pending text-center">{{$new_users->count()}}</span>
              @endif
              <div class="widget-small primary coloured-icon"><i class="icon fa fa-users fa-3x"></i>
                <div class="info">
                  <h4>APP Users</h4>
                  <p><b>{{$app_users->count()}}</b></p>
                </div>
              </div>
            </a>          
        </div>
        <div class="col-md-6 col-lg-3 home-widget">
          <a @if ($pending_event_organizers->count()>0)
              href="{{ route('unverified_event_organizers') }}"
              data-toggle="tooltip" title="{{$pending_event_organizers->count()}} pending event organizer(s)"
          @else
              href"{{ route('verified_event_organizers') }}"
          @endif>
          @if ($pending_event_organizers->count()>0)
            <span class="pending text-center">{{$pending_event_organizers->count()}}</span>              
          @endif
            <div class="widget-small info coloured-icon"><i class="icon fa fa-th-list fa-3x"></i>
              <div class="info">
                <h4>EVENT ORGANIZERS</h4>
                <p><b>{{$all_event_organizers->count()}}</b></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3 home-widget">
          <a @if ($pending_events->count()>0)
              href="{{ route('admin_unverified_events') }}"
              data-toggle="tooltip" title="{{$pending_events->count()}} pending even organizer(s)"
          @else
              href"{{ route('admin_verified_paid_events') }}"
          @endif>
          @if ($pending_events->count()>0)
            <span class="pending text-center">{{$pending_events->count()}}</span>              
          @endif
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-calendar-check-o fa-3x"></i>
              <div class="info">
                <h4>EVENTS</h4>
                <p><b>{{$all_events->count()}}</b></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3 home-widget">
          <a href="{{ route('posts') }}" @if ($abuses->count()>0)
            data-toggle="tooltip" title="{{$abuses->count()}} abuse(s) from {{$posts_with_abuse->count()}} post(s)"
          @endif >
          @if ($abuses->count()>0)
            <span class="pending text-center">{{$abuses->count()}}</span>              
          @endif
            <div class="widget-small danger coloured-icon"><i class="icon fa fa-gg fa-3x"></i>
              <div class="info">
                <h4>Posts</h4>
                <p><b>{{$posts->count()}}</b></p>
              </div>
            </div>
          </a>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">New Users</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="newUsers"></canvas>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Most Active Users</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="MostActiveUsers"></canvas>
            </div>
          </div>          
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Most Active Venues</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="MostActiveVenues"></canvas>
            </div>
          </div>          
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Country With Most Users</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="CountryWithMostUsers"></canvas>
            </div>
          </div>          
        </div>
        <div class="col-md-6">
          <div class="tile">
            <h3 class="tile-title">Town With Most Users</h3>
            <div class="embed-responsive embed-responsive-16by9">
              <canvas class="embed-responsive-item" id="TownWithMostUsers"></canvas>
            </div>
          </div>          
        </div>
      </div>
    </main>
@endsection

@section('other-scripts')
{{-- Page specific scripts --}}
<script type="text/javascript" src="{{ asset('js/plugins/chart.js') }}"></script>
<script type="text/javascript">
    new Chart($("#newUsers"), {
    type: 'line',
    data: {
        labels: ['1st sep','2nd sep','3rd sep','4th sep','5th sep'],
        datasets: [{ 
            data: [30,35,25,40,45],
            label: "New users",
            borderColor: "#3e95cd",
            fill: true
        }
        ]
    },
    options: {
        
    }
    });

    new Chart($("#MostActiveUsers"), {
        type: 'horizontalBar',
        data: {
        labels: ["John", "Kim", "Jane", "Peter", "Linda"],
        datasets: [
            {
            label: "Comments and posts",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [34,57,74,78,43]
            }
        ]
        },
        options: {
        legend: { display: false }
        }
    });

    new Chart($("#MostActiveVenues"), {
        type: 'horizontalBar',
        data: {
        labels: ["CityHall", "Uhuru park", "Archives", "Hilton", "Kempinski"],
        datasets: [
            {
            label: "Posts",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [87,127,154,108,120]
            }
        ]
        },
        options: {
        legend: { display: false }
        }
    });

    new Chart($("#CountryWithMostUsers"), {
        type: 'horizontalBar',
        data: {
        labels: ["Kenya", "Uganda", "Tanzania", "Sweden", "Nigeria"],
        datasets: [
            {
            label: "Posts",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [870,427,554,138,450]
            }
        ]
        },
        options: {
        legend: { display: false }
        }
    });

    new Chart($("#TownWithMostUsers"), {
        type: 'horizontalBar',
        data: {
        labels: ["Nairobi", "Arusha", "Kampala", "Nakuru", "Lagos"],
        datasets: [
            {
            label: "Posts",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: [520,227,174,348,350]
            }
        ]
        },
        options: {
        legend: { display: false }
        }
    });
    
</script>
  
@endsection