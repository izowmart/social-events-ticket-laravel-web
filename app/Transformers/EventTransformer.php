<?php

namespace App\Transformers;

use App\Event;
use League\Fractal\TransformerAbstract;

class EventTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Event $event
     *
     * @return array
     */
    public function transform(Event $event)

    {
        return [
                'id' => $event->id,
                'name' => $event->name,
                'description' => $event->description,
                'location' =>$event->location,
                'type' => $event->type,
                'image_url' => null,
                'start_date_time' => $event->events_dates != null ? ($event->events_dates->first() != null ? $event->events_dates->first()->start : null ) : null
            ];
    }
}
