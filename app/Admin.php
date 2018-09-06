<?php

namespace App;

//Class which implements Illuminate\Contracts\Auth\Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable;

//Trait for sending notifications in laravel
use Illuminate\Notifications\Notifiable;

//Notification for admin
use App\Notifications\AdminResetPasswordNotification;

use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable
{
    // This trait has notify() method defined
    use Notifiable;
    
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    //Send password reset notification
  public function sendPasswordResetNotification($token)
  {
      $this->notify(new AdminResetPasswordNotification($token));
  }
}
