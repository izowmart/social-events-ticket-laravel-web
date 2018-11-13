<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Traits\UniversalMethods;
use App\Mail\TicketsBought;
use App\PaymentRequest;
use App\PaymentResponse;
use App\Ticket;
use App\TicketCategoryDetail;
use App\TicketCustomer;
use App\TicketPurchaseRequest;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        return view('payments.button');
    }

    /**
     * we need to pass
     * event_id, event_ticket_cattegory_id & its no of ticke
     */

    public function encryptData(Request $request)
    {
        $data_array = [];

      
       parse_str($request->getContent(),$data_array);

//       return response()->json(['data_array'=> $data_array]);

    //    dd($request->all());
        $validator = Validator::make($data_array, [
             'first_name'=>'required|string',
             'last_name'=>'required|string',
             'email'=>'required|email',
             'phone'=>'required',
             'event_id'=>'required|integer',
             'subtotal'=>'required|integer',
             'ticket_sale_end_date_time'=>'required'
         ]);

         if($validator->fails()){
             return redirect()->back()->withInput()->withErrors($validator->errors());
         }
//             return response()->json([
//                 'message' => 'failed because of '.UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray())
//             ]);
//         }

        $user = User::where('email',$data_array['email'])->first();
        $ticket_customer = TicketCustomer::updateOrCreate(
            [
                'email'         => $data_array['email'],
            ],
            [
                'phone_number'  => UniversalMethods::formatPhoneNumber($data_array['phone']),
                'first_name'    => $data_array['first_name'],
                'last_name'     => $data_array['last_name'],
                'user_id'       => $user != null ? $user->id : 0,
            ]
        );

        $event_id = $data_array['event_id'];

        $event = Event::find($event_id);
//        $ticket_customer = TicketCustomer::find($ticket_customer->id);

        $merchantTransactionID = now()->timestamp . "" . uniqid();
        $payload = [
            "merchantTransactionID" => $merchantTransactionID,
            "customerFirstName"     => $ticket_customer->first_name,
            "customerLastName"      => $ticket_customer->last_name,
            "MSISDN"                => UniversalMethods::formatPhoneNumber($ticket_customer->phone_number),
            "customerEmail"         => $ticket_customer->email,
            "amount"                => $data_array['subtotal'], //TODO::get the amount for the type of ticket the customer has decided to purchase
            "currencyCode"          => "KES",
            "accountNumber"         => "123456",
            "serviceCode"           => "APISBX3857",
            "dueDate"               => $data_array['ticket_sale_end_date_time'], //TODO::this is to be replaced by the ticket_sale_end_date_time
            "serviceDescription"    => "Payment for ".$event->name,
            "accessKey"             => '$2a$08$Ga/jSxv1qturlAr8SkHhzOaprXnfOJUTqB6fLRrc/0nSYpRlAd96e',
            "countryCode"           => "KE",
            "languageCode"          => "en",
            "successRedirectUrl"    =>  route("success_url"),
            "failRedirectUrl"       =>  route("failure_url"),
            "paymentWebhookUrl"     =>  route("process_payment"),
        ];

        //create a pending payment request
        $payment_request = PaymentRequest::create([
            'merchantTransactionID'     => $payload['merchantTransactionID'],
            'amount'                    => $payload['amount'],
            'ticket_customer_id'        => $ticket_customer->id,
            //            'vip_quantity'              => key_exists('vip_quantity',$data_array) ? $data_array['vip_quantity'] : 0,
            //            'regular_quantity'          => key_exists('regular_quantity',$data_array) ? $data_array['regular_quantity'] : 0,
            'event_id'                  => $event_id
        ]);

        //keep a record of the tickets quantities to be bought and their prices
        $records_to_save = [];
        foreach ($data_array as $index => $item) {
            if (stristr($index, "quantity") === FALSE) {
                continue;
            }else{
                //get the slug
                $ticket_category_slug = substr($index,0,strpos($index,'_'));

                //get the ticket category
//                $ticket_category = TicketCategory::where('slug', $ticket_category_slug)->first();

                //get the ticket category details record
                $ticket_category_details = TicketCategoryDetail::join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
                    ->where('event_id', $event_id)
                    ->where('slug', $ticket_category_slug)
                    ->select('ticket_category_details.id')
                    ->first();


                $record_to_save = [
                    'payment_request_id'                => $payment_request->id,
                    'ticket_category_detail_id'         => $ticket_category_details->id,
                    'tickets_count'                     => (int)$item
                ];

                //save the ticket categories to be purchased with their counts
                TicketPurchaseRequest::create($record_to_save);
            }
        }

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

    public function processPayment(Request $request)
    {
        $payload = $request->getContent();
        $result = json_decode($payload);
        try {


            //save the response to the db
            PaymentResponse::create([
                'type'     => 'webhook',
                'response' => $payload
            ]);

            //log the response
            logger("PROCESS PAYMENT::  " . $payload);


            //confirm whether the payment should be accepted or not
            //check whether the merchantTransactionID & the amounts are recognized
            $pending_payment_request = PaymentRequest::where('merchantTransactionID', $result->merchantTransactionID)
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($result->MSISDN))
//                ->where('amount','=', $result->amountPaid)
                ->where('payment_request_status', '=',0)
                ->first();

            if ($pending_payment_request != null) {

                //accept the payment
                return response()->json([
                    'checkoutRequestID'     => $result->checkoutRequestID,
                    'merchantTransactionID' => $result->merchantTransactionID,
                    'statusCode'            => 183,
                    'statusDescription'     => "Successful Payment",
                    'receiptNumber'         => $result->merchantTransactionID,
                ]);
            }else{
                //reject the payment
                return response()->json([
                    'checkoutRequestID'     => $result->checkoutRequestID,
                    'merchantTransactionID' => $result->merchantTransactionID,
                    'statusCode'            => 180,
                    'statusDescription'     => "Payment declined",
                    'receiptNumber'         => $result->merchantTransactionID,
                ]);
            }

        } catch ( \Exception $exception ) {
            logger("PAYMENT PROCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
            //reject the payment
            return response()->json([
                'checkoutRequestID'     => $result->checkoutRequestID,
                'merchantTransactionID' => $result->merchantTransactionID,
                'statusCode'            => 180,
                'statusDescription'     => "Payment declined",
                'receiptNumber'         => $result->merchantTransactionID,
            ]);
        }
    }

    public function success(Request $request)
    {
        try {
//            $payload = $request->getContent();

//            return response()->json([$payload,$payload['merchantTransactionID']);
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'success',
                'response' => $request->getContent()
            ]);

            //log the payment
            logger("PAYMENT SUCCESS::  " .  $request->getContent());


            //update the pending payment request record
            $approved_payment_request = $this->approvePendingPaymentRequest($request);

            //create a tickets record against that event
            //based on their types
            $ticket_purchase_requests = TicketPurchaseRequest::join('ticket_category_details','ticket_purchase_requests.ticket_category_detail_id','=','ticket_category_details.id')
                ->join('ticket_categories','ticket_categories.id','=','ticket_category_details.category_id')
                ->where('payment_request_id', $approved_payment_request->id)
                ->select('ticket_category_details.category_id','ticket_categories.name','ticket_purchase_requests.tickets_count')
                ->get();

//            $regular_tickets_count = $approved_payment_request->regular_quantity;
//            $vip_tickets_count = $approved_payment_request->vip_quantity;

            $event_id = $approved_payment_request->event_id;

            $ticket_customer_id = $approved_payment_request->ticket_customer_id;
            $approved_payment_request_id = $approved_payment_request->id;

            $tickets_array = [];
            $pdfs_array = [];

            foreach ($ticket_purchase_requests as $ticket_purchase_request) {
                /*
                 * for each ticket type, based on the number of tickets purchased
                 * create tickets records--> this is for each of the ticket generated by the system
                 *              a. generate the qr code & convert it so an image for android
                 *              b. generate a pdf ticket with the qr code embedded for emailing
                 *
                 */

                $ticket_type_count = $ticket_purchase_request->tickets_count;

                //loop through given the number of tickets per category
                for ($i = 0; $i < $ticket_type_count; $i++) {
                    $ticket_array =  $this->createTickets($ticket_purchase_request->name,$event_id, $ticket_purchase_request->category_id, $ticket_customer_id,
                        $approved_payment_request_id);


                    $pdf_array = $ticket_array['pdf_format_url'];

                    $tickets_array[] = $ticket_array;
                    $pdfs_array[] = url('bought_tickets/pdfs').'/'.$pdf_array;
                }
            }

            if (count($tickets_array)>0) {

                //TODO:: save the tickets records

                //then we send the email with the relevant pdfs
                $ticket_customer = TicketCustomer::find($ticket_customer_id);
                $email_data = [
                    'name'      => $ticket_customer->first_name,
                    'event'     => Event::find($event_id),
                    'files'     => $pdfs_array
                ];

                Mail::to($ticket_customer)->queue(new TicketsBought($email_data));
            }

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

            //update the pending payment request record with failed status
            $this->declinePendingPaymentRequest($request);


            //display a success message to the user
            return view('payments.failure');

        } catch ( \Exception $exception ) {
            logger("PAYMENT FAILURE error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
        }
    }

    /*
     * Mobile METHODS
     */
    public function mobile_success(Request $request)
    {
        try {
            $payload = $request->getContent();
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'mobile_success',
                'response' => $payload
            ]);
            //log the payment
            logger("PAYMENT SUCCESS::  " . $payload);

            //approve the pending payment request
            $approved_pending_payment_request = $this->approvePendingPaymentRequest($request);

            //raise tickets for the user
