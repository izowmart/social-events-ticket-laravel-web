<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/6/18
 * Time: 1:24 PM
 */

namespace App\Http\Traits;


use App\PaymentRequest;
use App\TicketCategoryDetail;
use App\TicketPurchaseRequest;

trait UniversalMethods
{
    public static function getValidationErrorsAsString($errorArray)
    {
        $errorArrayTemp = [];
        $error_strings = "";
        foreach ($errorArray as $index => $item) {
            $errStr = $item[0];
            array_push($errorArrayTemp, $errStr);
        }
        if (!empty($errorArrayTemp)) {
            $error_strings = implode('. ', $errorArrayTemp);
        }

        return $error_strings;
    }

    public static function formatPhoneNumber($phone_number)
    {
        if(starts_with($phone_number, "7")){
            return "254".$phone_number;
        }elseif (starts_with($phone_number,"07")){
            return "254" . substr($phone_number, 1);
        } elseif (starts_with($phone_number,"+2547")){
            return substr($phone_number,1);
        }elseif (starts_with($phone_number,"2547")){
            return $phone_number;
        }

    }

    /**
     * @param        $ticket_customer
     * @param        $data_array
     * @param        $event
     * @param        $event_id
     * @param string $ivKey
     * @param string $secretKey
     *
     * @param bool   $from_web
     *
     * @return array
     */
    public static function process_mula_payments(
        $ticket_customer,
        $data_array,
        $event,
        $event_id,
        $from_web = true,
        $ivKey = "cpZdGWvh4rfB78C9",
        $secretKey = "CXNjbwmtVcfDYBTh"

    ) {
        $merchantTransactionID = now()->timestamp . "" . uniqid();
        $payload = [
            "merchantTransactionID" => $merchantTransactionID,
            "customerFirstName"     => $ticket_customer->first_name,
            "customerLastName"      => $ticket_customer->last_name,
            "MSISDN"                => UniversalMethods::formatPhoneNumber($ticket_customer->phone_number),
            "customerEmail"         => $ticket_customer->email,
            "amount"                => $data_array['subtotal'],
            //get the amount for the type of ticket the customer has decided to purchase
            "currencyCode"          => "KES",
            "accountNumber"         => "123456",
            "serviceCode"           => "FIKDEV8910",
            "dueDate"               => $data_array['ticket_sale_end_date_time'], //TODO::this is to be replaced by the ticket_sale_end_date_time
            "serviceDescription"    => "Payment for " . $event->name,
            "accessKey"             => '$2a$08$FIRIU0JS9GESx6ePn/wsUuX4aq2HAsJ16qmz/bTYbT4j7lZ9R6r1W',
            "countryCode"           => "KE",
            "languageCode"          => "en",
            "successRedirectUrl"    => $from_web ? route("success_url") : route('mobile_success_url'),
            "failRedirectUrl"       => $from_web ? route("failure_url") : route('mobile_failure_url'),
            "paymentWebhookUrl"     => route("process_payment"),
        ];

        //create a pending payment request
        $payment_request = PaymentRequest::create([
            'merchantTransactionID' => $payload['merchantTransactionID'],
            'amount'                => $payload['amount'],
            'ticket_customer_id'    => $ticket_customer->id,
            'event_id'              => $event_id
        ]);

        //keep a record of the tickets quantities to be bought and their prices
        foreach ($data_array as $index => $item) {
            if (stristr($index, "quantity") === false) {
                continue;
            } else {
                //get the slug
                $ticket_category_slug = substr($index, 0, strpos($index, '_'));

                //get the ticket category
//                $ticket_category = TicketCategory::where('slug', $ticket_category_slug)->first();

                //get the ticket category details record
                $ticket_category_details = TicketCategoryDetail::join('ticket_categories', 'ticket_categories.id', '=',
                    'ticket_category_details.category_id')
                    ->where('event_id', $event_id)
                    ->where('slug', $ticket_category_slug)
                    ->select('ticket_category_details.id')
                    ->first();


                $record_to_save = [
                    'payment_request_id'        => $payment_request->id,
                    'ticket_category_detail_id' => $ticket_category_details->id,
                    'tickets_count'             => (int)$item
                ];

                //save the ticket categories to be purchased with their counts
                TicketPurchaseRequest::create($record_to_save);
            }
        }

        //The encryption method to be used
        $encrypt_method = "AES-256-CBC";

        // Hash the secret key
        $key = hash('sha256', $secretKey);

        // Hash the iv - encrypt method AES-256-CBC expects 16 bytes
        $iv = substr(hash('sha256', $ivKey), 0, 16);
        $encrypted = openssl_encrypt(
            json_encode($payload, true), $encrypt_method, $key, 0, $iv
        );

        //Base 64 Encode the encrypted payload
        $encryptedPayload = base64_encode($encrypted);

        return [$payload, $encryptedPayload];

    }
}