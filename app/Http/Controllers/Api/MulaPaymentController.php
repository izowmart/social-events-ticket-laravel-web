<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/25/18
 * Time: 6:15 PM
 */

namespace App\Http\Controllers\Api;


use App\Event;
use App\Http\Traits\UniversalMethods;
use App\PaymentRequest;
use App\PaymentResponse;
use App\TicketCustomer;
use Illuminate\Support\Facades\Request;

class MulaPaymentController
{
    public $secretKey;
    public $ivKey;

    public function __construct()
    {
        $this->secretKey = "rHkgZYVKnCGLXFRb";
        $this->ivKey = "HtJBgFPQh3qwcRmk";

    }

    public function initiate_payment($user_id, $event_id)
    {

//        $validator = Validator::make($data_array,
//            [
//                'event_id' => 'required|integer|exists:events,id',
//                'customer_id' => 'required|integer|exists:ticket_customers,id',
//            ],
//            [
//                'event_id.required' => 'event id required',
//                'event_id.integer' => 'event id integer',
//                'event_id.exists' => 'event id must exists',
//                'customer_id.required' => 'customer id required',
//                'customer_id.integer' => 'customer id integer',
//                'customer_id.exists' => 'customer id exists',
//
//            ]);
//
//        if ($validator->fails()) {
//            return response()->json([
//                'message' => 'failed because of '.UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray())
//            ]);
//        }


        //$event_id = $data_array['event_id'];
        $ticket_customer_id = $user_id;//$data_array['customer_id'];

        $event = Event::find($event_id);
        $ticket_customer = TicketCustomer::find($ticket_customer_id);


        $payload = [
            "merchantTransactionID" => now()->timestamp."".uniqid(),
            "customerFirstName"     => $ticket_customer->first_name,
            "customerLastName"      => $ticket_customer->last_name,
            "MSISDN"                => UniversalMethods::formatPhoneNumber($ticket_customer->phone_number),
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
            "successRedirectUrl"    =>  route("mobile_success_url"),
            "failRedirectUrl"       =>  route("mobile_failure_url"),
            "paymentWebhookUrl"     =>  route("process_payment"),
        ];

        //attach a pending payment request
        PaymentRequest::create([
            'merchantTransactionID'     => $payload['merchantTransactionID'],
            'MSISDN'                    =>$payload['MSISDN'],
            'customerEmail'             => $payload['customerEmail'],
            'amount'                    => $payload['amount']
        ]);

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

//        /*
        return response()->json([
            'success'   =>  true,
            'message'   => 'params fetched successfully',
            'datum'     => [
                'params' => $encryptedPayload,
                'accessKey' => $payload['accessKey'],
                'countryCode' => $payload['countryCode']
            ]
            ]
        );
//         */

        /*
        $data =json_encode( [
            'params' => $encryptedPayload,
            'accessKey' => $payload['accessKey'],
            'countryCode' => $payload['countryCode']
        ]);

        return view('payments.mobile',compact('data'));
        */
    }

}