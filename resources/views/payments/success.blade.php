@extends('payments.main')
@section('content')
    <h1 class="cover-heading">
        @component('payments.title')
            <strong>Successful Payment</strong>
        @endcomponent
    </h1>
    <p class="lead">
        Great! Your payment was successful.
    </p>
    <p class="lead">
        <a href="{{route('tickets_home')}}" class="btn btn-lg btn-secondary">Home</a>
    </p>
@endsection()