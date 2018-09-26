<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payments</title>
</head>
<body>

{{--<div class="row">--}}
    {{--<div class="col-md-6">--}}
        {{--<div class="tile">--}}
            {{--<h3 class="tile-title">Vertical Form</h3>--}}
            {{--<div class="tile-body">--}}
                {{--<form>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="control-label">Name</label>--}}
                        {{--<input class="form-control" type="text" placeholder="Enter full name">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="control-label">Email</label>--}}
                        {{--<input class="form-control" type="email" placeholder="Enter email address">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="control-label">Address</label>--}}
                        {{--<textarea class="form-control" rows="4" placeholder="Enter your address"></textarea>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="control-label">Gender</label>--}}
                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="radio" name="gender">Male--}}
                            {{--</label>--}}
                        {{--</div>--}}
                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="radio" name="gender">Female--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="control-label">Identity Proof</label>--}}
                        {{--<input class="form-control" type="file">--}}
                    {{--</div>--}}
                    {{--<div class="form-group">--}}
                        {{--<div class="form-check">--}}
                            {{--<label class="form-check-label">--}}
                                {{--<input class="form-check-input" type="checkbox">I accept the terms and conditions--}}
                            {{--</label>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</form>--}}
            {{--</div>--}}
            {{--<div class="tile-footer">--}}
                {{--<button class="btn btn-primary" type="button"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>&nbsp;&nbsp;&nbsp;<a class="btn btn-secondary" href="#"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cancel</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
        <!-- The div that you will append the checkout button to -->
        <div class="checkout-button"></div>
        <script
                src="https://code.jquery.com/jquery-3.3.1.min.js"
                integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
                crossorigin="anonymous"></script>

        <script id="mula-checkout-library" type="text/javascript"
                src="https://beep2.cellulant.com:9212/checkout/v2/mula-checkout.js"></script>
        <script type="text/javascript">
    $('document').ready(function () {

        const encryptionURL = "{{route('encryption_url')}}";

        // Provide the class name of where you would like to append the 'pay with mula' button. This example uses a div
        MulaCheckout.addPayWithMulaButton({className: 'checkout-button', checkoutType: 'modal'});

        const params = {
          "customer_id" : 1,
          "event_id"    : 1,
        };

        // On click of the button provide the encrypted merchant properties as well as the checkoutType.
        document.querySelector('.mula-checkout-button').addEventListener('click', () => {
            console.log("button clicked");
            encrypt().then(response => {
                console.log("response: "+JSON.stringify(response));
                MulaCheckout.renderMulaCheckout({merchantProperties: response, checkoutType: 'modal'})
            })

        });

        function encrypt() {
            return fetch(encryptionURL, {
                method: 'POST',
                body: JSON.stringify(params),
                mode: 'cors'
            }).then(response => response.json())
        }
    });
</script >

</body>
</html>
