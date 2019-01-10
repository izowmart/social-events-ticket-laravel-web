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

            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($notifications),
                    'notifications' => fractal($notifications,NotificationTransformer::class),
                    'users'         => fractal($users,$userTransformer)
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
                    'notification_ids' => 'required|array'
                ],
                [
                    'notification_ids.required' => 'Invalid request!'
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

            $user = $request->user();

            Notification::whereIn('id', $request->notification_ids)
                ->update([
                    'seen' => true
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Marked the notification(s) as Read',
                'data'      => fractal($user->user_notifications, NotificationTransformer::class)
            ]);

        } catch ( \Exception $exception ) {
            return response()->json([
                'success' => false,
                'message' => 'Failed: ' . $exception->getMessage()." line: ".$exception->getTraceAsString(),
            ]);
        }
    }

}
