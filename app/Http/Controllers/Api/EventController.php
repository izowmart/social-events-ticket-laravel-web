<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\EventResource;
use App\Scanner;
use App\Transformers\EventTransformer;
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
                ->whereDate('event_dates.end','>=',now()) //upcoming events
                ->get();


            return Response::json([
                    "success" => true,
                    "message" => "found " . count($events),
                    "data"    => fractal($events, EventTransformer::class),
                ]

            );
        } catch ( \Exception $exception ) {
            return Response::json([
                    "success" => "false",
                    "message" => "error fetching events!" . $exception,
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
