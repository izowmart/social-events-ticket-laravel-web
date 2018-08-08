<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'event_organizer_id','name', 'description', 'location', 'type',
    ];

}
