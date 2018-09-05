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
        return parent::toArray($request);
//        return [
//          'id' => $request->id,
//          "title"=>$request->title,
//          "description"=>$request->descritpion,
//          "image_url"=>$request->image_url,
//          "start_date"=>$request->start_date,
//          "end_date"=>$request->end_date,
//          "created_at"=>$request->created_at,
//          "updated_at"=>$request->updated_at,
//        ];
    }
}
