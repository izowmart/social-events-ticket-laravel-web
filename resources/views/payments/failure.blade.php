@extends('payments.main')
@section('content')
    <div class="card w-75 text-center">
        <div class="card-body">
            <h5 class="card-title">
                @component('payments.title')
                    <strong>Failed Payment</strong>
                @endcomponent
            </h5>

            <p class="card-text">
                Sorry! Your payment was unsuccessful.
            </p>
            <a href="{{route('home')}}"
               type="button"
               class="card-link btn btn-outline-success">
                <i class="fas fa-arrow-left"></i>
                Go to Home
            </a>
        </div>
        <div class="card-footer text-muted">Thank You!</div>
    </div>
@endsection()