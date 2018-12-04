<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Event extends Model
{
     use Sluggable;

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        //we add a slug url to the name
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $fillable = [
        'event_organizer_id','name', 'description', 'location', 'type','image_url','start_date_time'
    ];

    public function scanners(){
        return $this->hasMany('App\EventScanner','event_id');
    }
    public function event_dates()
    {
        return $this->hasMany('App\EventDate', 'event_id');
    }

    public function ticket_category_details()
    {
        return $this->hasMany('App\TicketCategoryDetail','event_id');
    }

    public function sponsor_media(){
        return $this->hasMany('App\EventSponsorMedia','event_id')->orderBy('id', 'asc');
    }

    public function getEventSponsorMedia() {
        return EventSponsorMedia::where('event_id',$this->id)->orderBy('id', 'asc');
    }

    public function user_tickets($ticket_customer_id)
    {
        return $this->tickets()->where('ticket_customer_id','=',$ticket_customer_id);
    }

    public function tickets()
    {
        return $this->hasMany('App\Ticket', 'event_id');
    }
}
