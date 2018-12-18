<?php

namespace App;

//Class which implements Illuminate\Contracts\Auth\Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable;

//Trait for sending notifications in laravel
use Illuminate\Notifications\Notifiable;

//Notification for admin
use App\Notifications\AdminResetPasswordNotification;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Authenticatable
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
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

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
  }

    public function adverts()
    {
        return $this->hasMany('App\Advert', 'admin_id');
    }
}
