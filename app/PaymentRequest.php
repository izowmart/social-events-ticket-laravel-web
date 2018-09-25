<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $fillable=['merchantTransactionID','MSISDN','customerEmail','amount','status'];
}
