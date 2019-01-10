<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Traits\UniversalMethods;
use App\Scanner;
use App\TicketCustomer;
use App\Transformers\EventTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
    public function index()
    {
        try {
            $user = request()->user();
            $ticket_customer_id = 0;
            $ticket_customer = TicketCustomer::where('user_id', '=', $user->id)->first();

            if ($ticket_customer != null) {
                $ticket_customer_id = $ticket_customer->id;
            }else{
                //check for ticket customer record based on the email
                $ticket_customer = TicketCustomer::where('email', '=', $user->email)->first();

                if ($ticket_customer != null) {
                    //update ticket customer details with the user id
                    TicketCustomer::where('email', '=', $user->email)
                        ->update([
                            'user_id' => $user->id
                        ]);

                    $ticket_customer_id = $ticket_customer->id;
                }
            }

            $events = Event::join('event_dates', 'event_dates.event_id', '=', 'events.id')
                ->where('events.status', 1)//approved by admin
                ->whereDate('event_dates.end', '>=', now())//upcoming events //TODO::what happens when the event dates are two or more??
                ->groupBy('events.id')
                ->get();

            $event_transformer = new EventTransformer();
            $event_transformer->setTicketCustomerId($ticket_customer_id);

            return Response::json([
                    "success" => true,
                    "message" => "found " . count($events),
                    "data"    => fractal($events, $event_transformer)->withResourceName('data'),
                ]

            );
        } catch ( \Exception $exception ) {
            return Response::json([
                    "message" => "error fetching events!" . $exception,
                ]

            );
        }
    }

    public function event_ticket_category_details(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'event_id' => 'required|integer|exists:events,id'
            ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                        'data'    => null
                    ], 200
                );
            }

            $event = Event::find($request->event_id);

            $user = $request->user();
            $ticket_customer_id = 0;
            $ticket_customer = TicketCustomer::where('user_id', '=', $user->id)->first();

            if ($ticket_customer != null) {
                $ticket_customer_id = $ticket_customer->id;
            }

            $event_transformer = new EventTransformer();
            $event_transformer->setTicketCustomerId($ticket_customer_id);

            $datum = fractal($event, $event_transformer)->withResourceName('datum');

            return Response::json([
                "success" => true,
                "message" => "fetched successfully",
                "datum"   => $datum
            ]);

        } catch ( \Exception $exception ) {
            return Response::json([
                "success" => false,
                "message" => "fetch failed " . $exception->getMessage(),
                "datum"   => null

            ]);
        }

    }
    //my_tickets
//    public function my_tickets(Request $request){
//
//        $user = $request->user();
//
//        $event = Event::join('tickets','tickets.event_id', '=','events.id')
//                ->join('ticket_customers','ticket_customers.id','=','tickets.ticket_customer_id')
//                ->join('users','users.id','=','ticket_customers.user_id')
//                ->where('ticket_customers.user_id','=',$user->id)
//                ->get();
//
//
//        dd($event);
//
//
//    }


    public function scanner_events($scanner_id)
    {
        $scanner = Scanner::find($scanner_id);

        if ($scanner == null) {
            return response()->json(
                [
                    'success' => true,
                    'message' => 'You have no events to scan yet!',
                    'data'    => []
                ], 200
            );
        } else {
            $events = $scanner->events;

            return response()->json(
                [
                    'success' => true,
                    'message' => 'You have ' . count($events) . ' events to scan',
                    'data'    => fractal($events, EventTransformer::class)
                ], 200
            );

        }


    }

}
