<?php

namespace App\Http\Controllers\Api;

use App\Event;
use App\Http\Resources\EventResource;
use App\Transformers\EventTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class EventController extends Controller
{
    public function index()
    {
        try {
            $events = Event::all();
            return Response::json(array(
                    "success" => "true",
                    "message" => "found " . count($events),
                    "data" => fractal($events, EventTransformer::class),
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
