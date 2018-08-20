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

}
