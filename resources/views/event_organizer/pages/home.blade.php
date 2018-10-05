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
          <li class="breadcrumb-item"><a href="{{ route('event_organizer_home') }}">Home</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-3 home-widget">
          <a @if ($unverified_events->count()>0)
              href="{{ route('event_organizer_unverified_events') }}"
              data-toggle="tooltip" title="{{$unverified_events->count()}} unverified event(s)"
          @else
              href="{{ route('event_organizer_verified_paid_events') }}"
          @endif>
          @if ($unverified_events->count()>0)
            <span class="pending text-center">{{$unverified_events->count()}}</span>              
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
          <a href="" >
            <div class="widget-small info coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
              <div class="info">
                <h4>UPCOMING EVENTS</h4>
                <p><b>{{$upcoming_events->count()}}</b></p>
              </div>
            </div>
          </a>
        </div>
        <div class="col-md-6 col-lg-3 home-widget">
          <a href="" data-toggle="tooltip" title="4 new scanners">                
            <span class="pending text-center">4</span>
            <div class="widget-small warning coloured-icon"><i class="icon fa fa-files-o fa-3x"></i>
              <div class="info">
                <h4>SCANNERS</h4>
                <p><b>102</b></p>
              </div>
            </div>
          </a>
        </div>
      </div>
    </main>
@endsection