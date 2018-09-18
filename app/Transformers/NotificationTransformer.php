<?php

namespace App\Transformers;

use App\Notification;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Notification $notification
     *
     * @return array
     */
    public function transform(Notification $notification)
    {
        return [
            'id'                            => $notification->id,
            'initiliazer_id'                => $notification->initializer_id,
            'initiliazer_username'          => $notification->initializer->username,
            'recipient_id'                  => $notification->recipient_id,
            'type'                          => $notification->type,
            'model_id'                      => $notification->model_id,
            'seen'                          => (bool) $notification->seen,
            'created_at'                    => Carbon::parse($notification->created_at)->toDateTimeString(),
            'updated_at'                    => Carbon::parse($notification->updated_at)->toDateTimeString(),


        ];
    }
}
