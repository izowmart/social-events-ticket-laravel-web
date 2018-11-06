<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Payments</title>
</head>
<body>

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
