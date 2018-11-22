@extends('common_pages.layouts')

@section('title')
    <title>Venues - Admin Fika Places</title>
@endsection
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-map-marker"></i> Venues</h1>
          <p>List of all venues</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('venues') }}">Venues</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">  
              <p><a class="btn btn-primary icon-btn float-right" href="{{ route('add_venue') }}"><i class="fa fa-plus"></i>Add Venue</a></p><br><br>
            <div class="tile-body">
              <table class="table table-hover table-bordered" id="adminsTable">
                <thead>
                  <tr>
                    <th>Name</th>
                    <th>Town</th>
                    <th>Contact person name</th>
                    <th>Contact person phone</th>
                    <th>Contact person email</th>
                    <th>Image</th>
                    <th>Featured</th>
                    <th>Location</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($venues as $venue)
                    <tr class="item">
                        <td>{{str_limit($venue->venue_name, $limit = 30, $end = '...')}}</td>
                        <td><a href="{{ route('single_town', ['id'=>Crypt::encrypt($venue->town_id)]) }}">{{ $venue->town_name}}</a></td>                        
                        <td>{{$venue->contact_person_name}}</td>
                        <td>{{$venue->contact_person_phone}}</td>
                        <td>{{$venue->contact_person_email}}</td>
                        <td><img height="100px" width="100px" src="{{asset('/venue_images/'.$venue->venue_image)}}"></td>
                        <td>
                          @if($venue->featured_status==0)
                          <button onClick="featureBtn('{{$venue->id}}')" class="btn btn-sm btn-outline-primary">Feature</button>
                          <form id="feature_venue_{{$venue->id}}" action="{{ route('feature_venue') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$venue->id}}">
                              <input type="hidden" name="featured_description" value="" id="featured_description_{{$venue->id}}">
                          </form>
                          @else
                          <a class="btn btn-sm btn-outline-danger" href="{{route('unfeature_venue',['slug'=>$venue->slug])}}"> Unfeature</a>
                          @endif
                        <td>
                            <a target="_blank" href="https://maps.google.com/maps?q={{$venue->latitude}},{{$venue->longitude}}" class="btn btn-sm btn-outline-primary">View</a>
                        </td>
                        <td>
                          <a href="{{ route('edit_venue', ['slug'=>$venue->slug]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                         
                          <button onClick="deleteBtn({{$venue->id}})" class="btn btn-sm btn-outline-danger">Delete</button>
                          <form id="delete_form_{{$venue->id}}" action="{{ route('delete_venue') }}" method="POST" style="display: none;">
                              {{ csrf_field() }}
                              <input type="hidden" name="id" value="{{$venue->id}}">
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
<script >
  function featureBtn(id) {
    swal({
  title: "Feature this venue?",
  text: "Add a catchy description of the venue:",
  type: "input",
  showCancelButton: true,
  closeOnConfirm: false,
  inputPlaceholder: "Venue's description"
}, function (inputValue) {
    if (inputValue === false) return false;
    if (inputValue === " ") {
      swal.showInputError("You need to write something!");
      return false
    }
    $form = "feature_venue_"+id;
    $featured = "featured_description_"+id;
    document.getElementById($featured).value = inputValue;
    document.getElementById($form).submit();
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