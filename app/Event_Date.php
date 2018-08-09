<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event_Date extends Model
{
    protected $fillable=['event_id','start_date_time','end_date_time',];
}
