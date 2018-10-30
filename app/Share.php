<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Share extends Model
{
    protected $fillable=['user_id','original_post_id','new_post_id'];
}
