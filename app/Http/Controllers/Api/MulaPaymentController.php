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
use App\TicketCategoryDetail;
use App\TicketCustomer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MulaPaymentController
{
    public function initiate_payment(Request $request)
    {
        try {

            $validator = Validator::make($request->all(),
                [
                    'tickets'                               => 'required|array',
                    'tickets.*.ticket_category_detail_id'   => 'required|integer|exists:ticket_category_details,id',
                    'tickets.*.no_of_tickets'               => 'required|integer',
                ],
                [
                    'tickets.required'                              => 'invalid request',
                    'tickets.array'                                 => 'invalid value',
                    'tickets.*.ticket_category_detail_id.required'  => 'invalid value',
                    'tickets.*.ticket_category_detail_id.integer'   => 'invalid value',
                    'tickets.*.ticket_category_detail_id.exists'    => 'ticket details are incorrect',
                    'tickets.*.no_of_tickets.required'              => 'invalid request',
                    'tickets.*.no_of_tickets.integer'               => 'correctly enter the number of tickets'
                ]);

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'failed because of ' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray())
                ]);
            }

            $user = $request->user();

            //register the user as a ticket customer
            $ticket_customer = TicketCustomer::updateOrCreate(
                [
                    'email' => $user->email,
                ],
                [
                    'phone_number' => UniversalMethods::formatPhoneNumber($user->phone_number),
                    'first_name'   => $user->first_name,
                    'last_name'    => $user->last_name,
                    'user_id'      => $user != null ? $user->id : null,
                    'source'       => TicketCustomer::SOURCE_APP,
                ]
            );

           //process the information for the tickets purchase request
            $tickets = $request->tickets;
            //this array should contain:
            //ticket_sale_end_date_time, category_slug_quantity,subtotal
            $data_array=[];
            $event_id = 0;
            $event = new Event();
            $total = 0;
            foreach ($tickets as $ticket)  {
                //get the ticket_category_detail_id & the no_of_tickets the client would want to purchase
                $ticket_category_detail_id = $ticket['ticket_category_detail_id'];
                $no_of_tickets = $ticket['no_of_tickets'];

                $ticket_category_detail = TicketCategoryDetail::find($ticket_category_detail_id);

                $event_id = $ticket_category_detail->event_id;
                $event = Event::find($event_id);
                $category_slug = $ticket_category_detail->category->slug;

                $ticket_sale_end_date_time = $event->ticket_sale_end_date;
                $total += $no_of_tickets*(int)$ticket_category_detail->price;

                $data_array['ticket_sale_end_date_time'] = $ticket_sale_end_date_time;
                $data_array[$category_slug.'_quantity'] = $no_of_tickets;
            }

            $data_array['subtotal'] = $total;

            DB::beginTransaction();

            list($payload, $encryptedPayload) = UniversalMethods::process_mula_payments($ticket_customer, $data_array,
                $event, $event_id,false);

            //if payload == null,
            // then the tickets sought are more than the tickets available,
            // therefore, let's take the user back and let them select again
            //otherwise we are good to go....
            if ($payload == null){
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
                    'message' => 'params fetch failed: '.$exception->getMessage().'\n'.$exception->getTraceAsString(),
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