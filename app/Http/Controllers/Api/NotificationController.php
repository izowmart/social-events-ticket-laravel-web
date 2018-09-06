<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\NotificationResource;
use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function index($user_id)
    {
        try {
            $notifications = Notification::where('recipient_id', $user_id)->get();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($notifications),
                    "data" => NotificationResource::make($notifications),
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

}
