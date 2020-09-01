<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attribute';

    protected $fillable = [
        "code",
        "type",
        "name",
        "alias",
        "data_type",
        "length",
        "is_deleted",
        "created_by",
        "updated_by"
    ];
}
