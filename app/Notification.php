<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    const LIKE_NOTIFICATION = 1;
    const SHARE_NOTIFICATION = 2;
    const FOLLOW_NOTIFICATION = 3;
    const FOLLOW_REQUEST_NOTIFICATION =4;
    const FOLLOW_REQUEST_REJECTED = 5;



    protected $fillable=['initializer_id','recipient_id','type','model_id','seen'];


    public function initializer()
    {
        return $this->belongsTo('App\User', 'initializer_id');
    }
}
