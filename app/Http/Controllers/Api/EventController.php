<?php

namespace App\Http\Controllers\Api;

use App\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = Event::all()->toArray();
            return Response::json(array(
                    "success" => "true",
                    "message" => "found " . count($events),
                    "data" => $events,
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => "false",
                    "message" => "error fetching events!" . $exception,
                )

            );
        }
    }

    public function create(Request $request)
    {
        $event_organizer_id = $request->input('event_organizer_id');
        $name = $request->input('name');
        $description = $request->input('description');
        $location = $request->input('location');
        $type = $request->input('type');
        try {
            $event = new Event();
            $event->event_organizer_id = $event_organizer_id;
            $event->name = $name;
            $event->description = $description;
            $event->location = $location;
            $event->type = $type;
            $event->save();
            return Response::json(array(
                "success" => true,
                "message" => "event successfully created",
                "data" => $event,
            ));
        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error creating event" . $exception,
            ));
        }
    }

    public function delete(Request $request){
        $event_id = $request->input('event_id');
        try{
            Event::where('id',$event_id)->delete();
            return Response::json(array(
                "success" => true,
                "message" => "event successfully deleted",
            ));
        }catch(\Exception $exception){
            return Response::json(array(
                "success" => false,
                "message" => "error deleting event",
            ));
        }
    }
}
