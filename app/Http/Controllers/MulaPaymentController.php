<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Traits\UniversalMethods;
use App\Mail\TicketsBought;
use App\Payment;
use App\PaymentRequest;
use App\PaymentResponse;
use App\Ticket;
use App\TicketCategoryDetail;
use App\TicketCustomer;
use App\TicketPurchaseRequest;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
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
        /*
         * TEST*
        $this->secretKey = "CXNjbwmtVcfDYBTh";
        $this->ivKey = "cpZdGWvh4rfB78C9";
        */

        /*
         * PRODUCTION */
        $this->secretKey = "spjYntujBuOHfulJ";
        $this->ivKey = "0mdHcgBP4ABYeADK";

    }

    /*************************************
     **************** START WEB ONLY ENDPOINTS ***************
     **************************************
     **/

    /**
     * encrypt the data for sending to the mula endpoint to initiate the payment for the client
     *
     * @param \Illuminate\Http\Request $request
     * ->this includes:-
     * first_name, last_name, phone_number, email
     * event_id, ticket_sale_end_date_time, subtotal &
     * (category_slug)_quantity ==> this is repeated for each of the user's selected ticket types
     * with a count of how many they'd want to get e.g. ['vip_quantity' => 10]
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
                    'user_id'      => $user != null ? $user->id : null,
                    'source'       => TicketCustomer::SOURCE_WEB,
                ]
            );

            $event_id = $data_array['event_id'];

            $event = Event::find($event_id);

            DB::beginTransaction();

            list($payload, $encryptedPayload) = UniversalMethods::process_mula_payments($ticket_customer, $data_array,
                $event, $event_id);

            //if payload == null,
            // then the tickets sought are more than the tickets available,
            // therefore, let's take the user back and let them select again
            //otherwise we are good to go....
            if ($payload == null){
                DB::rollBack();

                return response()->json([
                    'params'      => null,
                    'accessKey'   => "",
                    'countryCode' => ""
                ]);
            }

            DB::commit();

            return response()->json([
                'params'      => $encryptedPayload,
                'accessKey'   => $payload['accessKey'],
                'countryCode' => $payload['countryCode']
            ]);

        } catch ( \Exception $exception ) {
            logger("Encrypt data error: message: ".$exception->getMessage()."\ntrace: ".$exception->getTraceAsString());
            DB::rollBack();

            return response()->json([
                'params'      => null,
                'accessKey'   => "",
                'countryCode' => ""
            ]);
        }
    }

    /**
     * show a success message to the user
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function success(Request $request)
    {
        try {

            $result = $request->getContent();

            parse_str($result, $payload);

            //save the response to the db
            PaymentResponse::create([
                'type'     => 'success',
                'response' => $result
            ]);

            //display a success message to the user
            return view('payments.success');
        } catch ( \Exception $exception ) {
            logger("payment success: " . $exception->getMessage());
            //display a success message to the user
            return view('payments.success');
        }
//        try {
////            $payload = $this->processSuccessfulPaymentRequest($request);
//            $result = $request->getContent();
////            $payload = json_decode($result);
//            parse_str($result, $payload);
//
//            //save the response to the db
//            PaymentResponse::create([
//                'type'     => 'success',
//                'response' => $result
//            ]);
//
//            //log the payment
////            logger("PAYMENT SUCCESS:: type: ".gettype($result). "\ncontent:" . $result." payload type: ".gettype($payload));
//
////            logger("success payload: type: " . gettype($payload) . " content: " . json_encode($payload));
////            $pending_payment_request = $this->fetchPendingPaymentRequest($payload);
//
//            //fetch the pending  payment
//            $pending_payment_request = PaymentRequest::where('merchantTransactionID', $payload['merchantTransactionID'])
////                ->where('MSISDN', UniversalMethods::formatPhoneNumber($payload->MSISDN))
////            ->where('amount', '=', $request->amountPaid)
//                ->where('payment_request_status', '=', 0)
//                ->where('payment_accepted', '=', true) //has this payment been accepted by our system...
//                ->first();
//
//            //TODO:: push this to a queue as a job?
//            if ($pending_payment_request != null) {
//                $result = $this->processSuccessfulPayment($pending_payment_request,$payload);
//
//                if ($result) {
//                    //display a success message to the user
//                    return view('payments.success');
//                }
//            }else{
//                //save the parameters of this success response....
//
//            }
//
//            //FIXME:: else show an error message
//            return view('payments.failure');
//
//        } catch ( \Exception $exception ) {
//            logger("PAYMENT SUCCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
//
//            return view('payments.failure');
//        }
    }

    /**
     * process failed web payment
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function failure(Request $request)
    {
        try {
            $payload = $request->getContent();
            //save the response to the db
            PaymentResponse::create([
                'type'     => 'failure',
                'response' => $payload
            ]);

            //display a failure message to the user
            return view('payments.failure');

        } catch ( \Exception $exception ) {
            logger("PAYMENT FAILURE error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());

            //display a failure message to the user
            return view('payments.failure');
        }
    }

    /*************************************
     **************** END WEB ONLY ENDPOINTS ***************
     **************************************
     */



    /*************************************
     **************** START WEB & MOBILE ENDPOINTS ***************
     **************************************
     **/

    /**
     * this is the webhook that pinged by the MULA
     * system after receiving the payment from the client
     * --------------------    ------------------------
     *  we validate the payment and either process & accept or reject it entirely
     * Validation:-
     * a. do we recognize the merchantTransactionID
     * b. are the tickets sought available?
     * c. has the time lapsed for ticket sale?
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        $payloadInitial = $request->getContent();
        $resultInitial = json_decode($payloadInitial);

        try {
            $payload = $payloadInitial;
            $result = $resultInitial;

            //save the response to the db
            PaymentResponse::create([
                'type'     => 'webhook',
                'response' => $payload
            ]);

            //check whether the merchantTransactionID
            $pending_payment_request = PaymentRequest::where('merchantTransactionID', $result->merchantTransactionID)
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($result->MSISDN))
//                ->where('amount','=', $result->amountPaid)
                ->where('payment_request_status', '=', 0)
                ->first();

            if ($pending_payment_request != null) {
                $event = Event::find($pending_payment_request->event_id);

                //has the ticket sale time lapse
                $deadline_lapsed = Carbon::now()->gt(Carbon::parse($event->ticket_sale_end_date));

                if ($deadline_lapsed) {
                    //let's reject the payment
                    return response()->json([
                        'checkoutRequestID'     => $result->checkoutRequestID,
                        'merchantTransactionID' => $result->merchantTransactionID,
                        'statusCode'            => 180,
                        'statusDescription'     => "Payment declined",
                        'receiptNumber'         => $result->merchantTransactionID,
                    ]);
                }

                //are the tickets available???
                $ticket_purchase_requests = TicketPurchaseRequest::where('payment_request_id', '=', $pending_payment_request->id)
                    ->get();

                //check whether all tickets the client wants are available..
                foreach ($ticket_purchase_requests as $ticket_purchase_request) {
                    $tickets_sought = $ticket_purchase_request != null ? $ticket_purchase_request->tickets_count : 0;
                    $ticket_category_detail = TicketCategoryDetail::find($ticket_purchase_request->ticket_category_detail_id);

                    $ticket_category = $ticket_category_detail->category;

                    $tickets_available = UniversalMethods::getRemainingCategoryTickets($pending_payment_request->event_id, $ticket_category->id);

                    if ($tickets_sought > $tickets_available){
                        //let's reject the payment
                        return response()->json([
                            'checkoutRequestID'     => $result->checkoutRequestID,
                            'merchantTransactionID' => $result->merchantTransactionID,
                            'statusCode'            => 180,
                            'statusDescription'     => "Payment declined",
                            'receiptNumber'         => $result->merchantTransactionID,
                        ]);
                    }
                }
                $processing_result = $this->processSuccessfulPayment($pending_payment_request,$payload);

                if (!$processing_result) {
                    return response()->json([
                        'checkoutRequestID'     => $result->checkoutRequestID,
                        'merchantTransactionID' => $result->merchantTransactionID,
                        'statusCode'            => 180,
                        'statusDescription'     => "Payment declined",
                        'receiptNumber'         => $result->merchantTransactionID,
                    ]);
                }else{
                    //return acceptance response
                    return response()->json([
                        'checkoutRequestID'     => $result->checkoutRequestID,
                        'merchantTransactionID' => $result->merchantTransactionID,
                        'statusCode'            => 183,
                        'statusDescription'     => "Successful Payment",
                        'receiptNumber'         => $result->merchantTransactionID,
                    ]);

                }
            }
//            else {
                //reject the payment
                return response()->json([
                    'checkoutRequestID'     => $result->checkoutRequestID,
                    'merchantTransactionID' => $result->merchantTransactionID,
                    'statusCode'            => 180,
                    'statusDescription'     => "Payment declined",
                    'receiptNumber'         => $result->merchantTransactionID,
                ]);
//            }

        } catch ( \Exception $exception ) {
            logger("PAYMENT PROCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());

            //reject the payment
            return response()->json([
                'checkoutRequestID'     => $resultInitial->checkoutRequestID,
                'merchantTransactionID' => $resultInitial->merchantTransactionID,
                'statusCode'            => 180,
                'statusDescription'     => "Payment declined",
                'receiptNumber'         => $resultInitial->merchantTransactionID,
            ]);
        }
    }

    /*************************************
     **************** END WEB & MOBILE ENDPOINTS  ***************
     **************************************
     **/



    /**
     **START MOBILE ONLY ENDPOINTS**
     **/

    /**
     * handle successful mobile payment
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
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

            //process the successful payment
//            $this->processPayment($request);

            //display a success message to the user
            return view('payments.mobile_success');

        } catch ( \Exception $exception ) {
            logger("PAYMENT SUCCESS error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
            return view('payments.mobile_success');
        }
    }

    /**
     * handle failed mobile payment
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
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
//            $this->declinePendingPaymentRequest($request);

            //display a success message to the user
            return view('payments.mobile_failure');
        } catch ( \Exception $exception ) {
            logger("PAYMENT FAILURE error:: " . $exception->getMessage() . "\nTrace::: " . $exception->getTraceAsString());
            return view('payments.mobile_failure');
        }
    }

    /*************************************
     **************** END MOBILE ONLY ENDPOINTS ***************
     **************************************
     **/


    /**
     * START HELPER FUNCTIONS
     */

    /**
     * @param $pending_payment_request
     *
     * @param $payload
     *
     * @return bool
     */
    public function processSuccessfulPayment($pending_payment_request,$payload): bool
    {
        try {
            //update the pending payment request record
            $approved_payment_request = $this->approvePendingPaymentRequest($pending_payment_request);

            //capture the payment details as given from MULA and tie to the approved pending payment request
            $mula_payload_payments = json_decode($payload)->payments;

            logger("mula payload count: " . count($mula_payload_payments)." content: ".json_encode($mula_payload_payments));

            //save each payment record
            foreach ($mula_payload_payments as $index => $mula_payload_payment) {
                $mula_payload_payment->payment_request_id = $approved_payment_request->id;
                Payment::create((array)$mula_payload_payment);  ;
            }

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
            $tickets_count=0;

            foreach ($ticket_purchase_requests as $ticket_purchase_request) {
                /*
                 * for each ticket type, based on the number of tickets purchased
                 * create tickets records--> this is for each of the ticket generated by the system
                 *              a. generate the qr code & convert it so an image for android
                 *              b. generate a pdf ticket with the qr code embedded for emailing
                 *
                 */

                $ticket_type_count = $ticket_purchase_request->tickets_count;
                $tickets_count += $ticket_type_count;

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

//            if (count($tickets_array) > 0) {
            //then we send the email with the relevant pdfs
            $ticket_customer = TicketCustomer::find($ticket_customer_id);
            $email_data = [
                'name'  => $ticket_customer->first_name,
                'event' => Event::find($event_id),
                'files' => $pdfs_array,
                'tickets_count' => $tickets_count
            ];

            Mail::to($ticket_customer)->queue(new TicketsBought($email_data));

            return true;
//            }
        } catch ( \Exception $exception ) {
            logger("processSuccessfulPayment: \nmessage: " . $exception->getMessage() . "\ntrace: " . $exception->getTraceAsString());

            return false;
        }
    }

    /**
     * @param $pending_payment_request
     *
     * @return mixed
     */
    public function approvePendingPaymentRequest($pending_payment_request)
    {
        $pending_payment_request->payment_request_status = 1;
        $pending_payment_request->save();

        return $pending_payment_request;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     */
    public function processSuccessfulPaymentRequest($request)
    {
        $result = $request->getContent();
        $payload = json_decode($result);

        //save the response to the db
        PaymentResponse::create([
            'type'     => 'success',
            'response' => $result
        ]);

        //log the payment
        logger("PAYMENT SUCCESS::  " . $result." payload type: ".gettype($payload));

        return $payload;
    }

    /**
     * @param $payload
     *
     * @return mixed
     */
    public function fetchPendingPaymentRequest($payload)
    {
        //fetch the pending  payment
        $pending_payment_request = PaymentRequest::where('merchantTransactionID', $payload['merchantTransactionID'])
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($payload->MSISDN))
//            ->where('amount', '=', $request->amountPaid)
            ->where('payment_request_status', '=', 0)
            ->first();

        return $pending_payment_request;
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
        $timestamp = now()->timestamp;

        $qr_code_image_name = $timestamp . ".png";
        $qr_code = QrCode::format('png')->size(100)->generate('http://fikaplaces.com/tickets/' . $unique_ticket_identifier,
            $qr_code_images_dir . '/' . $qr_code_image_name);

        /*
        * Prepare the content for the ticket pdf template
        */
        $ticket_template_data = [
            'event'                => $event,
            'event_dates'                => UniversalMethods::getEventDateTimeArray($event_dates),
            'ticket_type'               => $ticket_type,
            'ticket_qr_code_image_name' => $qr_code_image_name,
            'ticket_no'                 => $timestamp
        ];


        //generate and save pdf
        $ticket_name = "T" . $timestamp . uniqid();
        $pdf_name = $ticket_name . ".pdf";
        $pdf_dir = public_path('bought_tickets/pdfs');

        if (!file_exists($pdf_dir)) {
            mkdir($pdf_dir, 0777, true);
        }

        $ticket_pdf_path = $pdf_dir . '/' . $pdf_name;

        //select the event template
        $template = $event->ticket_template == 1 ? "event_organizer.ticket_templates.template_1" : "event_organizer.ticket_templates.template_2";

        //create the pdf for each ticket to be shared via email
//        return PDF::loadView( 'tickets.display-tickets', $ticket_template_data)->save( $pdf_dir.'/'.$pdf_name )->stream($pdf_name);
        $pdf = PDF::loadView($template, $ticket_template_data)->save($ticket_pdf_path);

        $ticket_category_detail = TicketCategoryDetail::where('event_id', $event_id)
            ->where('category_id', $ticket_category_id)
            ->first();

        $data = [
            'event_id'                  => $event_id,
            'ticket_customer_id'        => $ticket_customer_id,
            'validation_token'          => $unique_ticket_identifier,
            'qr_code_image_url'         => $qr_code_image_name,
            'pdf_format_url'            => $pdf_name,
            'ticket_category_detail_id' => $ticket_category_detail->id,
            'unique_ID'                 => $timestamp
        ];

        //save the tickets records
        Ticket::create($data);

        return $data;
    }


    /**
     * @param \Illuminate\Http\Request $request
     */
    public function declinePendingPaymentRequest(Request $request): void
    {
        $pending_payment_request = PaymentRequest::where('merchantTransactionID',
            $request->merchantTransactionID)//FIXME::index the merchantTransacationID for faster querying???
//                ->where('MSISDN', UniversalMethods::formatPhoneNumber($payload->MSISDN))
//            ->where('amount', '=', $request->amountPaid) //FIXME::accept excess payments?? checkout the MULA refunds API...
        ->where('payment_request_status', '=', 0)
            ->first();

        $pending_payment_request->payment_request_status = 2;
        $pending_payment_request->save();
    }

    /*************************************
     **************** END HELPER FUNCTIONS ***************
     **************************************
     **/

}
