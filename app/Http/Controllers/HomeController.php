<?php

namespace App\Http\Controllers;

use App\PaymentResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }

    public function home_user()
    {
        return view('user.home');
    }

    public function home_scanner()
    {
        return view('scanner.home');
    }

    public $secretKey;
    public $ivKey;

    public function __construct()
    {
        $this->secretKey = "rHkgZYVKnCGLXFRb";
        $this->ivKey = "HtJBgFPQh3qwcRmk";

    }

//    public function index()
//    {
//        $amount = "1000";
//
//        $ticket_customer = TicketCustomer::findOrFail(1);
//
//        /**
//         *
//         * TODO::based on the event_organizer id fetch their
//         *       - first and last name
//         *       - phone number
//         *       - email
//         *
//         */
//        $payload = [
//            "merchantTransactionID" => "" . uniqid("Trans:"),
//            "customerFirstName"     => $ticket_customer->first_name,
//            "customerLastName"      => $ticket_customer->last_name,
//            "MSISDN"                => $ticket_customer->phone_number,
//            "customerEmail"         => $ticket_customer->email,
//            "amount"                => $amount,
//            "currencyCode"          => "KES",
//            "accountNumber"         => "123456",
//            "serviceCode"           => "APISBX3857",
//            "dueDate"               => "2018-08-24 11:09:59",
//            "serviceDescription"    => "Getting service/good x",
//            "accessKey"             => '$2a$08$Ga/jSxv1qturlAr8SkHhzOaprXnfOJUTqB6fLRrc/0nSYpRlAd96e',
//            "countryCode"           => "KE",
//            "languageCode"          => "en",
//            "successRedirectUrl"    => "http://dc91f14b.ngrok.io/payments/success_url",
//            "failRedirectUrl"       => "http://dc91f14b.ngrok.io/payments/failure_url",
//            "paymentWebhookUrl"     => "http://dc91f14b.ngrok.io/payments/process_payment"
//        ];
//
//
//        return view('welcome',compact('payload'));
//
//    }

    public function encryptData(Request $request)
    {
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
            logger("PAYMENT SUCCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
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
            logger("PAYMENT FAILURE error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
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
            $result = json_decode($payload);
            return response()->json([
                'checkoutRequestID'     => $result->checkoutRequestID,
                'merchantTransactionID' => $result->merchantTransactionID,
                'statusCode'            => $result->requestStatusCode == 178 ? 183 : 180,
                'statusDescription'     => $result->requestStatusCode == 178 ? "Payment Accepted" : "Payment declined",
                'receiptNumber'         => $result->merchantTransactionID,
            ]);

        } catch ( \Exception $exception ) {
            logger("PAYMENT PROCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
            return response()->json([
                'error' => 'payment error: '.$exception->getMessage()
            ]);
        }
    }
}
