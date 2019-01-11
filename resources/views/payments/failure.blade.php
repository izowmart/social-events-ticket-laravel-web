@extends('payments.main')
@section('content')
    <h1 class="cover-heading">
        @component('payments.title')
            <strong>Failed Payment</strong>
        @endcomponent
    </h1>
    <p class="lead">
        Sorry! Your payment was unsuccessful.
    </p>
    <p class="lead">
        <a href="{{route('home')}}" class="btn btn-lg btn-secondary">Home</a>
    </p>
@endsection()