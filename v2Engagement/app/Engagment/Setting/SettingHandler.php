<?php

namespace App\Engagment\Setting;

use Illuminate\Support\Facades\DB;

use App\Settings;

class SettingHandler
{
    protected $setting;

    public function __construct(Settings $setting)
    {
       $this->setting = $setting;
    }

    public function getSettingType($type)
    {

        $Settings = $this->setting->select('id', 'setting_data')
            ->where('setting_type', $type)
            ->where('is_active', 1)
            ->first();
        return $Settings;
    }

    public function getBountyCampaign()
    {

        $Settings = $this->setting->select('*')
            ->where('is_deleted', 0)
            ->where('is_active', 1)
            ->where('setting_type', 'bounty_campaign')
            ->first();
        return $Settings;
    }

}