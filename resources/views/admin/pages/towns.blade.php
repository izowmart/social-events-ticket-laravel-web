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
          <h1><i class="fa fa-compass"></i> Towns</h1>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admins') }}">Towns</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
              <p><a class="btn btn-primary icon-btn float-right" href="{{ route('add_town') }}"><i class="fa fa-plus"></i>Add Town</a></p><br><br>
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Town Name</th>
                    <th>Country</th>
                    <th>Added on</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($towns as $town)
                    <tr class="item">
                        <td>{{$town->town_name}}</td>
                        <td>{{$town->country_name}}</td>
                        <td>{{date("M j, Y", strtotime($town->created_at))}}</td>
                        <td>
                          <a href="{{ route('edit_town',array('country'=>$town->country_name,'town'=>$town->town_name))}}" class="btn btn-sm btn-outline-primary">Edit</a>
                         
                          <button onClick="deleteBtn({{$town->id}})" class="btn btn-sm btn-outline-danger">Delete</button>
                          <form id="delete_form_{{$town->id}}" action="{{ route('delete_town') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$town->id}}">
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