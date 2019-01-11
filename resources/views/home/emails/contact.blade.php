@extends('beautymail::templates.ark')

@section('content')

    @include('beautymail::templates.ark.heading', [
		'heading' => 'New Enquiry!',
		'level' => 'h1'
	])

    @include('beautymail::templates.ark.contentStart')

    <h4 class="secondary"><strong>{{$name}}</strong></h4><br>
    <p>Email: {{$email}}</p><br>
    <p>Message: {{$the_body}}</p><br>

    @include('beautymail::templates.ark.contentEnd')

@stop