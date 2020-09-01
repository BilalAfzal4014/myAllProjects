<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobHistory extends Model
{
    //
    protected $table = 'jobs_history';

    protected $fillable = [
        'job',
        'started_at',
        'ended_at'
    ];

    public $timestamps = false;
}
