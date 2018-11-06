<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $fillable=['merchantTransactionID','event_id','amount','vip_quantity','regular_quantity','payment_request_status','ticket_customer_id'];
}
