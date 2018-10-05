@extends('common_pages.layouts')

@section('testing-styles')
<style>

</style>
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa fa-gg"></i> Posts</h1>
          <p>List of all posts</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('posts') }}">Posts</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
              {{-- <p><a class="btn btn-primary icon-btn float-right" href="{{ route('add_post') }}"><i class="fa fa-plus"></i>Add Post</a></p><br><br> --}}
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>User name</th>
                    <th>Venue</th>
                    <th>Comment</th>
                    <th>Media</th>
                    <th>Status</th>
                    <th>Abuses</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                    <tr class="item">
                        <td><a href="{{ route('single_user', ['id'=>Crypt::encrypt($post->user_id)]) }}">{{$post->first_name}} {{$post->last_name}}</a></td>
                        <td>{{ $post->venue_name}}</td>                        
                        <td>{{$post->comment}}</td>
                        <td><a href="{{$post->media_url}}" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td>
                        <td>
                            @if ($post->status==1)
                                {{'active'}}
                            @elseif ($post->status==2)
                                {{'blocked'}}
                            @else                                
                                {{'inactive'}}
                            @endif
                        </td>
                        <td>{{ $post->abuses->count() }}
                            @if ($post->abuses->count()>0)
                                <a href="{{ route('abuses',['id'=>$post->id]) }}" class="btn btn-sm btn-outline-primary">View</a>
                                
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
<script type="text/javascript">$('#adminsTable').DataTable();</script>
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-notify.min.js') }}"></script>
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