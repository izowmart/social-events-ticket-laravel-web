<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Scanner;
use App\TicketCustomer;
use App\Transformers\EventTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

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
            }

            $events = Event::join('event_dates','event_dates.event_id','=','events.id')
                ->where('events.status', 1)//approved by admin
                ->whereDate('event_dates.end','>=',now()) //upcoming events //TODO::what happens when the event dates are two or more??
                ->groupBy('events.id')
                ->get();

            $event_transformer = new EventTransformer();
            $event_transformer->setTicketCustomerId($ticket_customer_id);

            return Response::json([
                    "success" => true,
                    "message" => "found " . count($events),
                    "data"    => fractal($events,$event_transformer )->withResourceName('data'),
                ]

            );
        } catch ( \Exception $exception ) {
            return Response::json([
                    "message" => "error fetching events!" . $exception,
                ]

            );
        }
    }
    //my_tickets
    public function my_tickets(Resquest $request){

        $user = $request->user();

        $event = Event::join('tickets','tickets.event_id', '=','events.id')
                ->join('ticket_category_details','ticket_category_details.event_id','=','events.id')
                ->where('ticket_customers.user_id','=','user.id');

        try{
            if ($event= Event::where('id',$request->event_id)->first()){

                if ($event->user_id !=$request->user()){
                    return \response()->json([
                        'message'=>''
                    ],401);
                }

                return \response()->json([
                    'message'=>'found events',
                    'data'=>$event
                ],200);


            }


        }catch (\Exception $exception){
            return Response::json([
               "success" => "false",
               "message" => "error fetching my_tickets!" . $exception,
            ]
            );

        }
    }

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
