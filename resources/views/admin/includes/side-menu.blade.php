<!-- Sidebar menu-->
  <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
  <aside class="app-sidebar">
    <div class="app-sidebar__user">
      <div>
        <p class="app-sidebar__user-name">John Doe</p>
        <p class="app-sidebar__user-designation">Frontend Developer</p>
      </div>
    </div>
    <ul class="app-menu">
      <li><a class="app-menu__item {{ Route::currentRouteNamed('adminhomepage') ? 'active' : '' }}" href="{{ route('admin_homepage') }}"><i class="app-menu__icon fa fa-home"></i><span class="app-menu__label">Home</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('admins_table') ? 'active' : '' }}" href="{{ route('admins_table') }}"><i class="app-menu__icon fa fa-user-circle"></i><span class="app-menu__label">Admins</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('countries') ? 'active' : '' }}" href="{{ route('countries') }}"><i class="app-menu__icon fa fa-map-o"></i><span class="app-menu__label">Countries</span></a></li>
      <li><a class="app-menu__item {{ Route::currentRouteNamed('towns') ? 'active' : '' }}" href="{{ route('towns') }}"><i class="app-menu__icon fa fa-compass"></i><span class="app-menu__label">Towns</span></a></li>
      <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-bullhorn"></i><span class="app-menu__label">Adverts</span></a></li>
      <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-map-marker"></i><span class="app-menu__label">Venue</span></a></li>
      <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-group"></i><span class="app-menu__label">Users</span></a></li>
      <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon fa fa-gg"></i><span class="app-menu__label">Parts</span></a></li>
      <li><a class="app-menu__item" href="charts.html"><i class="app-menu__icon	fa fa-bug"></i><span class="app-menu__label">Abuse</span></a></li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Event organizer</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="table-basic.html"><i class="icon fa fa-circle-o"></i> Uneverified</a></li>
          <li><a class="treeview-item" href="table-data-table.html"><i class="icon fa fa-circle-o"></i> Verified</a></li>
        </ul>
      </li>
      <li class="treeview"><a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa fa-calendar-check-o"></i><span class="app-menu__label">Events</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu">
          <li><a class="treeview-item" href="table-basic.html"><i class="icon fa fa-circle-o"></i> Uneverified</a></li>
          <li class="treeview"><a class="treeview-item" href="table-data-table.html" data-toggle="treeview"><i class="icon fa fa-circle-o"></i> Verified</a>
            <li><a class="treeview-item" href="table-basic.html"><i class="icon fa fa-circle-o"></i> Free</a></li>
          </li>
        </ul>
      </li>
    </ul>
  </aside>