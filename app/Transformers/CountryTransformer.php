<?php

namespace App\Transformers;

use App\Country;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class CountryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Country $country
     *
     * @return array
     */
    public function transform(Country $country)
    {
        return [
            'id'        => $country->id,
            'name'      => $country->name,
            "created_at"  =>Carbon::parse($country->created_at)->toDateString(),
            "updated_at"  =>Carbon::parse($country->updated_at)->toDateString(),

        ];
    }
}
