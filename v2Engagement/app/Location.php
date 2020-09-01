<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    //
    protected $table = 'location';

    protected $fillable = [
        'currency',
        'default_name',
        'lat',
        'lng',
        'language',
        'flag',
        'is_active',
        'is_deleted',
        'created_by',
        'updated_by'
    ];
}
