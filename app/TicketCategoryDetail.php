<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketCategoryDetail extends Model
{
    protected $fillable = ['event_id','category_id','price','no_of_tickets'];

    public function category()
    {
        return $this->belongsTo('App\TicketCategory', 'category_id');
    }
}
