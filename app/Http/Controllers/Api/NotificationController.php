<?php

namespace App\Http\Controllers\Api;

use App\Notification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        try {
            $notifications = Notification::where('recipient_id', $request->input('user_id'))->toArray();
            return Response::json(array(
                    "success" => true,
                    "message" => "found " . count($notifications),
                    "data" => $notifications,
                )

            );
        } catch (\Exception $exception) {
            return Response::json(array(
                    "success" => false,
                    "message" => "error fetching notifications!" . $exception,
                )

            );
        }
    }

}
