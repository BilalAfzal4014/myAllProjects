<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignTracking extends Model
{
    //
    protected $table = 'campaign_tracking';

    protected $fillable = [
        'campaign_id',
        'row_id',
        'email',
        'firebase_key',
        'device_key',
        'device_type',
        'track_key',
        'payload',
        'sent',
        'viewed',
        'viewed_at',
        'sent_at',
        'job',
        'status',
        'started_at',
        'ended_at',
    ];

    protected $with = ['files', 'log'];

    protected $hidden = ['row_id', 'track_key', 'payload', 'job', 'status', 'started_at', 'ended_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function log()
    {
        return $this->hasOne(CampaignTrackingLogs::class, 'campaign_tracking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany(CampaignTrackingLogFiles::class, 'campaign_tracking_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conversions()
    {
        return $this->hasMany(UserCampaign::class, 'track_key');
    }
}
