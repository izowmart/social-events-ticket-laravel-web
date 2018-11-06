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

    /*
     * Is this an original post that I have shared?
     * params: $requesting_user_id -> user making the api call
     */
    public function shared($requesting_user_id)
    {
        return Share::where('user_id', $requesting_user_id)->where('original_post_id', $this->id)->exists();
    }

    /*
     * Is this a new post from my sharing
     *
     * params: $requesting_user_id
     */
    public function my_shared_post($requesting_user_id)
    {
        return Share::where('user_id', $requesting_user_id)->where('new_post_id', $this->id)->exists();
    }


    /*
     * return true if:
     * this post belong to one that the requesting user follows & not shared
     * or is it shared by one that the requesting user follows,
     * otherwise false
     *
     * params: user_id = the requesting user id
     */
    public function friends_post($requesting_user_id)
    {
        $user = User::find($requesting_user_id);

        $my_following_ids = $user->following->pluck('id')->toArray();

        $belongs_to_my_following = in_array($this->user_id,$my_following_ids);



        $new_post_shared_by_my_following = Share::where('new_post_id', $this->id)
            ->whereIn('user_id', $my_following_ids)
            ->exists();

        return ($belongs_to_my_following && $this->original_post()) || $new_post_shared_by_my_following;
    }

    /**
     * return true if this is not a new post by share,
     * otherwise false
     */
    public function original_post()
    {
        return !Share::where('new_post_id', $this->id)->exists();
    }
}
