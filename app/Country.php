<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable=[
        'name',
    ];

    public function users(){
        return $this->hasMany('App\User');
    }

    public function towns(){
        return $this->hasMany('App\Town');
    }


}
