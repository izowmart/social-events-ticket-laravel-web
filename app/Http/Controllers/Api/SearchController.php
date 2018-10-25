<?php

namespace App\Http\Controllers\Api;

use App\Http\Traits\UniversalMethods;
use App\Transformers\UserTransformer;
use App\Transformers\VenueTransformer;
use App\User;
use App\Venue;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    /*
     * We are searching from People & Venues
     * People: based on names, emails and usernames
     * Venues; based on name
     *
     */
    public function index(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'search_term' => 'required|string|min:3'
            ],
                [
                    'search_term.required' => 'Kindly enter a word to search',
                    'search_term.string'   => 'Invalid search term',
                    'search_term.min'      => 'The search value is too short.'
                ]);

            if ($validator->fails()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => '' . UniversalMethods::getValidationErrorsAsString($validator->errors()->toArray()),
                        'datum'   => []
                    ], 200
                );
            }

            $search_term = $request->search_term;
            $user = $request->user();

            $people = User::where('status', '=', 1)
                ->where(function ($query) use ($search_term) {
                    $query->where('first_name', 'like', '%' . $search_term . '%')
                        ->orWhere('last_name', 'like', '%' . $search_term . '%')
                        ->orWhere('username', 'like', '%' . $search_term . '%')
                        ->orwhere('email', 'like', '%' . $search_term . '%');
                })
                ->get();

            $venues = Venue::where('status', '=', 1)
                ->where('name', 'like', '%' . $search_term . '%')
                ->get();

            $userTransformer = new UserTransformer();
            $userTransformer->setUserId($user->id);

            return response()->json([
                'success' => true,
                'message' => 'Found ' . count($people) . ' people and ' . count($venues) . ' venues.',
                'users'   => fractal($people, $userTransformer),
                'venues'  => fractal($venues, new VenueTransformer($user->id))
            ]);

        } catch ( \Exception $exception ) {
            return response()->json([
                'success' => false,
                'message' => 'Search failed ' . $exception->getMessage(),
            ]);
        }


    }
}
