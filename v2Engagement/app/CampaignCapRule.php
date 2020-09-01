<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CampaignCapRule extends Model
{
    const CAP_TYPE_EMAIL = 'email';
    const CAP_TYPE_INAPP = 'inapp';
    const CAP_TYPE_PUSH = 'push';

    protected $fillable = [
        'company_id',
        'cap_limit',
        'campaign_type',
        'duration_unit',
        'duration_value',
    ];

    /**
     * @return bool
     */
    public function isEmail()
    {
        return in_array(strtolower($this->campaign_type), [self::CAP_TYPE_EMAIL]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPush()
    {
        return in_array(strtolower($this->campaign_type), [self::CAP_TYPE_PUSH]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isInapp()
    {
        return in_array(strtolower($this->campaign_type ), [self::CAP_TYPE_INAPP]) ? true : false;
    }
}
