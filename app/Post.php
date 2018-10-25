<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['user_id','venue_id','media_type','media_url','comment','anonymous','type','shared',];


    public function abuses(){
        return $this->hasMany('App\Abuse','post_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function venue()
    {
        return $this->belongsTo('App\Venue', 'venue_id');
    }

    public function like()
    {
        return $this->hasMany('App\Like','post_id');
    }

    public function shared($user_id)
    {
        return Share::where('user_id', $user_id)->where('shared_id', $this->id)->exists();
    }

    //does this post belong to one that I follow
    public function friends_post($user_id)
    {
        $user = User::find($user_id);

        return in_array($this->id, $user->following->pluck('id')->toArray());
    }
}
