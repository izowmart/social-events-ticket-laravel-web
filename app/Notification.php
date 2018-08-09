<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $fillable=['initializer_id','recipient_id','type','message',];
}
