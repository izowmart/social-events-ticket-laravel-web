@extends('common_pages.layouts')

@section('title')
    <title>Add Admin - Admin Fika Places</title>
@endsection

@section('content')
    @include('includes.header')
    @include('includes.side-menu')

    <main class="app-content">
      <div class="app-title">
        <div>
          <h1><i class="fa fa-edit"></i> Add Admin</h1>
          <p>Fill the form below to add a new admin</p>
        </div>
        <ul class="app-breadcrumb breadcrumb">
          <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
          <li class="breadcrumb-item"><a href="{{ route('admin_home') }}">Home</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admins') }}">Admins</a></li>
        </ul>
      </div>
      <div class="row">
        <div class="col-md-12">
          <div class="tile">              
            <div class="tile-body">
              <form method="POST" action="{{ route('add_admin_post') }}">
                {{ csrf_field() }}
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                            <label class="control-label">First Name</label>
                            <input class="form-control" type="text" name="first_name" placeholder="Input first name" value="{{ old('first_name') }}" required autofocus>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                            <label class="control-label">Last Name</label>
                            <input class="form-control" type="text" name="last_name" placeholder="Input last name" value="{{ old('last_name') }}" required autofocus>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-5">
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="control-label">Email</label>
                            <input class="form-control" type="text" name="email" placeholder="Input email address" value="{{ old('email') }}" required autofocus>
                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
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