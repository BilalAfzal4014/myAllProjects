<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lookup extends Model
{
    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'modified_date';

    protected $table = 'lookup';

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'parent_id',
        'is_deleted',
        'modifiedby',
        'created_date',
        'modified_date'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
