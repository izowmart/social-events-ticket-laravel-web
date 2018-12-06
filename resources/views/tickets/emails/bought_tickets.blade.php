<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <title>Tickets</title>
</head>
<body>
        <h1>Hello {{$name}},</h1>
        This email is to confirm that you have successfully purchased {{$tickets_count}} Ticket(s) for the
        Event, {{$event->name}}, via Fika Places.
        <p>Please see attached for Tickets. </p>
        <p>Tickets can only be used once, so please do not share your ticket with anyone.</p>

        <p>We sincerely hope that you enjoy the event.</p>

        <p>Please download our App and explore different venues around you.</p>
        (App Link)
        <p>Those who have the App will also be able to qualify for different gifts and offers during Fika events.</p>


        <p>For more information on Fika Places you can visit our website www.fikaplaces.com</p>
        <p>You should also follow us on social media for interesting information and updates on offers</p>
        <ul>
            <li><a href="https://www.facebook.com/fika.live/">Facebook</a></li>
            <li><a href="https://twitter.com/fika_live/">Twitter</a></li>
            <li><a href="https://instagram.com/fikaplaces">Instagram</a></li>
        </ul>


        <p> Thank you!</p>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
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

