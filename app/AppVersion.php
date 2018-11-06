<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    protected $fillable=['least_accepted_version_code','latest_version_code',];
}
