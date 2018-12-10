<?php
/**
 * Created by PhpStorm.
 * User: phillip
 * Date: 9/6/18
 * Time: 1:24 PM
 */

namespace App\Http\Traits;


use App\PaymentRequest;
use App\Ticket;
use App\TicketCategoryDetail;
use App\TicketPurchaseRequest;
use App\TicketScan;
use Carbon\Carbon;

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
        /**
         * PRODUCTION
         */
        $ivKey = "0mdHcgBP4ABYeADK",
        $secretKey = "spjYntujBuOHfulJ"

        /*
         * TEST*
        $ivKey = "cpZdGWvh4rfB78C9",
        $secretKey = "CXNjbwmtVcfDYBTh"
        */
    ) {

        $merchantTransactionID = now()->timestamp . "" . uniqid();

        //create a pending payment request
        $payment_request = PaymentRequest::create([
            'merchantTransactionID' => $merchantTransactionID,
            'amount'                => $data_array['subtotal'],
            'ticket_customer_id'    => $ticket_customer->id,
            'event_id'              => $event_id
        ]);


        //check that the tickets sought are available
        foreach ($data_array as $index => $item) {
            if (stristr($index, "quantity") === false) {
                continue;
            } else {
                //get the slug
                $ticket_category_slug = substr($index, 0, strpos($index, '_'));

                //get the ticket category details record
                $ticket_category_detail = TicketCategoryDetail::join('ticket_categories', 'ticket_categories.id', '=',
                    'ticket_category_details.category_id')
                    ->where('event_id', $event_id)
                    ->where('slug', $ticket_category_slug)
                    ->select('ticket_category_details.category_id','ticket_category_details.id')
                    ->first();

                //check the remaining tickets
                //pass the event_id and the category_id
                $tickets_available = UniversalMethods::getRemainingCategoryTickets($event_id,
                    $ticket_category_detail->category_id);

                //get the sought tickets
                $tickets_sought = (int)$item;

                //if sought is more than available
                //return null to let the user choose the tickets again,
                //otherwise let's proceed and create the ticketpurchaserequest records
                if ($tickets_sought > $tickets_available) {
                    return [null, null];
                }else{
                    $record_to_save = [
                        'payment_request_id'        => $payment_request->id,
                        'ticket_category_detail_id' => $ticket_category_detail->id,
                        'tickets_count'             => (int)$item
                    ];

                    //save the ticket categories to be purchased with their counts
                    TicketPurchaseRequest::create($record_to_save);
                }
            }

        }

        //let's process the encrypted payload
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

            "dueDate"               => $data_array['ticket_sale_end_date_time'], //ticket_sale_end_date_time
            "serviceDescription"    => "Payment for " . $event->name,
            /**
             * TEST
             *
            "accessKey"             => '$2a$08$FIRIU0JS9GESx6ePn/wsUuX4aq2HAsJ16qmz/bTYbT4j7lZ9R6r1W',
            "serviceCode"           => "FIKDEV8910",
             */

            /**
             * PRODUCTION
             */
            "accessKey"             => "ZmQIqoH2u6GSfjm6vPNwhIVUXkj9W8ity6fMdEoyhnVDdYMqjckDlWKAN34U",
            "serviceCode"           => "FIKAPLACES",
            "payerClientCode"      => "SAFKE", //this determines which option the user can pay with
            //end of production
            "countryCode"           => "KE",
            "languageCode"          => "en",
            "successRedirectUrl"    => $from_web ? route("success_url") : route('mobile_success_url'),
            "failRedirectUrl"       => $from_web ? route("failure_url") : route('mobile_failure_url'),
            "paymentWebhookUrl"     => route("process_payment"),
        ];

        logger("payload before encryption: " . json_encode($payload));


