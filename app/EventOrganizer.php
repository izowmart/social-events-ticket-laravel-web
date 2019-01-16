<?php

namespace App;

//Class which implements Illuminate\Contracts\Auth\Authenticatable
use Illuminate\Foundation\Auth\User as Authenticatable;

//Trait for sending notifications in laravel
use Illuminate\Notifications\Notifiable;

//Notification for Seller
use App\Notifications\EventOrganizerResetPasswordNotification;

class EventOrganizer extends Authenticatable
{
    // This trait has notify() method defined
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password',
    ];
    protected $hidden = [
        'password',
    ];

    //Send password reset notification
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new EventOrganizerResetPasswordNotification($token));
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function scanners()
    {
        return $this->hasMany('App\Scanner','event_organizer_id');
    }

    public function paid_events()
    {
        return $this->hasMany('App\Event')
            ->where('type',Event::PAID_EVENT)
            ->where('status',Event::VERIFIED_EVENT);
    }

    public function free_events()
    {
        return $this->hasMany('App\Event')
            ->where('type',Event::FREE_EVENT)
            ->where('status',Event::VERIFIED_EVENT)
            ;
    }

    public function all_events()
    {
        return $this->hasMany('App\Event')
            ->where('type',Event::PAID_EVENT)
            ->where('status',Event::VERIFIED_EVENT);
    }
}
