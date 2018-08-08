<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $fillable=['user_id','venue_id','media_type','media_url','comment','anonymous','type','shared',];
}
