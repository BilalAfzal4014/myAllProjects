<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignSchedule extends Model
{
    protected $table = 'campaign_schedule';
    protected $primaryKey = 'campaign_id';
    public $timestamps = false;
}
