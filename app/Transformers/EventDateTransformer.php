<?php

namespace App\Transformers;

use App\EventDate;
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
        $start_date_time = Carbon::parse($eventDate->start);


        $start_year = $start_date_time->year;
        $start_month = $start_date_time->month;
        $start_day = $start_date_time->day;
        $start_date = Carbon::create($start_year, $start_month, $start_day);

        $end_date_time = Carbon::parse($eventDate->end);
        $end_year =  $end_date_time->year;
        $end_month = $end_date_time->month;
        $end_day =   $end_date_time->day;
        $end_date = Carbon::create($end_year, $end_month, $end_day);

        $same = $start_date->eq($end_date);

        $event_time = $same ? Carbon::parse($eventDate->start)->toFormattedDateString() . " " . Carbon::parse($eventDate->start)->format('h:i A') . " - " . Carbon::parse($eventDate->end)->format('h:i A') : Carbon::parse($eventDate->start)->toFormattedDateString() . " " . Carbon::parse($eventDate->start)->format('h:i A') . " - " . Carbon::parse($eventDate->end)->toFormattedDateString() . " " . Carbon::parse($eventDate->end)->format('h:i A');

        return [
            'id'            => $eventDate->id,
            'event_id'      => $eventDate->event_id,
            'event_time'    => $event_time
        ];
    }
}
