<?php

namespace App\Http\Controllers;

use App\PaymentResponse;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;

class HomeController extends Controller
{
    public $secretKey;
    public $ivKey;

    public function __construct()
    {
        $this->secretKey = "rHkgZYVKnCGLXFRb";
        $this->ivKey = "HtJBgFPQh3qwcRmk";

    }

    public function encryptData(Request $request)
    {
        $event_organizer_id =  $request->event_organizer_id;
        $amount = $request->amount;

        $event_organizer = Event

        /**
         *
         * TODO::based on the event_organizer id fetch their
         *       - first and last name
         *       - phone number
         *       - email
         *
         */

        $merchantProperties = [
            "merchantTransactionID"   => "".uniqid("Trans:"),
            "customerFirstName"       => 'john',
            "customerLastName"        => "njoro",
            "MSISDN"                  => "254711110128",
            "customerEmail"           => "johnnjoroge40@gmail.com",
            "amount"                  => "1000",
            "currencyCode"            => "KES",
            "accountNumber"           => "123456",
            "serviceCode"             => "APISBX3857",
            "dueDate"                 => "2018-08-24 11:09:59",
            "serviceDescription"      => "Getting service/good x",
            "accessKey"               => "$2a$08$Ga/jSxv1qturlAr8SkHhzOaprXnfOJUTqB6fLRrc/0nSYpRlAd96e",
            "countryCode"             => "KE",
            "languageCode"            => "en",
            "successRedirectUrl"      => "{{route('success_url')}}",
            "failRedirectUrl"         => "{{route('failure_url')}}",
            "paymentWebhookUrl"       => "http://ef1ebeb0.ngrok.io/payments/process_payment"
        ];

        $payload = json_decode($request->getContent());

        //The encryption method to be used
        $encrypt_method = "AES-256-CBC";

        // Hash the secret key
        $key = hash('sha256', $this->secretKey);

        // Hash the iv - encrypt method AES-256-CBC expects 16 bytes
        $iv = substr(hash('sha256', $this->ivKey), 0, 16);
        $encrypted = openssl_encrypt(
            json_encode($payload, true), $encrypt_method, $key, 0, $iv
        );

        //Base 64 Encode the encrypted payload
        $encryptedPayload = base64_encode($encrypted);

        return response()->json([
            'params' => $encryptedPayload,
            'accessKey' => $payload->accessKey,
            'countryCode' => $payload->countryCode
        ]);

    }

    public function success(Request $request)
    {
        try {
            $payload = $request->getContent();
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'success',
                'response' => $payload
            ]);
            //log the payment
            logger("PAYMENT SUCCESS::  " . $payload);

            //display a success message to the user
            return view('payments.success');
        } catch ( \Exception $exception ) {
            logger("PAYMENT SUCCESS error:: ".$exception->getMessage()."\nTrace::: ".$exception->getTraceAsString());
        }
    }

    public function failure(Request $request)
    {
        try {
            $payload = $request->getContent();
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'failure',
                'response' => $payload
            ]);
            //log the payment
            logger("PAYMENT FAILURE::  " . $payload);

            //display a success message to the user
            return view('payments.failure');
        } catch ( \Exception $exception ) {
            logger("PAYMENT FAILURE error:: ".$exception->getMessage()."\nTrace::: ".$exception->getTraceAsString());
        }
    }

    public function processPayment(Request $request)
    {
        try {
            $payload = $request->getContent();

            //save the response to the db
            PaymentResponse::create([
                'type'     => 'webhook',
                'response' => $payload
            ]);

            //log the response
            logger("PROCESS PAYMENT::  " . $payload);

            //confirm whether  the payment should be accepted or not

            return response()->json([
                'checkoutRequestID'=>$payload->checkoutRequestID,
                'merchantTransactionID'=>$payload->merchantTransactionID,
                'statusCode'=> $payload->requestStatusCode == 178 ? 183 : 180,
                'statusDescription' => $payload->requestStatusCode == 178 ? "Payment Accepted" : "Payment declined",//$payload->requestStatusDescription,
                'receiptNumber' => $payload->merchantTransactionID,
            ]);

        } catch ( \Exception $exception) {
            logger("PAYMENT PROCESS error:: ".$exception->getMessage()."\nTrace::: ".$exception->getTraceAsString());
        }
    }


}
