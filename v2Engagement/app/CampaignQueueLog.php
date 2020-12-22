<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignQueueLog extends Model
{
    protected $table = 'campaign_queues_logs';

    protected $fillable = [
        "campaign_id",
        "row_ids",
        "attempts",
        "created_at"

    ];

}
