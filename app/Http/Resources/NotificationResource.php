<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class NotificationResource extends JsonResource
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
            'id'                            => $this->id,
            'initiliazer_id'                => $this->initializer_id,
            'initiliazer_username'          => $this->initializer->username,
            'recipient_id'                  => $this->recipient_id,
            'type'                          => $this->type,
            'model_id'                      =>$this->model_id,
            'seen'                          => (bool) $this->seen,
            'created_at'                    => Carbon::parse($this->created_at)->toDateTimeString(),
            'updated_at'                    => Carbon::parse($this->updated_at)->toDateTimeString(),


        ];
    }
}
