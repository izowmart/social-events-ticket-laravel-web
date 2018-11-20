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
        $this->secretKey = "CXNjbwmtVcfDYBTh";
        $this->ivKey = "cpZdGWvh4rfB78C9";

    }

    public function index()
    {
        return view('payments.button');
    }

    /**
     * encrypt the data for sending to the mula endpoint to initiate the payment
     * for the client
     *
     */

    public function encryptData(Request $request)
    {
        try {
            $data_array = [];


            parse_str($request->getContent(), $data_array);

            $validator = Validator::make($data_array, [
                'first_name'                => 'required|string',
                'last_name'                 => 'required|string',
                'email'                     => 'required|email',
                'phone'                     => 'required',
                'event_id'                  => 'required|integer',
                'subtotal'                  => 'required|integer',
                'ticket_sale_end_date_time' => 'required'
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withInput()->withErrors($validator->errors());
            }

            $user = User::where('email', $data_array['email'])->first();
            $ticket_customer = TicketCustomer::updateOrCreate(
                [
                    'email' => $data_array['email'],
                ],
                [
                    'phone_number' => UniversalMethods::formatPhoneNumber($data_array['phone']),
                    'first_name'   => $data_array['first_name'],
                    'last_name'    => $data_array['last_name'],
                    'user_id'      => $user != null ? $user->id : 0,
                ]
            );

            $event_id = $data_array['event_id'];

            $event = Event::find($event_id);

            DB::beginTransaction();

            list($payload, $encryptedPayload) = UniversalMethods::process_mula_payments($ticket_customer, $data_array,
                $event, $event_id);

            DB::commit();
            return response()->json([
                'params'      => $encryptedPayload,
                'accessKey'   => $payload['accessKey'],
                'countryCode' => $payload['countryCode']
            ]);

        } catch ( \Exception $exception ) {
            DB::rollBack();
            return response()->json([
                'params'      => null,
                'accessKey'   => "",
                'countryCode' => ""
            ]);
        }
    }

    /*
     * this is the webhook that pinged by the MULA
     * system after receiving the payment from the client
     * --------------------    ------------------------
     *  we validate the payment and either accept or reject it
     *
     */

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
            //check whether the merchantTransactionID
            // & the amounts are recognized //TODO:: do we accept payments greater than the expected value???
            $pending_payment_request = PaymentRequest::where('merchantTransactionID', $result->merchantTransactionID)
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($result->MSISDN))
//                ->where('amount','=', $result->amountPaid)
                ->where('payment_request_status', '=', 0)
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
            } else {
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
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'success',
                'response' => $request->getContent()
            ]);

            //log the payment
            logger("PAYMENT SUCCESS::  " . $request->getContent());

            //process the successful payment
            //TODO:: push this to a queue as a job?
            $this->processSuccessfulPayment($request);

            //display a success message to the user
            return view('payments.success');

        } catch ( \Exception $exception ) {
            logger("PAYMENT SUCCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function processSuccessfulPayment(Request $request): void
    {
//update the pending payment request record
        $approved_payment_request = $this->approvePendingPaymentRequest($request);

        //get the pending ticket purchase requests records
        //given the approved payment request
        $ticket_purchase_requests = TicketPurchaseRequest::join('ticket_category_details',
            'ticket_purchase_requests.ticket_category_detail_id', '=', 'ticket_category_details.id')
            ->join('ticket_categories', 'ticket_categories.id', '=', 'ticket_category_details.category_id')
            ->where('payment_request_id', $approved_payment_request->id)
            ->select('ticket_category_details.category_id', 'ticket_categories.name',
                'ticket_purchase_requests.tickets_count')
            ->get();

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
                $ticket_array = $this->createTickets($ticket_purchase_request->name, $event_id,
                    $ticket_purchase_request->category_id, $ticket_customer_id,
                    $approved_payment_request_id);


                $pdf_array = $ticket_array['pdf_format_url'];

                $tickets_array[] = $ticket_array;
                $pdfs_array[] = url('bought_tickets/pdfs') . '/' . $pdf_array;
            }
        }

        if (count($tickets_array) > 0) {
            //then we send the email with the relevant pdfs
            $ticket_customer = TicketCustomer::find($ticket_customer_id);
            $email_data = [
                'name'  => $ticket_customer->first_name,
                'event' => Event::find($event_id),
                'files' => $pdfs_array
            ];

            Mail::to($ticket_customer)->queue(new TicketsBought($email_data));
        }
    }

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

    /*
     * Mobile METHODS
     */

    /**
     * @param $ticket_type
     * @param $event_id
     * @param $ticket_category_id
     * @param $ticket_customer_id
     * @param $approved_payment_request_id
     *
     * @return mixed
     */
    public function createTickets(
        $ticket_type,
        $event_id,
        $ticket_category_id,
        $ticket_customer_id,
        $approved_payment_request_id
    ) {
        $payload = "" . now()->timestamp . "" . $event_id . "" . $ticket_category_id . "" . $ticket_customer_id . "" . $approved_payment_request_id;
        $unique_ticket_identifier = hash_hmac('sha256', strtolower(trim($payload)), config('app.key'));

        $event = Event::find($event_id);

        $event_dates = $event->event_dates;

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
            $qr_code_images_dir . '/' . $qr_code_image_name);

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

        //save the tickets records
        Ticket::create($data);

        return $data;
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
    /* end mobile methods*/

    /**
     * Helper methods
     */

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function declinePendingPaymentRequest(Request $request): void
    {
        $pending_payment_request = PaymentRequest::where('merchantTransactionID', $request->merchantTransactionID) //FIXME::index the merchantTransacationID for faster querying???
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($payload->MSISDN))
//            ->where('amount', '=', $request->amountPaid) //FIXME::accept excess payments?? checkout the MULA refunds API...
            ->where('payment_request_status', '=', 0)
            ->first();

        $pending_payment_request->payment_request_status = 2;
        $pending_payment_request->save();
    }

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

            //process the successful payment
            $this->processPayment($request);

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
//
//    /**
//     * @param $ticket_customer
//     * @param $data_array
//     * @param $event
//     * @param $event_id
//     *
//     * @return array
//     */
//    public static function process_mula_payment($ticket_customer, $data_array, $event, $event_id): array
//    {
//        $merchantTransactionID = now()->timestamp . "" . uniqid();
//        $payload = [
//            "merchantTransactionID" => $merchantTransactionID,
//            "customerFirstName"     => $ticket_customer->first_name,
//            "customerLastName"      => $ticket_customer->last_name,
//            "MSISDN"                => UniversalMethods::formatPhoneNumber($ticket_customer->phone_number),
//            "customerEmail"         => $ticket_customer->email,
//            "amount"                => $data_array['subtotal'],
//            //get the amount for the type of ticket the customer has decided to purchase
//            "currencyCode"          => "KES",
//            "accountNumber"         => "123456",
//            "serviceCode"           => "FIKDEV8910",
//            "dueDate"               => $data_array['ticket_sale_end_date_time'],
//            //TODO::this is to be replaced by the ticket_sale_end_date_time
//            "serviceDescription"    => "Payment for " . $event->name,
//            "accessKey"             => '$2a$08$FIRIU0JS9GESx6ePn/wsUuX4aq2HAsJ16qmz/bTYbT4j7lZ9R6r1W',
//            "countryCode"           => "KE",
//            "languageCode"          => "en",
//            "successRedirectUrl"    => route("success_url"),
//            "failRedirectUrl"       => route("failure_url"),
//            "paymentWebhookUrl"     => route("process_payment"),
//        ];
//
//        //create a pending payment request
//        $payment_request = PaymentRequest::create([
//            'merchantTransactionID' => $payload['merchantTransactionID'],
//            'amount'                => $payload['amount'],
//            'ticket_customer_id'    => $ticket_customer->id,
//            'event_id'              => $event_id
//        ]);
//
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
//
//        //The encryption method to be used
//        $encrypt_method = "AES-256-CBC";
//
//        // Hash the secret key
//        $key = hash('sha256', $this->secretKey);
//
//        // Hash the iv - encrypt method AES-256-CBC expects 16 bytes
//        $iv = substr(hash('sha256', $this->ivKey), 0, 16);
//        $encrypted = openssl_encrypt(
//            json_encode($payload, true), $encrypt_method, $key, 0, $iv
//        );
//
//        //Base 64 Encode the encrypted payload
//        $encryptedPayload = base64_encode($encrypted);
//
//        return [$payload, $encryptedPayload];
//    }

    /* end helper methods */
}
