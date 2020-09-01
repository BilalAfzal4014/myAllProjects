<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use CompileTags;

    protected $table = 'segment';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_segments');
    }
}
