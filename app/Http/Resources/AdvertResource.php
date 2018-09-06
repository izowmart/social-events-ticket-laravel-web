<?php

namespace App\Http\Resources;

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
          "start_date"  =>$this->start_date,
          "end_date"    =>$this->end_date,
          "created_at"  =>$this->created_at,
          "updated_at"  =>$this->updated_at,
        ];
    }
}
