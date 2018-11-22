<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_customer_id', 'event_id', 'validation_token','qr_code_image_url','pdf_format_url','ticket_category_id','unique_ID'
    ];

    public function event()
    {
        return $this->belongsTo('App\Event', 'event_id');
    }

    public function category()
    {
        return $this->belongsTo('App\TicketCategory', 'ticket_category_id');
    }

    public function ticket_scan()
    {
        return $this->hasOne('App\TicketScan', 'ticket_id');
    }

    /**
     * check whether this ticket has been scanned yet
     * @return bool
     */
    public function scanned()
    {
        if ($this->ticket_scan == null) {return false;}
        else {
            return $this->ticket_scan->count() == 1 ? true : false;
        }
    }
}
