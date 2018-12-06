<?php

namespace App;

use App\Notifications\UserResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    const SELF = -1;
    const NO_RELATIONSHIP = 0;
    const REQUESTER_FOLLOWS_BUT_NOT_FOLLOWED_BACK = 1;
    const REQUESTER_FOLLOWS_AND_FOLLOWED_BACK = 2;
    const REQUESTER_DOESNT_FOLLOW_BUT_FOLLOWED = 3;
    const REQUESTER_PENDING_FOLLOW_BUT_NOT_FOLLOWED_BACK = 4;
    const REQUESTER_PENDING_FOLLOW_AND_FOLLOWED_BACK = 5;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'year_of_birth',
        'gender',
        'profile_url',
        'country_id',
        'fcm_token',
        'auto_follow_status',
        'app_version_code',
        'is_first_time_login',
        'phone_number',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function follows($user_id, $status = 1)
    {
        return $this->following()->attach($user_id,
            ['status' => $status, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function following() //those I follow
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')
            ->where('followers.status', '=', 1)
            ->withPivot(['status'])
            ->withTimestamps();
    }

    public function unfollows($user_id)
    {
        return $this->following()->detach($user_id);
    }

    public function approve_following($user_id, $status = 1)
    {
        return $this->following()->updateExistingPivot($user_id,
            ['status' => $status, 'created_at' => now(), 'updated_at' => now()]);
    }

    public function followers() //those who follow me
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id')
            ->where('followers.status', '=', 1)
            ->withTimestamps();
    }

    public function pending_follow_requests()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id')
            ->where('followers.status', '=', 2)
            ->withPivot(['status'])
            ->withTimestamps();
    }

    public function getUserRelationship($user_id)
    {
        $user = User::find($user_id);
        $my_following = in_array($user_id, $this->following->pluck('id')->toArray());
        $my_follower = in_array($user_id, $this->followers->pluck('id')->toArray());
        $my_sent_requests = in_array($this->id, $user->pending_follow_requests->pluck('id')->toArray());


        if ($user_id == $this->id) {
            return $this::SELF;
        } elseif (!$my_follower && !$my_following) {
            //not following or follower
            if ($my_sent_requests) {
                if (!$my_follower) {
                    //pending follow request but they don't follow me
                    return $this::REQUESTER_PENDING_FOLLOW_BUT_NOT_FOLLOWED_BACK;
                } else {
                    //pending follow request and they follow me
                    return $this::REQUESTER_PENDING_FOLLOW_AND_FOLLOWED_BACK;
                }
            }else{
                return $this::NO_RELATIONSHIP;
            }
        }elseif ($my_following && $my_follower){
            return $this::REQUESTER_FOLLOWS_AND_FOLLOWED_BACK;
        }elseif (!$my_following && $my_follower) {
            //i follow them but they don't follow me back
            return $this::REQUESTER_FOLLOWS_BUT_NOT_FOLLOWED_BACK;
        }elseif ($my_following && !$my_follower){
            //i don't follow them but they follow me
                return $this::REQUESTER_DOESNT_FOLLOW_BUT_FOLLOWED;
        }else{
            return $this::NO_RELATIONSHIP;
        }
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }

    public function likedPosts()
    {
        return $this->hasMany('App\Like', 'user_id');
    }

    public function likesPost($post_id)
    {
        return in_array($post_id, $this->likedPosts->pluck('post_id')->toArray());
    }

    public function original_posts()
    {
        return $this->posts()->leftJoin('shares','posts.id','=','shares.new_post_id')
            ->whereNull('shares.new_post_id');
    }

    public function getNameAttribute()
    {
        return ucwords($this->first_name. ' '.$this->last_name);
    }
}
