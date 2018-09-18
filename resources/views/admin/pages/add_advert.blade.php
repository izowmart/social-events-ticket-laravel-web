@extends('common_pages.layouts')

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-bullhorn"></i> Add Advert</h1>
          <p>Fill the details of the advert</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('adverts') }}">Adverts</a></li>
          <li class="breadcrumb-item"><a href="{{ route('add_advert') }}">Add</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('add_advert_post') }}" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label class="control-label">Title</label>
                            <input class="form-control" type="text" id="advert" name="title" value="{{ old('title') }}" placeholder="Input title of the advert"  required autofocus>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label class="control-label">Description</label>
                            <textarea class="form-control" placeholder="The advert decription here" value="{{ old('description') }}" id="description" name="description" rows="7" required></textarea>
                            @if ($errors->has('title'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Image</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" aria-describedby="ImageHelp" name="image" onchange="readURL(this);" class="custom-file-input" id="image" required>
                                    <label class="custom-file-label" for="image">Click to choose image</label>                                                
                                </div>
                                <div class="invalid-feedback">
                                    Please select an image.
                                </div>
                            </div>                            
                            <small class="form-text text-muted" id="ImageHelp">Preview of the image will be shown below.</small>                                        
                            <div id="hidden">
                                <br>
                                <img id="blah" src="" width="565"><br><br>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-10">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('start') ? ' has-error' : '' }}">
                                    <label class="control-label">Start date</label>
                                    <input class="form-control" type="text" id="start" name="start" value="{{ old('start') }}" placeholder="Select start date"  required autofocus>
                                    @if ($errors->has('start'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('start') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group{{ $errors->has('stop') ? ' has-error' : '' }}">
                                    <label class="control-label">Stop date</label>
                                    <input class="form-control" type="text" id="stop" name="stop" value="{{ old('stop') }}" placeholder="Select stop date"  required autofocus>
                                    @if ($errors->has('stop'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('stop') }}</strong>
                                        </span>
                                    @endif
                                </div>                            
                            </div>
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
<script type="text/javascript" src="{{ asset('js/plugins/bootstrap-datepicker.min.js') }}"></script>
<script>
function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $("#hidden").slideDown("slow");
            $('#blah')
                .attr('src', e.target.result)
                .width(400);
        };

        reader.readAsDataURL(input.files[0]);
    }
}
$('#start,#stop').datepicker({
    format: "yyyy-mm-dd",
    autoclose: true,
    todayHighlight: true
});
</script>
@endsection