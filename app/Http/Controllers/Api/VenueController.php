<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\UniversalMethods;
use App\Transformers\VenueTransformer;
use App\UserVenue;
use App\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractalistic\ArraySerializer;

class VenueController extends Controller
{
    public function index($user_id)
    {
        try {
            $venues = Venue::all();
            return Response::json([
                    "success" => true,
                    "message" => "found " . count($venues),
                    "data" => fractal()->collection($venues)
                        ->serializeWith(ArraySerializer::class)
                        ->withResourceName('data')
                        ->transformWith(new VenueTransformer($user_id))//(new VenueTransformer())->transform($venues,$user_id)

                ]

            );
        } catch (\Exception $exception) {
            return response()->json(array(
                    "success" => false,
                    "message" => "error fetching venues!" . $exception,
                )
            );
        }
    }

    public function follow_venue(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id'        => 'required|exists:users,id',
                'venue_id'       => 'required|exists:venues,id',
            ],
            [
                'user_id.required'         => 'Kindly Login',
                'user_id.exists'           => 'Kindly Sign Up',
                'venue_id.required'        => 'Kindly Login',
                'venue_id.exists'          => 'Kindly Sign Up',
            ]
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                    'data'    => []
                ], 200
            );
        }

        $venue_id = $request->venue_id;
        $user_id = $request->user_id;

        $venue = Venue::find($venue_id);

        $user_venue = UserVenue::where('user_id', $user_id)
            ->where('venue_id', $venue_id)
            ->first();

        if ($user_venue == null) {
            $venue->follow($user_id);
            return response()->json(
                [
                    "success" => true,
                    "message" => "You now follow ".$venue->name
                ]
            );

        }else{
            $venue->unfollow($user_id);

            return response()->json(
                [
                    "success" => true,
                    "message" => "You no longer follow ".$venue->name
                ]
            );
        }
    }
}
