<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Self_;

class CampaignTypes extends Model
{
    //
    protected $table = 'campaign_types';

    protected $fillable = [
        'name'
    ];

    const TYPE_EMAIL = 'email';
    const TYPE_PUSH = 'push';
    const TYPE_INAPP = 'inapp';

    /**
     * Get list of associated campaigns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function campaigns()
    {
        return $this->hasMany(Campaign::class, 'type_id');
    }

    /**
     * @return bool
     */
    public function isEmail()
    {
        return in_array(strtolower($this->name), [self::TYPE_EMAIL]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPush()
    {
        return in_array(strtolower($this->name), [self::TYPE_PUSH]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isInapp()
    {
        return in_array(strtolower($this->name), [self::TYPE_INAPP]) ? true : false;
    }
}
