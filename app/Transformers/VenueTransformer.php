<?php

namespace App\Transformers;

use App\Venue;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class VenueTransformer extends TransformerAbstract
{
    protected  $user_id;

    public function __construct($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * A Fractal transformer.
     *
     * @param \App\Venue $venue
     *
     * @param            $user_id
     *
     * @return array
     */
    public function transform(Venue $venue)
    {
        return [
            'id'                            => $venue->id,
            'name'                          => $venue->name,
            'latitude'                      => $venue->latitude,
            'longitude'                     => $venue->longitude,
            'contact_person_name'           => $venue->contact_person_name,
            'contact_person_phone'          => $venue->contact_person_phone,
            'status'                        => $venue->status ,
            'following'                     => $venue->followed($this->user_id) != null ? true : false,
            'created_at'                    => Carbon::parse($venue->created_at)->toDateTimeString(),
            'updated_at'                    => Carbon::parse($venue->updated_at)->toDateTimeString(),
        ];
    }
}
