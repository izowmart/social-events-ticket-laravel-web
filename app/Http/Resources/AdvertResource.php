<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return [
          'id'          =>$this->id,
          "title"       =>$this->title,
          "description" =>$this->descritpion,
          "image_url"   =>$this->image_url,
          "start_date"  =>Carbon::parse($this->start_date)->toDateString(),
          "end_date"    =>Carbon::parse($this->end_date)->toDateString(),
          "created_at"  =>Carbon::parse($this->created_at)->toDateString(),
          "updated_at"  =>Carbon::parse($this->updated_at)->toDateString(),
        ];
    }
}
