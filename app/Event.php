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
    public function events_dates()
    {
        return $this->belongsTo('App\EventDate', 'event_id');
    }

    public function tickets()
    {
        return $this->hasMany('App\EventTicketCategory','event_id');
    }
}
