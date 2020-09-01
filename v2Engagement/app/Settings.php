<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = "settings";
    
    public function getSettingType($type) {
    	
          $Settings = Settings::select('id','setting_data')
			          ->where('setting_type', $type)
			          ->where('is_active', 1 )
			          ->first();
          return $Settings;
    }
    
    public function getBountyCampaign() {
    	
          $Settings = Settings::select('*')
			          ->where('is_deleted', 0 )
			          ->where('is_active', 1 )
			          ->where('setting_type', 'bounty_campaign' )
			          ->first();
          return $Settings;
    }

    public function updateSettings($id, $data, $type)
    {
      $settings = Settings::where('id', $id)
          ->where('setting_type', $type)
          ->update(['setting_data' => $data]);
      return $settings;
    }

    public function updateSettingsByID($id, $data)
    {
        $settings = Settings::where('id', $id)
                            ->update(['setting_data' => $data]);
        return $settings;
    }
}
