<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketCustomer extends Model
{
    const SOURCE_WEB = 1;
    const SOURCE_APP = 2;
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number', 'user_id',
    ];
}
