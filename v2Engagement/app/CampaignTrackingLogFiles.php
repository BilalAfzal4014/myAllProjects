<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignTrackingLogFiles extends Model
{
    //
    protected $table = 'campaign_tracking_log_files';

    protected $fillable = [
        'campaign_id',
        'campaign_tracking_id',
        'log',
        'queue_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tracking()
    {
        return $this->belongsTo(CampaignTracking::class);
    }
}
