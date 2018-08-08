<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abuse extends Model
{
    protected $fillable=['user_id','post_id','count',];
}
