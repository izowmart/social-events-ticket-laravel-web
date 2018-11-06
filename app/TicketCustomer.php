<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketCustomer extends Model
{
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone_number', 'user_id',
    ];
}
