<?php

namespace App\Transformers;

use App\EventDate;
use App\Http\Traits\UniversalMethods;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class EventDateTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\EventDate $eventDate
     *
     * @return array
     */
    public function transform(EventDate $eventDate)
    {
        $event_time = UniversalMethods::getEventDateTimeStr([$eventDate]);

        return [
            'id'            => $eventDate->id,
            'event_id'      => $eventDate->event_id,
            'event_time'    => $event_time
        ];
    }
}
