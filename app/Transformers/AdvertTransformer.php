<?php

namespace App\Transformers;

use App\Advert;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class AdvertTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @param \App\Advert $advert
     *
     * @return array
     */
    public function transform(Advert $advert)
    {
        return [
            'id'          =>$advert->id,
            "title"       =>$advert->title,
            "description" =>$advert->description,
            "image_url"   =>$advert->image_url,
            "start_date"  =>Carbon::parse($advert->start_date)->toDateString(),
            "end_date"    =>Carbon::parse($advert->end_date)->toDateString(),
            "created_at"  =>Carbon::parse($advert->created_at)->toDateString(),
            "updated_at"  =>Carbon::parse($advert->updated_at)->toDateString(),
        ];
    }
}
