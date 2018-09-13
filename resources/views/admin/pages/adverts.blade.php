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
          <h1><i class="fa fa-bullhorn"></i> Adverts</h1>
          <p>List of all adverts</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('adverts') }}">Adverts</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
              <p><a class="btn btn-primary icon-btn float-right" href="{{ route('add_advert') }}"><i class="fa fa-plus"></i>Add Advert</a></p><br><br>
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Descrition</th>
                    <th>Image</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Status</th>
                    <th>Added By</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($adverts as $advert)
                    <tr class="item">
                        <td>{{str_limit($advert->title, $limit = 35, $end = '...')}}</td>
                        <td>{{ str_limit($advert->description, $limit = 60, $end = '...') }}</td>                        
                        <td><a href="{{$advert->image_url}}" target="_blank" class="btn btn-sm btn-outline-primary">View</a></td>
                        <td>{{date("jS M Y", strtotime($advert->start_date))}}</td>
                        <td>{{date("jS M Y", strtotime($advert->end_date))}}</td>
                        <td>
                            @if ($advert->status==1)
                                {{'active'}}
                            @elseif ($advert->status==2)
                                {{'deactivated'}}
                            @else                                
                                {{'inactive'}}
                            @endif
                        </td>
                        <td>{{$advert->first_name}} {{$advert->last_name}}</td>
                        <td>
                          <button onClick="document.getElementById('edit_form_{{$advert->id}}').submit();" class="btn btn-sm btn-outline-primary">Edit</button>
                          <form id="edit_form_{{$advert->id}}" action="{{ route('edit_advert') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$advert->id}}">
                          </form>

                          <button onClick="deleteBtn({{$advert->id}})" class="btn btn-sm btn-outline-danger">Delete</button>
                          <form id="delete_form_{{$advert->id}}" action="{{ route('delete_advert') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$advert->id}}">
                          </form>
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
<script type="text/javascript" src="{{ asset('js/plugins/sweetalert.min.js') }}"></script>
<script>
  function deleteBtn(id) {    
    swal({
      		title: "Are you sure?",
      		text: "You will not be able to recover this record",
      		type: "warning",
      		showCancelButton: true,
      		confirmButtonText: "Yes, delete it!",
      		cancelButtonText: "No, cancel!",
      		closeOnConfirm: false,
      		closeOnCancel: true
      	}, function(isConfirm) {
          if (isConfirm) {
            $form ="delete_form_"+id;
      			document.getElementById($form).submit();
      		}
          
      		
      	});
  }
  
</script>
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