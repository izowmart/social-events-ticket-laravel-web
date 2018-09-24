<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Traits\UniversalMethods;
use App\PaymentResponse;
use App\TicketCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MulaPaymentController extends Controller
{

    public $secretKey;
    public $ivKey;

    public function __construct()
    {
        $this->secretKey = "rHkgZYVKnCGLXFRb";
        $this->ivKey = "HtJBgFPQh3qwcRmk";

    }

    public function index()
    {
        /**
        $amount = "1000";

        $ticket_customer = TicketCustomer::findOrFail(1);

        /**
         *
         * TODO::based on the event_organizer id fetch their
         *       - first and last name
         *       - phone number
         *       - email
         *
         *
        $payload = [
            "merchantTransactionID" => "" . uniqid("Trans:"),
            "customerFirstName"     => $ticket_customer->first_name,
            "customerLastName"      => $ticket_customer->last_name,
            "MSISDN"                => $ticket_customer->phone_number,
            "customerEmail"         => $ticket_customer->email,
            "amount"                => $amount,
            "currencyCode"          => "KES",
            "accountNumber"         => "123456",
            "serviceCode"           => "APISBX3857",
            "dueDate"               => "2018-08-24 11:09:59",
            "serviceDescription"    => "Getting service/good x",
            "accessKey"             => '$2a$08$Ga/jSxv1qturlAr8SkHhzOaprXnfOJUTqB6fLRrc/0nSYpRlAd96e',
            "countryCode"           => "KE",
            "languageCode"          => "en",
            "successRedirectUrl"    => "http://dc91f14b.ngrok.io/payments/success_url",
            "failRedirectUrl"       => "http://dc91f14b.ngrok.io/payments/failure_url",
            "paymentWebhookUrl"     => "http://dc91f14b.ngrok.io/payments/process_payment"
        ];


        return view('welcome',compact('payload'));
         */

        return view('welcome');

    }

    public function encryptData(Request $request)
    {
       $data_array = (array)json_decode($request->getContent());

        $validator = Validator::make($data_array,
            [
                'event_id' => 'required|integer|exists:events,id',
                'customer_id' => 'required|integer|exists:ticket_customers,id',
            ],
            [
                'event_id.required' => 'event id required',
                'event_id.integer' => 'event id integer',
                'event_id.exists' => 'event id must exists',
                'customer_id.required' => 'customer id required',
                'customer_id.integer' => 'customer id integer',
                'customer_id.exists' => 'customer id exists',

            ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'failed because of '.UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray())
            ]);
        }


        $event_id = $data_array['event_id'];
        $ticket_customer_id = $data_array['customer_id'];

        $event = Event::find($event_id);
        $ticket_customer = TicketCustomer::find($ticket_customer_id);

        $payload = [
            "merchantTransactionID" => "" . uniqid("Trns:",true),
            "customerFirstName"     => $ticket_customer->first_name,
            "customerLastName"      => $ticket_customer->last_name,
            "MSISDN"                => $ticket_customer->phone_number,
            "customerEmail"         => $ticket_customer->email,
            "amount"                => "100", //TODO::get the amount for the type of ticket the customer has decided to purchase
            "currencyCode"          => "KES",
            "accountNumber"         => "123456",
            "serviceCode"           => "APISBX3857",
            "dueDate"               => "2018-10-24 11:09:59", //TODO::this is to be replaced by the ticket_sale_end_date_time
            "serviceDescription"    => "Payment for ".$event->name,
            "accessKey"             => '$2a$08$Ga/jSxv1qturlAr8SkHhzOaprXnfOJUTqB6fLRrc/0nSYpRlAd96e',
            "countryCode"           => "KE",
            "languageCode"          => "en",
            "successRedirectUrl"    =>  route("success_url"),
            "failRedirectUrl"       =>  route("failure_url"),
            "paymentWebhookUrl"     =>  route("process_payment"),
        ];




        //$payload = json_decode($request->getContent());

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
            'accessKey' => $payload['accessKey'],
            'countryCode' => $payload['countryCode']
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

            $result = json_decode($payload);
            //confirm whether the payment should be accepted or not
            //check whether the MSISDN is recognized
            if ($result->MSISDN)


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
