@extends('admin.layouts')

@section('testing-styles')
<style>

</style>
    
@endsection

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.side-menu')

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
                        <td>{{$user->year_of_birth}}</td>
                        <td>
                            @if ($user->gender==1)
                                {{'male'}}
                            @elseif ($user->gender==2)
                                {{'female'}}
                            @endif
                        </td>                        
                        <td>{{$user->name}}</td>
                        <td>{{$user->first_name}} {{$user->app_version_code}}</td>                        
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
<script type="text/javascript">$('#usersTable').DataTable();</script>
@endsection