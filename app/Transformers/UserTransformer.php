<?php

namespace App\Transformers;

use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    private $user_id;
    private $requesting_user;
//
//    public function __construct($user_id)
//    {
//        $this->user_id = $user_id;
//    }

    /**
     * A Fractal transformer.
     *
     * @param \App\User $user
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id'                    => $user->id,
            'firstname'             => $user->first_name,
            'lastname'              => $user->last_name,
            'username'              => $user->username,
            'phone_number'          => $user->phone_number,
            'email'                 => $user->email,
            'year_of_birth'         => $user->year_of_birth,
            'gender'                => $user->gender,
            'profile_url'           => $user->profile_url,
            'country_id'            => $user->country_id,
            'fcm_token'             => $user->fcm_token,
            'auto_follow_status'    => (bool) $user->auto_follow_status,
            'app_version_code'      => $user->app_version_code,
            'is_first_time_login'   => (bool) $user->first_time_login,
            'created_at'            => Carbon::parse($user->created_at)->toDateTimeString(),
            'updated_at'            => Carbon::parse($user->updated_at)->toDateTimeString(),
            'posts'                 => $user->posts->count(),
            'followers'             => $user->followers->count(),
            'following'             => $user->following->count(),
//            'follower'              => (bool) ($this->user_id == $user->id || $this->requesting_user == null ) ? false : in_array($user->id,$this->requesting_user->followers->where('status','=',1)->pluck('id')->toArray()),
//            'pending_follow_request' => (bool) ($this->user_id == $user->id || $this->requesting_user == null ) ? false : in_array($user->id,$this->requesting_user->followers->where('status','=',2)->pluck('id')->toArray()),
//            'followed'              => (bool) ($this->user_id == $user->id || $this->requesting_user == null ) ? false : in_array($user->id,$this->requesting_user->following->where('status','=',1)->pluck('id')->toArray()),
            'user_relationship'     => (int) $user->getUserRelationship($this->user_id)
        ];
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        $this->requesting_user = User::find($this->user_id);
    }
}
