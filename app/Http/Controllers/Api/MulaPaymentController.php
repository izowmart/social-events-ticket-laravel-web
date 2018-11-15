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
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class MulaPaymentController
{
    public function initiate_payment(Request $request)
    {
        try {

            $validator = Validator::make($request->all(),
                [
                    'event_id' => 'required|integer|exists:events,id',
                    'user_id'  => 'required|integer|exists:users,id',
                ],
                [
                    'event_id.required'    => 'invalid value',
                    'event_id.integer'     => 'invalid value',
                    'event_id.exists'      => 'invalid value',
                    'customer_id.required' => 'login',
                    'customer_id.integer'  => 'invalid request',
                    'customer_id.exists'   => 'invalid value',

                ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'failed because of ' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray())
                ]);
            }

            $user_id = $request->user_id;
            $event_id = $request->event_id;

            $user = User::find($user_id);

            $event = Event::find($event_id);

            $data_array = $request->all();
            $data_array['ticket_sale_end_date_time'] = $event->ticket_sale_end_date_time;

            $ticket_customer = TicketCustomer::updateOrCreate(
                [
                    'email' => $user->email,
                ],
                [
                    'phone_number' => UniversalMethods::formatPhoneNumber($user->phone_number),
                    'first_name'   => $user->first_name,
                    'last_name'    => $user->last_name,
                    'user_id'      => $user != null ? $user->id : 0,
                ]
            );

            DB::beginTransaction();

            list($payload, $encryptedPayload) = UniversalMethods::process_mula_payments($ticket_customer, $data_array,
                $event, $event_id,false);

            DB::commit();

            return response()->json([
                    'success' => true,
                    'message' => 'params fetched successfully',
                    'datum'   => [
                        'params'      => $encryptedPayload,
                        'accessKey'   => $payload['accessKey'],
                        'countryCode' => $payload['countryCode']
                    ]
                ]
            );
        } catch ( \Exception $exception ) {
            DB::rollBack();

            return response()->json([
                    'success' => false,
                    'message' => 'params fetch failed',
                    'datum'   => [
                        'params'      => null,
                        'accessKey'   => '',
                        'countryCode' => ''
                    ]
                ]
            );

        }
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