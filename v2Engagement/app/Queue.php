<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    protected $table = 'queue';

    protected $fillable = [
        'method',
        'data',
        'priority',
        'status',
        'attempted',
        'error_message',
        'started_at',
    ];

    public $timestamps = false;
}
