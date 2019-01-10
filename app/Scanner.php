<?php

namespace App;

use App\Notifications\ScannerResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Scanner extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];
    protected $hidden = [
        'password','remember_token'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function events()
    {
        return $this->belongsToMany('App\Event','event_scanners','scanner_id','event_id')
            ->join('event_dates','event_dates.event_id','=','events.id') //join with the dates
            ->where('status','=',1) //approved by admin
            ->where('type','=',2)  //paid event
            ->whereDate('event_dates.start','>=',now()); //whose start date is today or after


    }

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ScannerResetPasswordNotification($token));
    }
}
