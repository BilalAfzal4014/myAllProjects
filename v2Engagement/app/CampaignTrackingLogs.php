<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignTrackingLogs extends Model
{
    //
    protected $table = 'campaign_tracking_logs';

    protected $fillable = [
        'campaign_tracking_id',
        'status',
        'message',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tracking()
    {
        return $this->belongsTo(CampaignTracking::class);
    }
}
