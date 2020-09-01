<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AttributeData extends Model
{
    protected $table = 'attribute_data';

    protected $fillable = [
        'company_id',
        'row_id',
        'code',
        'value',
        'created_by',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'import_data_id',
        'data_type'
    ];
}
