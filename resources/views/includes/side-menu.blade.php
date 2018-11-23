<!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">
    <div class="app-sidebar__user">
      <div>
        <p class="app-sidebar__user-name">John Doe</p>
        {{--<p class="app-sidebar__user-designation"></p>TODO:: show the user role here..--}}
      </div>
    </div>
    <ul class="app-menu">
      @auth('web_admin')
      <li><a class="app-menu__item {{ Route::currentRouteNamed('admin_home') ? 'active' : '' }}" href="{{ route('admin_home') }}"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Home</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('admins') ? 'active' : '' }}" href="{{ route('admins') }}"><i class="app-menu__icon fa fa-user-circle"></i><span class="app-menu__label">Admins</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('countries') ? 'active' : '' }}" href="{{ route('countries') }}"><i class="app-menu__icon fa fa-map-o"></i><span class="app-menu__label">Countries</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('towns') ? 'active' : '' }}" href="{{ route('towns') }}"><i class="app-menu__icon fa fa-compass"></i><span class="app-menu__label">Towns</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('adverts') ? 'active' : '' }}" href="{{ route('adverts') }}"><i class="app-menu__icon fa fa-bullhorn"></i><span class="app-menu__label">Adverts</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('venues') ? 'active' : '' }}" href="{{ route('venues') }}"><i class="app-menu__icon fa fa-map-marker"></i><span class="app-menu__label">Venues</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('users') ? 'active' : '' }}" href="{{ route('users') }}"><i class="app-menu__icon fa fa-group"></i><span class="app-menu__label">Users</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('posts') ? 'active' : '' }}" href="{{ route('posts') }}"><i class="app-menu__icon fa fa-gg"></i><span class="app-menu__label">Posts</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('verified_event_organizers') ? 'active' : '' }}" href="{{ route('verified_event_organizers') }}"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Event organizers</span></a></li>
      {{-- <li class="treeview {{ Route::currentRouteNamed('verified_event_organizers') || Route::currentRouteNamed('unverified_event_organizers') ? 'is-expanded' : '' }}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Event organizer</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item {{ Route::currentRouteNamed('unverified_event_organizers') ? 'active' : '' }}" href="{{ route('unverified_event_organizers') }}"><i class="icon fa fa-circle-o"></i> Unverified</a></li>
          <li><a class="treeview-item {{ Route::currentRouteNamed('verified_event_organizers') ? 'active' : '' }}" href="{{ route('verified_event_organizers') }}"><i class="icon fa fa-circle-o"></i> Verified</a></li>
        </ul>
      </li> --}}
      <li><a class="app-menu__item {{ Route::currentRouteNamed('admin_free_events') ? 'active' : '' }}" href="{{ route('admin_free_events') }}"><i class="app-menu__icon fa fa fa-calendar-o"></i><span class="app-menu__label">Free Events</span></a></li>
      <li class="treeview {{ Route::currentRouteNamed('admin_verified_paid_events') || Route::currentRouteNamed('admin_unverified_paid_events') || Route::currentRouteNamed('admin_verified_paid_events') ? 'is-expanded' : '' }}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-calendar-check-o"></i><span class="app-menu__label">Paid Events</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item {{ Route::currentRouteNamed('admin_unverified_paid_events') ? 'active' : '' }}" href="{{ route('admin_unverified_paid_events') }}"><i class="icon fa fa-chain-broken"></i> Unverified</a></li>
          <li><a class="treeview-item {{ Route::currentRouteNamed('admin_verified_paid_events') ? 'active' : '' }}" href="{{ route('admin_verified_paid_events') }}"><i class="icon fa fa-check"></i> Verified</a></li>
        </ul>
      </li>
      @endauth
      @auth('web_event_organizer')
      <li><a class="app-menu__item {{ Route::currentRouteNamed('event_organizer_home') ? 'active' : '' }}" href="{{ route('event_organizer_home') }}"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Home</span></a></li> 
      <li><a class="app-menu__item {{ Route::currentRouteNamed('add_event') ? 'active' : '' }}" href="{{ route('add_event') }}"><i class="app-menu__icon fa fa-calendar-plus-o"></i><span class="app-menu__label">Add Event</span></a></li> 
      <li><a class="app-menu__item {{ Route::currentRouteNamed('event_organizer_free_events') ? 'active' : '' }}" href="{{ route('event_organizer_free_events') }}"><i class="app-menu__icon fa fa fa-calendar-o"></i><span class="app-menu__label">My Free Events</span></a></li> 
      <li class="treeview {{ Route::currentRouteNamed('event_organizer_verified_paid_events') || Route::currentRouteNamed('event_organizer_unverified_paid_events') || Route::currentRouteNamed('event_organizer_verified_paid_events') ? 'is-expanded' : '' }}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-calendar-check-o"></i><span class="app-menu__label">My Paid Events</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item {{ Route::currentRouteNamed('event_organizer_unverified_paid_events') ? 'active' : '' }}" href="{{ route('event_organizer_unverified_paid_events') }}"><i class="icon fa fa-chain-broken"></i> Unverified</a></li>
          <li><a class="treeview-item {{ Route::currentRouteNamed('event_organizer_verified_paid_events') ? 'active' : '' }}" href="{{ route('event_organizer_verified_paid_events') }}"><i class="icon fa fa-check"></i> Verified</a></li>
        </ul>
      </li>  
      <li class="treeview {{ Route::currentRouteNamed('tickets_report') || Route::currentRouteNamed('tickets_source', ['source_name'=>'website']) || Route::currentRouteNamed('tickets_source', ['source_name'=>'app']) ? 'is-expanded' : '' }}"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-briefcase"></i><span class="app-menu__label">Reports</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item {{ Route::currentRouteNamed('event_organizer_unverified_paid_events') ? 'active' : '' }}" href="{{ route('event_organizer_unverified_paid_events') }}"><i class="icon fa fa-calendar"></i> Events</a></li>
          <li><a class="treeview-item {{ Route::currentRouteNamed('tickets_report') || Route::currentRouteNamed('tickets_source', ['source_name'=>'website']) || Route::currentRouteNamed('tickets_source', ['source_name'=>'app']) ? 'active' : '' }}" href="{{ route('tickets_report') }}"><i class="icon fa fa-money"></i> Tickets</a></li>
        </ul>
      </li>  
      @endauth
    </ul>
  </aside>