<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event_Dates extends Model
{
    protected $fillable=['event_id','start_date_time','end_date_time',];
}
