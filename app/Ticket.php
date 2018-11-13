<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $fillable = [
        'ticket_customer_id', 'event_id', 'validation_token','qr_code_image_url','pdf_format_url','ticket_category_id',
    ];
}
