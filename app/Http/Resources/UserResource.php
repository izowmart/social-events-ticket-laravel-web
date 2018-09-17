<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'                    => $this->id,
            'firstname'            => $this->first_name,
            'lastname'             => $this->last_name,
            'username'              => $this->username,
            'email'                 => $this->email,
            'year_of_birth'         => $this->year_of_birth,
            'gender'                => $this->gender,
            'profile_url'           => $this->profile_url,
            'country_id'            => $this->country_id,
            'fcm_token'             => $this->fcm_token,
            'auto_follow_status'    => $this->auto_follow_status,
            'app_version_code'      => $this->app_version_code,
            'is_first_time_login'   => $this->is_first_time_login,
            'created_at'            => Carbon::parse($this->created_at)->toDateTimeString()
        ];
    }
}
