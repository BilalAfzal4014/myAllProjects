<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permissions extends Model
{
    //
    protected $table='user_has_roles';
    public $timestamps = false;
    protected $fillable = [
        'role_id',
        'user_id'
    ];
}
