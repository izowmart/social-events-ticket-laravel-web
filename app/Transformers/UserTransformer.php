<?php

namespace App\Transformers;

use App\User;
use Carbon\Carbon;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
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
            'posts'                 => $user->posts->count(),
            'followers'             => $user->followers->count(),
            'following'             => $user->following->count(),
        ];
    }
}
