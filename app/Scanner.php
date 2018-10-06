<?php

namespace App;

use App\Notifications\ScannerResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Scanner extends Authenticatable
{
    use Notifiable, HasApiTokens;

    protected $fillable = [
        'event_organizer_id','first_name', 'last_name', 'email', 'password',
    ];
    protected $hidden = [
        'password','remember_token'
    ];

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
