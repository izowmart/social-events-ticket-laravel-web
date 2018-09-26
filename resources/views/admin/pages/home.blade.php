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
<script type="text/javascript" src="{{ asset('js/plugins/moment.min.js') }}"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // start new users script
    $.ajax({
        url: "{{ route('new_users_chart') }}",
        type: "get",
        success: function(response) {
          var new_users_label = [];
          var new_users_data = [];
            response.forEach(function(data){
                var new_date  = moment(data.created_at).format('Do MMM');
                new_users_label.push(new_date);
                new_users_data.push(data.total);
            });
            NewUsersChart(new_users_label,new_users_data);
        }
    });

    function NewUsersChart(label,data) {
      new Chart($("#newUsers"), {
      type: 'line',
      data: {
          labels: label,
          datasets: [{ 
              data: data,
              label: "New users",
              borderColor: "#3e95cd",
              fill: true
          }
          ]
      }
      });
    }
    //end new users script

     // start active users script
    $.ajax({
        url: "{{ route('active_users_chart') }}",
        type: "get",
        success: function(response) {
          var active_users_label = [];
          var active_users_data = [];
            response.forEach(function(data){
                active_users_label.push(data.first_name);
                active_users_data.push(data.total);
            });
            ActiveUsersChart(active_users_label,active_users_data);
        }
    });

    function ActiveUsersChart(label,data) {
      new Chart($("#MostActiveUsers"), {
          type: 'horizontalBar',
          data: {
          labels: label,
          datasets: [
              {
              label: "Posts",
              backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
              data: data
              }
          ]
          },
          options: {
          legend: { display: false }
          }
      });
    }
    //end active users script

     // start active venues script
    $.ajax({
        url: "{{ route('active_venues_chart') }}",
        type: "get",
        success: function(response) {
          var active_venues_label = [];
          var active_venues_data = [];
            response.forEach(function(data){
                active_venues_label.push(data.name);
                active_venues_data.push(data.total);
            });
            ActiveVenuesChart(active_venues_label,active_venues_data);
        }
    });

    function ActiveVenuesChart(label,data) {
      new Chart($("#MostActiveVenues"), {
          type: 'horizontalBar',
          data: {
          labels: label,
          datasets: [
              {
              label: "Posts",
              backgroundColor: ["#3cba9f", "#c45850","#8e5ea2","#e8c3b9","#3e95cd"],
              data: data
              }
          ]
          },
          options: {
          legend: { display: false }
          }
      });
    }
    //end active venues script

     // start country with most users script
    $.ajax({
        url: "{{ route('country_most_users_chart') }}",
        type: "get",
        success: function(response) {
          var country_most_users_label = [];
          var country_most_users_data = [];
            response.forEach(function(data){
                country_most_users_label.push(data.name);
                country_most_users_data.push(data.total);
            });
            CountryMostUsersChart(country_most_users_label,country_most_users_data);
        }
    });

    function CountryMostUsersChart(label,data) {
      new Chart($("#CountryWithMostUsers"), {
          type: 'horizontalBar',
          data: {
          labels: label,
          datasets: [
              {
              label: "Posts",
              backgroundColor: ["#3cba9f", "#c45850","#3e95cd","#e8c3b9","#8e5ea2"],
              data: data
              }
          ]
          },
          options: {
          legend: { display: false }
          }
      });
    }
    //end country with most users script

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
  });
    
</script>
  
@endsection