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
          <h1><i class="fa fa-edit"></i> Add Country</h1>
          <p>Fill the form below to add a new town</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_homepage') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('countries') }}">Countries</a></li>
          <li class="breadcrumb-item"><a href="{{ route('add_countries') }}">Add</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('country') ? ' has-error' : '' }}">
                            <label class="control-label">Name</label>
                            <input class="form-control" type="text" id="country" name="country" placeholder="Input name of the town"  required autofocus>
                            @if ($errors->has('country'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('country') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                  </div>
                  <div class="tile-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>

              </form>              
            </div>
          </div>
        </div>
      </div>
    </main>
@endsection

@section('other-scripts')
<script src="https://maps.googleapis.com/maps/api/js?v=3&sensor=true&libraries=places&key=AIzaSyBO5Else2rW4UNyXiCMp3y20JV7BseTMys"></script>
<script src="{{ asset('js/plugins/locationpicker.jquery.js') }}"></script>
<script>
  $('#country').locationpicker({
    location: {
        latitude: 46.15242437752303,
        longitude: 2.7470703125
    },
    radius: 300,
    inputBinding: {
        latitudeInput: $('#us7-lat'),
        longitudeInput: $('#us7-lon'),
        radiusInput: $('#us7-radius'),
        locationNameInput: $('#us7-address')
    },
    enableAutocomplete: true,
    autocompleteOptions: {
        types: ['(countries)']
    }
});
     
</script>
@endsection