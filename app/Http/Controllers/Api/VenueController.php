<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\UniversalMethods;
use App\Transformers\VenueTransformer;
use App\UserVenue;
use App\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Spatie\Fractalistic\ArraySerializer;

class VenueController extends Controller
{
    public function index()
    {
        try {
            $user =  request()->user();
            $venues = Venue::all();

            $venueTransformer = new VenueTransformer();
            $venueTransformer->setUserId($user->id);

            return Response::json([
                    "success" => true,
                    "message" => "found " . count($venues),
                    "data" => fractal($venues, $venueTransformer)
//                        ->serializeWith(ArraySerializer::class)
//                        ->withResourceName('data')
//                        ->transformWith(new VenueTransformer($user_id))//(new VenueTransformer())->transform($venues,$user_id)

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
        try {
            $validator = Validator::make($request->all(),
                [
                    'venue_id' => 'required|exists:venues,id',
                ],
                [
                    'venue_id.required' => 'Kindly Login',
                    'venue_id.exists'   => 'Kindly Sign Up',
                ]
            );

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                        'datum'    => null
                    ], 200
                );
            }

            $venue_id = $request->venue_id;
            $user_id = $request->user()->id;

            $venue = Venue::find($venue_id);

            $user_venue = UserVenue::where('user_id', $user_id)
                ->where('venue_id', $venue_id)
                ->first();

            $venueTransformer = new VenueTransformer();
            $venueTransformer->setUserId($user_id);

            if ($user_venue == null) {
                $venue->follow($user_id);

                return response()->json(
                    [
                        "success" => true,
                        "message" => "You now follow " . $venue->name,
                        'datum'    => fractal($venue, $venueTransformer)
                    ]
                );

            } else {
                $venue->unfollow($user_id);

                return response()->json(
                    [
                        "success" => true,
                        "message" => "You no longer follow " . $venue->name,
                        'datum'    => fractal($venue, $venueTransformer)
                    ],200
                );
            }
        } catch ( \Exception $exception ) {
            logger("Following venue id: " .$request->venue_id." failed: ".$exception->getMessage());
            return response()->json(
                [
                    "success" => false,
                    "message" => "Sorry, venue following failed.",
                    'datum'    => null
                ]
            );
        }
    }

    /**
     * Get the venues near a user given a radius and their gps coordinates
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function venues_near_me(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'proximity' => 'required',
                    'latitude'  => 'required',
                    'longitude' => 'required',
                ],
                [
                    'proximity.required' => 'Kindly select the proximity radius',
                    'latitude.required'  => 'Kindly switch on your gps',
                    'longitude.required' => 'Kindly switch on your gps',
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

            $proximity_radius = $request->proximity;
            $user_latitude = $request->latitude;
            $user_longitude = $request->longitude;

            $user = $request->user();

            //get the venues within the given proximity using the harvesine formula
            //distance in kms, rounded to 2 dps
            $venues = Venue::select(
                DB::raw("venues.*,ROUND(
             6371 * acos(
                       cos( radians(  ?  ) ) *
                       cos( radians( latitude ) ) *
                       cos( radians( longitude ) - radians(?) ) +
                       sin( radians(  ?  ) ) *
                       sin( radians( latitude ))
             )
        ,2) AS distance")
            )
                ->having("distance", "<=", $proximity_radius)
                ->orderBy("distance")
                ->where('status', '=', DB::raw(1))
                ->setBindings([$user_latitude, $user_longitude, $user_latitude])
                ->get();


            $venueTransformer = new VenueTransformer();
            $venueTransformer->setUserId($user->id);

            return response()->json(
                [
                    "success" => true,
                    "message" => "Found " .count($venues) ." venues",
                    'data'    => fractal($venues, $venueTransformer)
                ],200
            );

        } catch ( \Exception $exception ) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "venues near me failed ".$exception->getMessage(),
                    'data'    => []
                ]
            );
        }
    }

}
