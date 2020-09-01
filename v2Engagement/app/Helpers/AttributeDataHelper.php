<?php

namespace App\Helpers;

use App\AttributeData;
use App\Components\CompanyAttributeData;
use App\UserAttribute;
use Carbon\Carbon;

class AttributeDataHelper
{
    const NOTIFICATION_TYPE_EMAIL = 'email';
    const NOTIFICATION_TYPE_APP = 'app';

    public static function saveAttributeData($company_id, $data, $field, $value)
    {
        try {
            $cache_key = CommonHelper::generateCacheKey($company_id, $data['app_name'], $data['user_id']);
            $item = \Cache::get($cache_key);
            $item = json_decode($item, true);
            $rowId = $item['row_id'];
            $attribute=UserAttribute::where('company_id','=',$company_id)->where('row_id','=',$rowId)
                ->update([
                    $field=>$value
                ]);
            self::saveAppCache($company_id, $data['app_name'], $data['user_id']);
            CompanyAttributeData::updateRow($company_id, $rowId);
            return true;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }

        return false;
    }

    public static function notificationTypes()
    {
        return [self::NOTIFICATION_TYPE_EMAIL, self::NOTIFICATION_TYPE_APP];
    }

    public static function checkDataFromAttributeTable($companyId,$appName,$userId)
    {
        $sql = "SELECT row_id FROM `attribute_data` WHERE company_id = $companyId AND (`code`='app_name' AND `value`='$appName') AND row_id IN ( SELECT row_id FROM `attribute_data` WHERE company_id = $companyId AND (`code`='user_id' AND `value`=$userId) )";
        $recordCheck = \DB::select($sql);

        if (empty($recordCheck)) {
            return null;
        }

        return $recordCheck[0]->row_id;
    }

    public static function getAllDataFromRowId($rowId)
    {
        $dataToSave = AttributeData::where("row_id",$rowId)->get();

        $tempArr = [];
        foreach ($dataToSave as $item) {
            $tempArr[$item->code] = $item->value;
        }

        return $tempArr;
    }

    public static function saveAppCache($company_id, $app_name, $user_id)
    {
        $checkAndRowId = self::checkDataFromAttributeTable($company_id, $app_name, $user_id);
        if($checkAndRowId) {
            $cacheKey = CommonHelper::generateCacheKey($company_id, $app_name, $user_id);

            $dataTOSaveInCache = self::getAllDataFromRowId($checkAndRowId);
            $dataTOSave = json_encode([
                "row_id" => $checkAndRowId,
                "company_id" => $company_id,
                "data" => $dataTOSaveInCache
            ]);

            \Cache::forget($cacheKey);
            \Cache::forever($cacheKey, $dataTOSave);
        }
    }
}