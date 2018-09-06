<?php

namespace App;

use App\Notifications\ScannerResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Scanner extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'event_organizer_id','first_name', 'last_name', 'email', 'password',
    ];
    protected $hidden = [
        'password','remember_token'
    ];

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ScannerResetPasswordNotification($token));
    }
}
