<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCampaign extends Model
{
    //
    protected $table = 'user_campaign';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'campaign_code',
        'track_key',
        'event_id',
        'event_value',
        'platform',
        'build',
        'version',
        'created_at'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversionEvents()
    {
        return $this->hasMany(Lookup::class, 'id', 'event_id');
    }
}
