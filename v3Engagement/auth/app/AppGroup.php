<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppGroup extends Model
{
    protected $table = 'app_group';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'is_default',
        'created_by',
        'updated_by',
    ];
}
