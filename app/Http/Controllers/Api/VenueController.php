<?php

namespace App\Http\Controllers\Api;

use App\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class VenueController extends Controller
{
    public function index()
    {
        try {
            $venues = Venue::all()->toArray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($venues),
                    "data" => $venues
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

    public function create(Request $request)
    {
        $name = $request->input('name');
        $town_id = $request->input('town_id');
        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $contact_person_name = $request->input('contact_person_name');
        $contact_person_phone = $request->input('contact_person_phone');
        $contact_person_email = $request->input('contact_person_email');

        try {
            $venue = new Venue();
            $venue->name = $name;
            $venue->town_id = $town_id;
            $venue->latitude = $latitude;
            $venue->longitude = $longitude;
            $venue->contact_person_name = $contact_person_name;
            $venue->contact_person_phone = $contact_person_phone;
            $venue->contact_person_email = $contact_person_email;
            $venue->save();
            return Response::json(array(
                "success" => true,
                "message" => "venue successfully created",
                "data" => $venue,
            ));
        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error creating venue" . $exception,
            ));
        }
    }
    public function delete(Request $request){
        $venue_id = $request->input('venue_id');
        try{
            Venue::where('id',$venue_id)->delete();
            return Response::json(array(
                "success" => true,
                "message" => "venue successfully deleted",
            ));
        }catch(\Exception $exception){
            return Response::json(array(
                "success" => false,
                "message" => "error deleting venue",
            ));
        }
    }
}
