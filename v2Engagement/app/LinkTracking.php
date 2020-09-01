<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LinkTracking extends Model
{
    protected $table = 'link_tracking';

    public $timestamps = false;

    protected $fillable = [
        'rec_type',
        'rec_id',
        'actual_url',
        'created_date',
        'ip_address',
        'user_agent',
        'device'
    ];
}
