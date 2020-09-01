<?php

namespace App\Engagment\quickNotification;

use Illuminate\Support\Facades\DB;
use App\AttributeData;

class QuickNotificationHandler
{
    public function getPreDataAppName($companyId)
    {
        $appName = DB::table('app')
            ->where('company_id', $companyId)
            ->where('is_active', '1')
            ->where('is_deleted', '0')
            ->groupBy('name')
            ->pluck('name');

        return $appName;
    }

    public function getPreDataPlatForm($companyId, $appName)
    {
        $queryString = "SELECT DISTINCT device_type as value from user_attribute where company_id='$companyId' AND app_name ='$appName'";
        $platForm = collect(DB::select($queryString))->pluck('value')->toArray();
        return $platForm;
    }

    public function getPreDataVersion($companyId, $appName, $platForms)
    {
        $queryString = "SELECT DISTINCT version as value from user_attribute where company_id='$companyId' AND app_name ='$appName' AND device_type='$platForms'";
        $version = collect(DB::select($queryString))->pluck('value')->toArray();
        return $version;
    }

    public function getPreDataBuild($companyId, $appName, $platForms, $version)
    {
        $queryString = "SELECT DISTINCT build as value from user_attribute where company_id='$companyId' AND app_name ='$appName' AND version ='$version' AND device_type='$platForms'";
        $build = collect(DB::select($queryString))->pluck('value')->toArray();
        return $build;
    }

    public function getPreDataUser($companyId, $appName, $platForms, $version, $build)
    {
        $usersArray = array();
//        $queryString = 'SELECT DISTINCT value From attribute_data
////              JOIN( SELECT attribute_data.row_id From attribute_data
////              JOIN( SELECT attribute_data.row_id From attribute_data
////              JOIN (SELECT attribute_data.row_id FROM attribute_data JOIN
////              ( SELECT row_id FROM attribute_data
////              WHERE company_id = ' . $companyId . ' AND value = ' . '"' . $appName . '"' . ' ) as x on attribute_data.row_id = x.row_id
////              WHERE value = "' . $platForms . '"' . ') as x1 on attribute_data.row_id = x1.row_id
////              WHERE value = "' . $version . '"' . ') as x2 on attribute_data.row_id = x2.row_id
////              where value = "' . $build . '"' . ') as x3 on attribute_data.row_id = x3.row_id where code = "row_id"';
////        $rowids = collect(DB::select($queryString))->pluck('value')->toArray();
////        $users = [];
////
////        foreach ($rowids as $id) {
////            $user = (object)[];
////            $result = AttributeData::where('row_id', $id)
////                ->where(function ($query) {
////                    $query->where('code', 'email')
////                        ->orWhere('code', 'row_id');
////                })
////                ->select('code', 'value')
////                ->get();
////
////            if (sizeof($result) == 2) {
////                foreach ($result as $obj) {
////                    $prop = $obj->code;
////                    if ($prop == 'email') {
////                        $user->value = $obj->value;
////                    } else {
////                        $user->id = $obj->value;
////                    }
////                }
////                    }
////        }
        $queryvalue = "(SELECT row_id, email AS email,device_type device_type from user_attribute
         where company_id='$companyId' AND app_name = '$appName' AND device_type='$platForms' AND build ='$build' AND version ='$version')";
        $users = DB::select(DB::raw($queryvalue));
        for ($val = 0; $val < count($users); $val++) {
            $usersArray[] = array(
                'id' => $users[$val]->row_id,
                'value' => $users[$val]->email
            );
        }
        return $usersArray;
    }


}