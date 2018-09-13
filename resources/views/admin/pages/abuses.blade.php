@extends('common_pages.layouts')

@section('testing-styles')
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa fa-bug"></i> Abuses</h1>
          <p>List of all abuses reported from the post</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('posts') }}">Posts</a></li>
          <li class="breadcrumb-item"><a href="{{ route('abuses') }}">Abuses</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>User name</th>
                    <th>User email</th>
                    <th>Type</th>
                    <th>Reported on</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($abuses as $abuse)
                    <tr class="item">
                        <td>{{$abuse->first_name}} {{$abuse->last_name}}</td>
                        <td>{{ $abuse->email}}</td>                        
                        <td>
                          @if ($abuse->type==1)
                              {{'spam'}}
                          @elseif ($abuse->type==2)
                              {{'terrorism'}}
                          @elseif ($abuse->type==3)
                              {{'inappropriate content'}}
                          @else                                
                              {{'others'}}
                          @endif
                        </td>
                        <td>{{date("jS M Y, g:i a", strtotime($abuse->created_at))}}</td>  
                        @if ($abuse->status!=2)
                        <td>
                          <a href="{{ route('block_post') }}" onclick="event.preventDefault(); document.getElementById('block-form').submit();" class="btn btn-sm btn-outline-primary">Block</a>
                            <form id="block-form" action="{{ route('block_post') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                                <input type="hidden" name="id" value="{{$abuse->id}}">
                            </form>
                        </td>
                        @endif                      
                                                
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

@endsection