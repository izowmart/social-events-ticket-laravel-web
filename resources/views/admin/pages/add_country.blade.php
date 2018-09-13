@extends('common_pages.layouts')

@section('other-styles')
<style>
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
</style>
    
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Add Country</h1>
          <p>Fill the form below to add a new town</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('countries') }}">Countries</a></li>
          <li class="breadcrumb-item"><a href="{{ route('add_country') }}">Add</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('add_country_post') }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label">Country name</label>
                            <input class="form-control" type="text" id="country" name="name" placeholder="Input name of the country"  required autofocus>
                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
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
<script src="https://cdn.jsdelivr.net/npm/places.js@1.10.0"></script>
<script>
(function() {
  var placesAutocomplete = places({
    container: document.querySelector('#country'),
    type: 'country',
    templates: {
      suggestion: function(suggestion) {
        return '<i class="flag ' + suggestion.countryCode + '"></i> ' +
          suggestion.highlight.name;
      }
    }
  });
})();
</script>
@endsection