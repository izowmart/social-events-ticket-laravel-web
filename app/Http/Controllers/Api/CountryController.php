<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Http\Resources\CountryResource;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    public function index()
    {
        try {
            $countries = Country::all()->toArray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($countries),
                    "data" => CountryResource::make($countries),
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error fetching countries!" . $exception,
                )

            );
        }
    }

    public function create(Request $request)
    {
        $name = $request->input('name');
        try {
            $country = new Country();
            $country->name = $name;
            $country->save();
            return Response::json(array(
                "success" => true,
                "message" => "country successfully created",
                "data" => $country,
            ));
        } catch (\Exception $exception) {
            return Response::json(array(
                "success" => false,
                "message" => "error creating country" . $exception,
            ));
        }
    }
}
