<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\EventResource;
use App\Scanner;
use App\Transformers\EventTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = Event::join('event_dates','event_dates.event_id','=','events.id')
                ->where('events.status', 1)//approved by admin
                ->whereDate('event_dates.end','>=',now()) //upcoming events //TODO::what happens when the event dates are two or more??
                ->get();


            return Response::json([
                    "success" => true,
                    "message" => "found " . count($events),
                    "data"    => fractal($events, EventTransformer::class),
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
    public function my_tickets(Request $request){

        $user = $request->user();

        $event = Event::join('tickets','tickets.event_id', '=','events.id')
                ->join('ticket_customers','ticket_customers.id','=','tickets.ticket_customer_id')
                ->join('users','users.id','=','ticket_customers.user_id')
                ->where('ticket_customers.user_id','=',$user->id)
                ->get();


        dd($event);


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
