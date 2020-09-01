<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    const STATUS_DRAFT = 'draft';
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspend';
    const STATUS_EXPIRED = 'expired';
    const STATUS_INACTIVE = 'inactive';

    const DELIVERY_TYPE_SCHEDULE = 'schedule';
    const DELIVERY_TYPE_ACTION = 'action';

    const PLATFORM_IOS = 'ios';
    const PLATFORM_ANDROID = 'android';
    const PLATFORM_UNIVERSAL = 'universal';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';

    protected $table = 'campaign';
    protected $appends = ['mutateCode'];

    public function getMutateCodeAttribute()
    {
        return $this->name . ' - ' . $this->created_at;
    }

    /**
     * Get list of associated emails for a campaign.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tracks()
    {
        return $this->hasMany(CampaignTracking::class, 'campaign_id');
    }

    /**
     * Get associated campaign type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function campaign_type()
    {
        return $this->hasOne(CampaignTypes::class, 'id', 'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(User::class, 'company_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function segments()
    {
        return $this->belongsToMany(Segment::class, 'campaign_segments');
    }

    /**
     * Get platform for a campaign.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function platform()
    {
        return $this->hasOne(Lookup::class, 'id', 'platform_id');
    }

    /**
     * @return bool
     */
    public function isPlatformAndroid()
    {
        $platform = $this->platform;

        return in_array(strtolower($platform->code), [self::PLATFORM_ANDROID]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPlatformIOS()
    {
        $platform = $this->platform;

        return in_array(strtolower($platform->code), [self::PLATFORM_IOS]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPlatformUniversal()
    {
        $platform = $this->platform;

        return in_array(strtolower($platform->code), [self::PLATFORM_UNIVERSAL]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isDraft()
    {
        return in_array($this->status, [self::STATUS_DRAFT]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return in_array($this->status, [self::STATUS_ACTIVE]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isSuspended()
    {
        return in_array($this->status, [self::STATUS_SUSPENDED]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return in_array($this->status, [self::STATUS_EXPIRED]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isDeliveryTypeSchedule()
    {
        return in_array($this->delivery_type, [self::DELIVERY_TYPE_SCHEDULE]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isDeliveryTypeAction()
    {
        return in_array($this->delivery_type, [self::DELIVERY_TYPE_ACTION]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isDeliveryControlEnabled()
    {
        return (isset($this->enable_delivery_control) && ((bool)$this->enable_delivery_control === true)) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPriorityLow()
    {
        return in_array($this->campaign_priority, [self::PRIORITY_LOW]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPriorityMedium()
    {
        return in_array($this->campaign_priority, [self::PRIORITY_MEDIUM]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isPriorityHigh()
    {
        return in_array($this->campaign_priority, [self::PRIORITY_HIGH]) ? true : false;
    }

    /**
     * @return bool
     */
    public function isCappingEnabled()
    {
        return ((bool)$this->enable_capping === true) ? true : false;
    }
}
