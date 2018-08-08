<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['name', 'town_id', 'latitude', 'longitude', 'contact_person_name','contact_person_phone','contact_person_email',];
}
