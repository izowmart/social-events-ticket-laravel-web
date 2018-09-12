<?php

namespace App;

use App\Notifications\UserResetPasswordNotification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name','username', 'email','year_of_birth','gender','image_url','country','fcm_token','auto_follow_status','app_version_code', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function follows($user_id,$status=1)
    {
        return $this->following()->attach($user_id,['status'=>$status,'created_at'=>now(),'updated_at'=> now()]);
    }

    public function unfollows($user_id)
    {
        return $this->following()->detach($user_id);
    }

    public function approve_following($user_id,$status = 1)
    {
        return $this->following()->updateExistingPivot($user_id,['status'=>$status,'created_at'=>now(),'updated_at'=> now()]);
    }

    public function followers() //those who follow me
    {
        return $this->belongsToMany(User::class,'followers','followed_id','follower_id')
            ->withPivot(['status'])
            ->withTimestamps();
    }

    public function following() //those I follow
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id','followed_id')
            ->withPivot(['status'])
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany('App\Post', 'user_id');
    }
}
