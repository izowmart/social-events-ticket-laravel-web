<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Main css-->
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css"
          href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('css/ticket-templates.css') }}">
    <title>Tickets</title>
</head>
<body>
<div class="ticket-template">
    <div class="ticket-position">
        <div class="tickets-content">
            <h1 style="text-align: left">{{$event->name}}</h1>
            <img class="tickets2-qr" src="{{asset('qr.png')}}" alt="qr code here">
            <div class="ticket-info">

                <p><span>Venue:</span> {{$event->location}}</p>
                <p><span>Time:</span>
                <ol>
                    @foreach($event_dates as $event_date)
                        <li>{{$event_date}}</li>
                    @endforeach
                </ol>
                </p>
                {{--<p><span>Date:</span> {{date("m/d/Y", strtotime($event_dates->first()->start))}} - {{date("m/d/Y", strtotime($event_dates->first()->end))}}</p>--}}
                {{--<p><span>Time:</span> {{date("g:i a", strtotime($event_dates->first()->start))}} - {{date("g:i a", strtotime($event_dates->first()->end))}}</p>--}}
                <p><span>Category:</span> @if ($event->type==1)
                        Free
                    @else
                        Paid
                    @endif</p>
                <p><span>No:</span> {{isset($ticket_no) ? "#".$ticket_no : "#87654321" }}</p>
            </div>
            @if($event->sponsor_media)
                <div class="ticket-end">
                    <div class="container-fluid">
                        <div class="row">
                            @foreach($event->sponsor_media as $sponsor_image)
                                <div class="col">
                                    <img class="ticket-sponsors"
                                         src="{{ asset('storage/images/event_sponsors/'.$sponsor_image->media_url) }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="text-center">
        <p>Powered by FIKA Places
            <img class="rounded" src="{{ asset('fika_logo.jpg') }}" alt="logo" style="height: 5%; width: 5%">
        </p>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy"
        crossorigin="anonymous"></script>
</body>
</html>

    
    
    