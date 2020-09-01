<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Engagment\quickNotification\QuickNotificationWrapper;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuickNotificationController extends Controller
{
    protected $notificationClass;

    public function __construct(QuickNotificationWrapper $notificationClassObj)
    {
        $this->notificationClass = $notificationClassObj;
    }

    public function quickNotification()
    {
        $roleArr = Auth::user()->roles()->pluck('name')->toArray();
        $companyKey = DB::table('users')->where('id', Auth::user()->id)->first()->company_key;
        if (in_array('SUPER-ADMIN', $roleArr)) {
            $role = 'admin';
        } else {
            $role = 'company';
        }
        $users = User::where(['is_deleted' => '0', 'status' => '1'])->get();
        return view('quickNotification.index', ['companyId' => Auth::user()->id, 'companyKey' => $companyKey, 'users' => $users, 'role' => $role, 'roleArr' => $roleArr]);
    }

    public function getPreData($companyId)
    {
        $data = $this->notificationClass->getPreData($companyId);
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Quick Notification filter Data',
        ]);
    }

    public function getDataOnSelection($companyId, $step, $appName, $platForm = "", $version = "", $build = "")
    {
        $data = $this->notificationClass->getDataOnSelection($companyId, $step, $appName, $platForm, $version, $build);
        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => 'Quick Notification filter Data',
        ]);
    }

    public function getCompanyKey($id)
    {
        $companykey = User::where('id', $id)->first(['company_key']);
        return \Response::json(array(
            'Exception' => '',
            'status' => 200,
            'error' => false,
            'message' => 'Company Found',
            'data' => $companykey
        ));
    }
//    public function getUserList($companyId, $appName, $platForms, $version, $build)
//    {
//        echo 'companyId'.$companyId.'<br>';
//        echo 'appName'.$appName.'<br>';
//        echo 'platForms'.$platForms.'<br>';
//        echo 'version'.$version.'<br>';
//        echo 'build'.$build.'<br>';
//        $queryvalue = "(SELECT
// r0.row_id, r1.value AS email,r2.value device_type,r3.value app_name,r4.value build,r5.value version
//FROM (
//SELECT DISTINCT row_id
//FROM attribute_data
//WHERE company_id=20 AND data_type='user') r0
//LEFT JOIN attribute_data r1 ON r1.row_id = r0.row_id AND r1.code = 'email' AND r1.company_id='20' AND r1.data_type='user'
//LEFT JOIN attribute_data r2 ON r2.row_id = r0.row_id AND r2.code = 'device_type'  AND r2.company_id='20' AND r2.data_type='user'
//LEFT JOIN attribute_data r3 ON r3.row_id = r0.row_id AND r3.code = 'app_name'  AND r3.company_id='20' AND r3.data_type='user'
//LEFT JOIN attribute_data r4 ON r4.row_id = r0.row_id AND r4.code = 'build'  AND r4.company_id='20' AND r4.data_type='user'
//LEFT JOIN attribute_data r5 ON r5.row_id = r0.row_id AND r5.code = 'version'  AND r5.company_id='20' AND r5.data_type='user'
//
//GROUP BY  r0.row_id
//HAVING app_name = 'DevCoredirection'  AND r2.value='ios' AND r4.value ='1' AND r5.value ='5')";
//      echo $queryvalue;
//      die;
//        $List= DB::select(DB::raw($queryvalue));
//        dd($List);
//    }

}