//        //keep a record of the tickets quantities to be bought and their prices
//        foreach ($data_array as $index => $item) {
//            if (stristr($index, "quantity") === false) {
//                continue;
//            } else {
//                //get the slug
//                $ticket_category_slug = substr($index, 0, strpos($index, '_'));
//
//                //get the ticket category
////                $ticket_category = TicketCategory::where('slug', $ticket_category_slug)->first();
//
//                //get the ticket category details record
//                $ticket_category_details = TicketCategoryDetail::join('ticket_categories', 'ticket_categories.id', '=',
//                    'ticket_category_details.category_id')
//                    ->where('event_id', $event_id)
//                    ->where('slug', $ticket_category_slug)
//                    ->select('ticket_category_details.id')
//                    ->first();
//
//
//                $record_to_save = [
//                    'payment_request_id'        => $payment_request->id,
//                    'ticket_category_detail_id' => $ticket_category_details->id,
//                    'tickets_count'             => (int)$item
//                ];
//
//                //save the ticket categories to be purchased with their counts
//                TicketPurchaseRequest::create($record_to_save);
//            }
//        }

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
    /**
     * the remaining = available - sold
     * @param $event_id
     * @param $ticket_category_id
     *
     * @return \Illuminate\Http\JsonResponse
     * to call me: UniversalMethods::getRemainingCategoryTickets(1,1)
     */
    public static function getRemainingCategoryTickets($event_id,$ticket_category_id)
    {
        //available tickets
         $ticket_category_details = TicketCategoryDetail::where('event_id', $event_id)
            ->where('category_id', $ticket_category_id)
            ->first();

        $total_available_tickets = (int) $ticket_category_details->no_of_tickets;

        //sold tickets
        $sold_tickets = Ticket::where('ticket_category_detail_id','=', $ticket_category_details->id)
            ->count();

        //remaining tickets
        $remaining_tickets = $total_available_tickets - $sold_tickets;
        // $data = [$total_available_tickets, $sold_tickets, $remaining_tickets];
        //TODO::optimize this to one query
        //FIXME:: there's a bug with how to get the count of the rows
//        $data = Ticket::join('ticket_categories','tickets.ticket_category_id','=','ticket_categories.id')
//            ->join('ticket_category_details','ticket_category_details.category_id','=','ticket_categories.id')
//            ->where('tickets.event_id','=',$event_id)
//            ->where('tickets.ticket_category_id','=',$ticket_category_id)
//            ->selectRaw('COUNT(tickets.event_id) as remaining_tickets')
//            ->first();


        return 
        // response()->json(
            $remaining_tickets;
        // );
    }

    /* generate a password that matches the criterion:
    * at least 6 character, with an upper and lower case letter and
    * a number
    *
    * @return string
    */
    public static function passwordGenerator()
    {
        $uppercaseLetters = ['P', 'X', 'A', 'N'];
        $lowercaseWord = substr(str_shuffle("qwertyuiopasdfghjklzxcvbnm"),0,4);
        $password = $uppercaseLetters[ array_rand($uppercaseLetters, 1) ] .  mt_rand(1,
                9) . $lowercaseWord;

        return $password;
    }


    /**
     * @param $event_dates
     *
     * @return string
     */
    public static function getEventDateTimeStr($event_dates): string
    {
        $event_times= "";
        foreach ($event_dates as  $event_date) {
            $start_date_time = Carbon::parse($event_date->start);

            $start_year = $start_date_time->year;
            $start_month = $start_date_time->month;
            $start_day = $start_date_time->day;
            $start_date = Carbon::create($start_year, $start_month, $start_day);

            $end_date_time = Carbon::parse($event_date->end);

            $end_year = $end_date_time->year;
            $end_month = $end_date_time->month;
            $end_day = $end_date_time->day;
            $end_date = Carbon::create($end_year, $end_month, $end_day);

            $same = $start_date->eq($end_date);

            $event_time = $same ? Carbon::parse($event_date->start)->toFormattedDateString() . " " . Carbon::parse($event_date->start)->format('h:i A') . " - " . Carbon::parse($event_date->end)->format('h:i A') : Carbon::parse($event_date->start)->toFormattedDateString() . " " . Carbon::parse($event_date->start)->format('h:i A') . " - " . Carbon::parse($event_date->end)->toFormattedDateString() . " " . Carbon::parse($event_date->end)->format('h:i A');
            $event_times .= $event_time." ,";
        }
        return $event_times;
    }

    /**
     * @param $event_dates
     *
     * @return array
     */
    public static function getEventDateTimeArray($event_dates): array
    {
        $event_times= [];
        foreach ($event_dates as  $event_date) {
            $start_date_time = Carbon::parse($event_date->start);

            $start_year = $start_date_time->year;
            $start_month = $start_date_time->month;
            $start_day = $start_date_time->day;
            $start_date = Carbon::create($start_year, $start_month, $start_day);

            $end_date_time = Carbon::parse($event_date->end);

            $end_year = $end_date_time->year;
            $end_month = $end_date_time->month;
            $end_day = $end_date_time->day;
            $end_date = Carbon::create($end_year, $end_month, $end_day);

            $same = $start_date->eq($end_date);

            $event_time = $same ? Carbon::parse($event_date->start)->toFormattedDateString() . " " . Carbon::parse($event_date->start)->format('h:i A') . " - " . Carbon::parse($event_date->end)->format('h:i A') : Carbon::parse($event_date->start)->toFormattedDateString() . " " . Carbon::parse($event_date->start)->format('h:i A') . " - " . Carbon::parse($event_date->end)->toFormattedDateString() . " " . Carbon::parse($event_date->end)->format('h:i A');
            array_push($event_times , $event_time);
        }
        return $event_times;
    }
}