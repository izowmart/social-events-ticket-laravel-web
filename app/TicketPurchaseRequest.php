<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketPurchaseRequest extends Model
{
    protected $fillable=['payment_request_id','ticket_category_detail_id','tickets_count'];
}
