@extends('admin.layouts')

@section('other-styles')
<style>
<link rel="stylesheet" type="text/css" href="//github.com/downloads/lafeber/world-flags-sprite/flags16.css" />
</style>
    
@endsection

@section('content')
    @include('admin.includes.header')
    @include('admin.includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Edit Town</h1>
          <p>Fill the form below to add a new town</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('towns') }}">Towns</a></li>
          <li class="breadcrumb-item"><a href="{{ route('edit_town',array('country'=>$country,'town'=>$town)) }}">Edit</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('edit_town_post') }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="control-label">Country</label>
                            <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }}">
                              <select aria-describedby="countryHelp" name="country_id" class="form-control" id="country_select" autofocus>
                                <option value="{{$country_id}}">{{$country}}</option>  
                                @foreach ($countries as $country)
                                <option value="{{$country->id}}">{{$country->name}}</option>                                    
                                @endforeach                                
                              </select>
                              @if ($errors->has('country_id'))
                                  <span class="help-block">
                                      <strong>{{ $errors->first('country_id') }}</strong>
                                  </span>
                              @endif
                              <small class="form-text text-muted" id="countryHelp">This only displays countries that are added to the system.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label class="control-label">Town</label>
                            <input class="form-control" type="text" id="city" value="{{$town}}" name="name" placeholder="Input name of the town"  required>
                            <input type="hidden" name="town_id" value="{{$town_id}}">
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
    container: document.querySelector('#city'),
    type: 'city',
    aroundLatLngViaIP: false,
    templates: {
      value: function(suggestion) {
        return suggestion.name;
      }
    }
  });
})();
</script>
@endsection