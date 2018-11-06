<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Http\Traits\UniversalMethods;
use App\Notification;
use App\Transformers\NotificationTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function index($user_id)
    {
        try {
            $notifications = Notification::where('recipient_id', $user_id)->get();

            $user_ids = $notifications->pluck('initializer_id')->toArray();

            $users = User::whereIn('id', $user_ids)->get();

            $userTransformer = new UserTransformer();
            $userTransformer->setUserId($user_id);

            $data = [
                'notifications' => fractal($notifications,NotificationTransformer::class),
                'users'         => fractal($users,$userTransformer)
            ];

            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($notifications),
                    "data" => $data,
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error fetching notifications! " . $exception,
                )

            );
        }
    }

    public function markSeen(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
                [
                    'user_id'          => 'required|exists:users,id',
                    'notification_ids' => 'required'
                ],
                [
                    'user_id.required'         => 'Kindly Log In!',
                    'user_id.exists'           => 'Kindly Sign Up!',
                    'notification_ids.required' => 'Kindly Sign Up!'
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

            Notification::whereIn('id', $request->notification_ids)
                ->update([
                    'seen' => true
                ]);


            $notifications = Notification::whereIn('id', $request->notification_ids)
                ->select('notifications.*')
                ->get();


//            $notifications = NotificationResource::make($notifications);

            return response()->json([
                'success' => true,
                'message' => 'Marked the notification(s) as Read',
                'data'      => fractal($notifications, NotificationTransformer::class)
            ]);

        } catch ( \Exception $exception ) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $exception->getMessage()." line: ".$exception->getTraceAsString(),
            ]);
        }
    }

}
