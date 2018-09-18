<?php

namespace App\Http\Controllers\Api;

use App\Country;
use App\Http\Resources\CountryResource;
use App\Transformers\CountryTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class CountryController extends Controller
{
    public function index()
    {
        try {
            $countries = Country::all();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($countries),
                    "data" => fractal($countries,CountryTransformer::class),
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

}
