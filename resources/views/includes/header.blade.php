<!-- Navbar-->
  <header class="app-header"><a class="app-header__logo" href="index.html">FIKA</a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar" aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">
      <!-- User Menu-->
      <li class="dropdown"><a class="app-nav__item" href="#" data-toggle="dropdown" aria-label="Open Profile Menu"><i class="fa fa-user fa-lg"></i></a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
          <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-cog fa-lg"></i> Settings</a></li>
          <li><a class="dropdown-item" href="page-user.html"><i class="fa fa-user fa-lg"></i> Profile</a></li>
          @auth('web_admin')              
            <li><a class="dropdown-item" href="{{ route('admin_logout') }}" onclick="event.preventDefault();
                                                      document.getElementById('admin-logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            <form id="admin-logout-form" action="{{ route('admin_logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
          @endauth
          @auth('web_event_organizer')          
            <li><a class="dropdown-item" href="{{ route('event_organizer_logout') }}" onclick="event.preventDefault();
                                                      document.getElementById('event-organizer-logout-form').submit();"><i class="fa fa-sign-out fa-lg"></i> Logout</a></li>
            <form id="event-organizer-logout-form" action="{{ route('event_organizer_logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>              
          @endauth
        </ul>
      </li>
    </ul>
  </header>