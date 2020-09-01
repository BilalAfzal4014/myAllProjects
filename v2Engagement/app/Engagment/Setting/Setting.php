<?php

namespace App\Engagment\Setting;

use App\Engagment\Setting\SettingHandler;

class Setting
{
    protected $setting;

    public function __construct(SettingHandler $setting)
    {
        $this->setting = $setting;
    }

    public function getSettingType($type)
    {
        $data = $this->setting->getSettingType($type);
        return $data;
    }

    public function getBountyCampaign()
    {
       
        $data = $this->setting->getBountyCampaign();
        return $data;

    }

    
}