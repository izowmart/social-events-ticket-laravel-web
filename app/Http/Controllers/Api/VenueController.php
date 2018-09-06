<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\VenueResource;
use App\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class VenueController extends Controller
{
    public function index()
    {
        try {
            $venues = Venue::all();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($venues),
                    "data" => VenueResource::make($venues),
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error fetching venues!" . $exception,
                )

            );
        }
    }
}
