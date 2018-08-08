<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    protected $fillable=[
        'admin_id','title','description','image_url','start_date','end_date',
    ];
}
