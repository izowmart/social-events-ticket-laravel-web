<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketScan extends Model
{
    protected $fillable = ['ticket_id','scanner_id'];

}
