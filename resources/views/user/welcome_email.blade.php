@extends('beautymail::templates.minty')

@section('content')

    @include('beautymail::templates.minty.contentStart')
    <tr>
        <td class="title">
            Dear {{$user->name}},
        </td>
    </tr>
    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <p>Thank you for creating a Fika Places account.</p>

            <p>Fika Places allows users to locate and appreciate the different venues around them. We also provide
                updates on new venues and upcoming events. Users are also able to buy tickets directly from our App or
                through our website.</p>
            <p>For more information on Fika Places please <a href="www.fikaplaces.com">visit our website</a></p>
            <p>Please share the app with your friends and help us grow our community.</p>
        </td>
    </tr>
    <tr>
        <td width="100%" height="25"></td>
    </tr>

    <tr>
        <td width="100%" height="10"></td>
    </tr>
    <tr>
        <td class="paragraph">
            <p>Follow us on social media for news on offers and giveaways:</p>
            <ul>
                <li><a href="https://www.facebook.com/fika.live/">Facebook</a></li>
                <li><a href="https://twitter.com/fika_live/">Twitter</a></li>
                <li><a href="https://instagram.com/fikaplaces">Instagram</a></li>
            </ul>
        </td>

    </tr>
    <tr>
        <td width="100%" height="25"></td>
    </tr>
    <tr>
        <td>
            @include('beautymail::templates.minty.button', ['text' => 'Thank You!', 'link' => '#'])
        </td>
    </tr>
    <tr>
        <td width="100%" height="25"></td>
    </tr>
    @include('beautymail::templates.minty.contentEnd')

@stop

{{--<!doctype html>--}}
{{--<html lang="{{ app()->getLocale() }}">--}}
{{--<head>--}}
    {{--<meta charset="utf-8">--}}
    {{--<meta http-equiv="X-UA-Compatible" content="IE=edge">--}}
    {{--<meta name="viewport" content="width=device-width, initial-scale=1">--}}

    {{--<title>Ticket Information</title>--}}

    {{--<!-- Main css-->--}}
    {{--<link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">--}}
    {{--<!-- Font-icon css-->--}}
    {{--<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">--}}

{{--</head>--}}

{{--<body>--}}
{{--Dear {{$name}},--}}

{{--Thank you for creating a Fika Places account.--}}

{{--Fika Places allows users to locate and appreciate the different venues around them. We also provide updates on new venues and upcoming events. Users are also able to buy tickets directly from our App or through our website.--}}
{{--For more information on Fika Places please visit our website www.fikaplaces.com--}}
{{--Please share the app with your friends and help us grow our community.--}}

{{--Follow us on social media for news on offers and giveaways--}}
{{--Facebook (clickable icon)--}}
{{--Instagram (clickable icon)--}}
{{--Twitter (clickable icon)--}}
{{--</body>--}}
{{--</html>--}}