@extends('common_pages.layouts')

@section('testing-styles')
<link rel="stylesheet" href="{{ asset('css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/buttons.dataTables.min.css') }}">
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-bullhorn"></i> Users</h1>
          <p>List of all users</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('users') }}">Users</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="usersTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Birth year</th>
                    <th>Gender</th>
                    <th>Country</th>
                    <th>App version</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr class="item">
                        <td>{{$user->first_name}} {{$user->last_name}}</td>
                        <td>{{$user->email}}</td>
                        <td>{{$user->phone_number}}</td>
                        <td>{{$user->year_of_birth}}</td>
                        <td>
                            @if ($user->gender==1)
                                {{'male'}}
                            @elseif ($user->gender==2)
                                {{'female'}}
                            @endif
                        </td>                        
                        <td>{{$user->name}}</td>
                        <td>{{$user->app_version_code}}</td>                        
                        <td>
                            @if ($user->status==1)
                                {{'active'}}
                            @elseif ($user->status==2)
                                {{'deactivated'}}
                            @else                                
                                {{'inactive'}}
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
<script type="text/javascript" src="{{ asset('js/plugins/dataTables.buttons.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/buttons.bootstrap4.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/buttons.flash.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/jszip.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/pdfmake.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/vfs_fonts.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/buttons.html5.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/plugins/buttons.print.min.js') }}"></script>
<script type="text/javascript">$('#usersTable').DataTable({
  dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
});</script>
@endsection