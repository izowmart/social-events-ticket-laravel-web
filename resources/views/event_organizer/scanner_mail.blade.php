@extends('beautymail::templates.ark')
 
@section('content')
 
    @include('beautymail::templates.ark.heading', [
        'heading' => 'Added as event scanner',
        'level' => 'h1'
    ])
 
    @include('beautymail::templates.ark.contentStart')
 
        <h1>Hi, {{$name}}</h1>
        <p>You were added as scanner for the tickets on {{$event_name}} event at FIKA Places.</p>
        <h4 class="secondary"><strong>Your login credentials are:</strong></h4>      
        <p><strong>Email: </strong> {{$email}}</p>
        <p><strong>Password: </strong> {{$password}}</p>
        <p>Proceed to the tickets app to login</p>
    @include('beautymail::templates.ark.contentEnd')
 
@stop