//            $this->createTickets($approved_pending_payment_request);

            //TODO: 3. generate and email tickets (with branding) as pdf attachment(s)
            //TODO: 4. create image for the tickets for the mobile app ?? do a table for that holds the url for this??


            //display a success message to the user
            return view('payments.mobile_success');

        } catch ( \Exception $exception ) {
            logger("PAYMENT SUCCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
        }
    }

    public function mobile_failure(Request $request)
    {
        try {
            $payload = $request->getContent();
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'mobile_failure',
                'response' => $payload
            ]);
            //log the payment
            logger("PAYMENT FAILURE::  " . $payload);

            //update the pending payment request record with failed status
            $this->declinePendingPaymentRequest($request);

            //display a success message to the user
            return view('payments.mobile_failure');
        } catch ( \Exception $exception ) {
            logger("PAYMENT FAILURE error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
        }
    }
    /* end mobile methods*/

    /**
     * Helper methods
     */
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function approvePendingPaymentRequest(Request $request)
    {
        $pending_payment_request = PaymentRequest::where('merchantTransactionID', $request->merchantTransactionID)
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($payload->MSISDN))
//            ->where('amount', '=', $request->amountPaid)
            ->where('payment_request_status', '=', 0)
            ->first();

        $pending_payment_request->payment_request_status = 1;
        $pending_payment_request->save();

        return $pending_payment_request;
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function declinePendingPaymentRequest(Request $request): void
    {
        $pending_payment_request = PaymentRequest::where('merchantTransactionID', $request->merchantTransactionID)
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($payload->MSISDN))
            ->where('amount', '=', $request->amountPaid)
            ->where('payment_request_status', '=', 0)
            ->first();

        $pending_payment_request->payment_request_status = 2;
        $pending_payment_request->save();
    }

    /**
     * @param $ticket_type
     * @param $event_id
     * @param $ticket_category_id
     * @param $ticket_customer_id
     * @param $approved_payment_request_id
     *
     * @return mixed
     */
    public function createTickets($ticket_type,$event_id, $ticket_category_id, $ticket_customer_id, $approved_payment_request_id)
    {
        $payload = "" . now()->timestamp . "" . $event_id . "" . $ticket_category_id . "" . $ticket_customer_id . "" . $approved_payment_request_id;
        $unique_ticket_identifier = hash_hmac('sha256', strtolower(trim($payload)), config('app.key'));

        $event = Event::find($event_id);

        $event_dates = $event->events_dates;

        /*
         * generate qr code for each ticket and save it as image
         * this is to be used
         * 1. on the pdf to be shared via email
         * 2. on the app as an image...
         */

        $qr_code_images_dir = public_path('bought_tickets/qr_code_images');

        if (!file_exists($qr_code_images_dir)) {
            mkdir($qr_code_images_dir, 0777, true);
        }

        $qr_code_image_name = now()->timestamp . ".png";
        $qr_code = QrCode::format('png')->size(100)->generate('http://fikaplaces.com/tickets/' . $unique_ticket_identifier,
            $qr_code_images_dir.'/' . $qr_code_image_name);

        /*
        * Prepare the content for the ticket pdf template
        */
        $ticket_template_data = [
            'event_name'                => $event->name,
            'event_location'            => $event->location,
            'event_start_date_time'     => $event_dates->first()->start,
            'event_end_date_time'       => $event_dates->first()->end,
            'ticket_type'               => $ticket_type,
            'ticket_qr_code_image_name' => $qr_code_image_name,
        ];


        //generate and save pdf
        $ticket_name = "T" . now()->timestamp . uniqid();
        $pdf_name = $ticket_name . ".pdf";
        $pdf_dir = public_path('bought_tickets/pdfs');

        if (!file_exists($pdf_dir)) {
            mkdir($pdf_dir, 0777, true);
        }

        $ticket_pdf_path = $pdf_dir . '/' . $pdf_name;

        //create the pdf for each ticket to be shared via email
//        return PDF::loadView( 'tickets.display-tickets', $ticket_template_data)->save( $pdf_dir.'/'.$pdf_name )->stream($pdf_name);
        $pdf = PDF::loadView('tickets.display-tickets', $ticket_template_data)->save($ticket_pdf_path);


        $data = [
            'event_id'           => $event_id,
            'ticket_customer_id' => $ticket_customer_id,
            'validation_token'   => $unique_ticket_identifier,
            'qr_code_image_url'  => $qr_code_image_name,
            'pdf_format_url'     => $pdf_name,
            'ticket_category_id' => $ticket_category_id
        ];

        return $data;
    }

        /* end helper methods */
}
