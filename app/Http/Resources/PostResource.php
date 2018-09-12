<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'id'                => $this->id,
            'username'          => $this->user->username,
            'venue'             => $this->venue->name,
            'created_at'        => Carbon::parse($this->created_at)->toDateTimeString(),
            'media_type'        => $this->media_type,
            'media_url'         => $this->media_url,
            'like'              => count($this->like) == 0 ? false : true,
            'shared'            => $this->shared,
            'comment'           => $this->comment,
        ];
    }
}
