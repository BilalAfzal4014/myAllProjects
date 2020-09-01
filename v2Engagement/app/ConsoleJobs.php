<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsoleJobs extends Model
{
    protected $fillable = [
        'name',
        'interval',
        'enabled',
        'interval_count',
        'instance_count',
    ];
}
