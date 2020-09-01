<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignTemplate extends Model
{
    protected $table = 'campaign_template';
    protected $appends = ['thumbNail'];
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'modified_at';

    public function getThumbNailAttribute()
    {
        return asset("{$this->halfUrl}");
    }
}
