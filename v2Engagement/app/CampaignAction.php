<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignAction extends Model
{
    protected $table = 'campaign_action';
    public $timestamps = false;
    protected $primaryKey = 'campaign_id';
}
