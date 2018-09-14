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
          <h1><i class="fa fa-map-marker"></i> Scanners</h1>
          <p>List of all scanners</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('scanners') }}">Scanners</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
              <p><a class="btn btn-primary icon-btn float-right" href="{{ route('add_scanner') }}"><i class="fa fa-plus"></i>Add Scanner</a></p><br><br>
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Town</th>
                    <th>Contact person name</th>
                    <th>Contact person phone</th>
                    <th>Contact person email</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($scanners as $scanner)
                    <tr class="item">
                        <td>{{str_limit($scanner->scanner_name, $limit = 30, $end = '...')}}</td>
                        <td>{{ $scanner->town_name}}</td>                        
                        <td>{{$scanner->contact_person_name}}</td>
                        <td>{{$scanner->contact_person_phone}}</td>
                        <td>{{$scanner->contact_person_email}}</td>
                        <td>
                            @if ($scanner->status==1)
                                {{'active'}}
                            @elseif ($scanner->status==2)
                                {{'deactivated'}}
                            @else                                
                                {{'inactive'}}
                            @endif
                        </td>
                        <td>
                          <button onClick="document.getElementById('edit_form_{{$scanner->id}}').submit();" class="btn btn-sm btn-outline-primary">Edit</button>
                          <form id="edit_form_{{$scanner->id}}" action="{{ route('edit_scanner') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$scanner->id}}">
                          </form>

                          <button onClick="deleteBtn({{$scanner->id}})" class="btn btn-sm btn-outline-danger">Delete</button>
                          <form id="delete_form_{{$scanner->id}}" action="{{ route('delete_scanner') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$scanner->id}}">
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