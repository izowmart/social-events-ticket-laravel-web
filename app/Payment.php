<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable=[
        'payment_request_id','payerTransactionID','MSISDN','accountNumber',
        'customerName','amountPaid','payerClientCode','cpgTransactionID',
        'paymentDate','clientName','clientDisplayName','currencyCode',
        'currencyID','paymentID','hubOverallStatus'
    ];
}
