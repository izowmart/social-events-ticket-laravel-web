<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable=['user_id','venue_id','media_type','media_url','comment','anonymous','type','shared',];


    public function abuses(){
        return $this->hasMany('App\Abuse','post_id');
    }

}
