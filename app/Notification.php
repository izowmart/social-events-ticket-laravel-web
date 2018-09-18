<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable=['initializer_id','recipient_id','type','model_id','seen'];


    public function initializer()
    {
        return $this->belongsTo('App\User', 'initializer_id');
    }
}
