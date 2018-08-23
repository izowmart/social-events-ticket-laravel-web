<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
</head>
<body>
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
        const merchantProperties = {
            merchantTransactionID: "{{uniqid("Trans:")}}",
            customerFirstName: 'john',
            customerLastName: "njoro",
            MSISDN: "254711110128",
            customerEmail: "johnnjoroge40@gmail.com",
            amount: "1000",
            currencyCode: "KES",
            accountNumber: "123456",
            serviceCode: "APISBX3857",
            dueDate: "2018-08-24 11:09:59",
            serviceDescription: "Getting service/good x",
            accessKey: "$2a$08$Ga/jSxv1qturlAr8SkHhzOaprXnfOJUTqB6fLRrc/0nSYpRlAd96e",
            countryCode: "KE",
            languageCode: "en",
            successRedirectUrl: "{{route('success_url')}}",
            failRedirectUrl: "{{route('failure_url')}}",
            paymentWebhookUrl: "http://dc91f14b.ngrok.io/payments/process_payment"
        };

        // Provide the class name of where you would like to append the 'pay with mula' button. This example uses a div
        MulaCheckout.addPayWithMulaButton({className: 'checkout-button', checkoutType: 'express'});

        // On click of the button provide the encrypted merchant properties as well as the checkoutType.
        document.querySelector('.mula-checkout-button').addEventListener('click', () => {
            console.log("button clicked");
            encrypt().then(merchantProperties => {
                console.log("merchantproperties: "+merchantProperties);
                MulaCheckout.renderMulaCheckout({merchantProperties: merchantProperties, checkoutType: 'express'})
            })

        });

        function encrypt() {
            return fetch(encryptionURL, {
                method: 'POST',
                body: JSON.stringify(merchantProperties),
                mode: 'cors'
            }).then(response => response.json())
        }
    });
</script>

</body>
</html>
