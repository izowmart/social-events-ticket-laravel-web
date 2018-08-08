<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Scanner extends Model
{
    protected $fillable = [
        'event_organizer_id','first_name', 'last_name', 'email', 'password',
    ];
    protected $hidden = [
        'password',
    ];
}
