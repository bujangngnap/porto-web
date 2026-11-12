<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'visited_date',
        'visitor_hash',
        'path',
        'referrer_host',
        'device_type',
    ];
}
