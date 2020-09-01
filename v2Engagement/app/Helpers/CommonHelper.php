<?php

namespace App\Helpers;

use App\Apps;
use App\AttributeData;
use App\Gallery;
use App\Libraries\tv_jwt;
use App\Lookup;
use App\User;
use App\UserAttribute;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\NewsFeed;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use Symfony\Component\HttpFoundation\Request;


class CommonHelper
{

    public static $_API_TRIGGER = "API_TRIGGERS";
    public static $_ACTION_TRIGGER = "ACTION_TRIGGERS";
    public static $_CONVERSTION_TYPES = "CONVERSION_TYPES";
    public static $_CAMPAIGN_TRIGGER = "trigger";
    public static $_CAMPAIGN_API = "api";
    public static $_CAMPAIGN_ACTION = "action";
    public static $_STATUS_COMPLETE = "completed";
    public static $_STATUS_ADDED = "added";
    public static $_STATUS_EXECUTING = "executing";
    public static $_ACTIVE = "active";
    public static $_CAMPAIGN_CONVERSION = "conversion";
    public static $_PLATFORM_COMPANY = "COMPANY";
    public static $_SECOND_API_TRIGGER = "Second";
    public static $_MINUTE_API_TRIGGER = "Minute";
    public static $_HOUR_API_TRIGGER = "Hour";
    public static $_SECOND_DELIVERY_CONTROL = "Minute";
    public static $_DAY_DELIVERY_CONTROL = "DAY";
    public static $_WEEK_DELIVERY_CONTROL = "Week";
    public static $_MONTH_DELIVERY_CONTROL = "Month";
    const CLASSIC = "Classic";
    protected $aws_service_name;
    protected $access_key;

    public function __construct()
    {


    }

    public static function CheckDuplicateCampaignTracking($data)
    {
	if(empty($data)) return true;
        $today = Carbon::now()->toDateString();
    
        $tracking_rows = \Illuminate\Support\Facades\DB::select("select ct.id from campaign_tracking ct inner join campaign c on c.id = ct.campaign_id and c.delivery_type='schedule'  where  ct.status='completed'  and  DATE_FORMAT(ct.sent_at,'%Y-%m-%d')='{$today}' and ct.track_key ='{$data['track_key']}' limit 1");

        if ($tracking_rows) {                    
            return false;
        } else {
            return true;
        } 
        
    }
    
        
    public function getUserAgainstCampaign($campaignId)
    {
        //get campaign and company data.
//        $campaignObj = \DB::table("campaign as c")
//            ->join("users as u", "c.company_id", "=", "u.id")
//            ->select(\DB::raw("c.id, c.company_id, c.subject, c.from_email,c.en, c.ar, c.rs, c.ca, u.android_key, u.ios_key,
//                                          u.ios_passphrase, u.firebase_server_api_key"))
//            ->where('c.id', '=', $campaignId)
//            ->first();
//
//        if (empty($campaignObj)) {
//            return [];//return ['No campaign found.'];
//        }

//        $campaignId = $campaignObj->id;
        $companyId = \Auth::user()->id;
        //$companyId = $campaignObj->company_id;
        //get segment filter query.
        $segmentArr = \DB::table('campaign as c')
            ->join("campaign_segments as cs", "cs.campaign_id", "=", "c.id")
            ->join("segment as s", "cs.segment_id", "=", "s.id")
            ->select('s.key_value_sql')
            ->where([['c.id', '=', $campaignId]])
            ->get();
        if (empty($segmentArr)) {
            return [];//return ["No segment found against this campaign."];
        }
        $queryStr = '';
        foreach ($segmentArr as $key => $segmentRow) {

            $queryStr .= $segmentRow->key_value_sql;
            $queryStr .= (isset($segmentArr[$key + 1])) ? ' OR ' : '';
        }
        //get unique user row ids.
        $rowIdArr = \DB::table('attribute_data as ad')
            ->select('ad.row_id')
            ->where([
                ['ad.company_id', '=', $companyId],
            ])
            ->whereRaw($queryStr)
            ->groupBy('row_id')
            ->get();
        if (empty($rowIdArr)) {
            return [];//return ["No user found against this criteria."];
        }

        $rowIdArr = array_pluck($rowIdArr, 'row_id');

        return $rowIdArr;

    }

    public function getUserAgainstNewFeed($newsFeedId)
    {


        $segment = \DB::table('segment as s')
            ->where('s.id', $newsFeedId)
            ->first();

        if (empty($segment)) {
            return [];//["No segment details found!"];
        }

        $queryStr = trim($segment->key_value_sql);

        $companyId = \Auth::user()->id;
        $rowIdArr = \DB::table('attribute_data as ad')
            ->select('ad.row_id')
            ->where([
                ['ad.company_id', '=', $companyId],
            ])
            ->whereRaw($queryStr)
            ->groupBy('row_id')
            ->get();
        $rowIdArr = array_pluck($rowIdArr, 'row_id');
        return $rowIdArr;
    }

    public function attributeDataExist()
    {

        return UserAttribute::where('company_id', \Auth::user()->id)
            ->count();
    }


    public static function minInHtml($selectedVal = null)
    {

        $option = '<select name="startHour" id="startHour11">';
        for ($minute = 0; $minute <= 23; $minute++) {
            $minute = ($minute < 10) ? "0{$minute}" : $minute;
            $selected = ($selectedVal == $minute) ? 'selected' : '';
            $option .= '<option value="' . $minute . '" ' . $selected . '>' . $minute . '</option>';
        }
        $option .= '</select>';
        echo $option;
    }

    public static function generateCacheKey($companyId, $appName, $userId)
    {
        return 'company_' . $companyId . "_" . strtolower(str_replace(" ", "_", $appName)) . "_" . $userId;

    }

    public static function getUserFromKey(tv_jwt $jwt, Request $request)
    {
        $get_first = function ($x) {
            return $x[0];
        };
        $headers = array_map($get_first, $request->headers->all());
        $token = '';
        if (isset($headers['Authorization']))
            $token = $headers['Authorization'];
        elseif (isset($headers['authorization']))
            $token = $headers['authorization'];
        if (!$token) {

            throw new \RuntimeException(CommonError::AUTH_MISSING, CommonError::STATUS_CODE_UNAUTHORIZED);
        }
        $jwtApiKey = config('common.JWT.apiKey.user');
        $data = $jwt->engagiveGetToken($token, $jwtApiKey);

        if (!$data) {

            throw new \RuntimeException(CommonError::TOKEN_EXPIRED, CommonError::STATUS_CODE_UNPROCESSABLE_ENTITY);
        }
        if (!$data->company_key) {

            throw new \RuntimeException(CommonError::USER_NOT_FOUND, CommonError::STATUS_CODE_NOT_FOUND);
        }

        $userToken = null;
        if (isset($data->user_token)) {

            $userToken = $data->user_token;
        }

        $user = User::where("company_key", $data->company_key)->where("is_deleted", 0)->where("status", 1)->first();

        if (!$user) {
            throw new \RuntimeException(CommonError::COMPANY_NOT_FOUND, CommonError::STATUS_CODE_NOT_FOUND);
        }

        return array("user" => $user, "user_token" => $userToken);
    }

    public static function getAttributeData($companyId, $importDataId)
    {

        $companyCodeArr = DB::table('attribute')
            ->select('code')
            ->where('company_id', '=', $companyId)
            ->orWhere('type', '=', 'General')->get();
        $companyCodeArr = array_pluck($companyCodeArr, 'code');
        if (!in_array('user_id', $companyCodeArr) || !in_array('app_name', $companyCodeArr)) {

            if ($importDataId) {
                return null;
            }
            throw new \RuntimeException(CommonError::PLATFORM_ERROR_ENCOUNTER, CommonError::STATUS_CODE_BAD_GATEWAY);
        }
        return $companyCodeArr;
    }

    public static function getAppList($companyId)
    {
        $appLIsting = Apps::where("company_id", $companyId)->where("is_active", 1)->select("name", 'app_id')->get()->toArray();
        $appNameListing = array_pluck($appLIsting, 'name');
        $appIdArray = array_pluck($appLIsting, 'app_id');

        return array($appNameListing, $appIdArray);
    }

    public static function modifyData($data)
    {

        unset($data['device_type']);
        unset($data['device_token']);
        unset($data['fire_base_key']);
        unset($data['app_name']);
        unset($data['app_id']);
        unset($data['version']);
        unset($data['build']);
        unset($data['is_login']);
        unset($data['last_login']);
        unset($data['enabled']);
        unset($data['is_active']);
        return $data;
    }

    public static function generateRowId($companyId)
    {
        $microTime = round(microtime(true) * 100);
        $row_id = $microTime + rand(200, 9999) + rand(100, 999999999999);
        usleep(rand(0, 100));
        if (CommonHelper::checkRowId($row_id, $companyId)) {
            self::generateRowId($companyId);
        }
        return $row_id;

    }

    public static function checkRowId($row_id, $companyId)
    {

        $attributeData = AttributeData::where("row_id", $row_id)->where("company_id", $companyId)->count();
        if ($attributeData) {
            return true;
        }
        return false;
    }

    public static function getActiveCompanies()
    {

        $users = User::whereHas('roles', function ($query) {
            $query->where('name', '=', 'COMPANY');
        })->where("is_deleted", 0)->where("status", 1)->get();
        return $users;
    }

    public static function getDeviceUserAgent()
    {
        $userAgent = "WEB";
        $hittedUserAgent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($hittedUserAgent, 'iPad') || strpos($hittedUserAgent, 'iPhone')) {
            $userAgent = 'IOS';
        } elseif (strpos($hittedUserAgent, 'Android')) {
            $userAgent = 'ANDROID';

        }
        return $userAgent;
    }

    public static function changeHeader($headers)
    {

        $tempHeader = [];
        foreach ($headers as $key => $header) {

            if ($key !== 'user-agent')
                $tempHeader[str_replace('-', '_', $key)] = $header;

        }
        return $tempHeader;
    }

    public static function campaignsCount($segment)
    {

        $counts = [
            'email' => 0,
            'inapp' => 0,
            'push' => 0,
        ];
        $campaigns = $segment->campaigns;


        if (($campaigns instanceof \Illuminate\Support\Collection) && ($campaigns->count() > 0)) {
            $counts['email'] = $campaigns->filter(function ($campaign) {
                return ($campaign->campaign_type->isEmail() === true) ? $campaign : null;
            })->count();

            $counts['inapp'] = $campaigns->filter(function ($campaign) {
                return ($campaign->campaign_type->isInapp() === true) ? $campaign : null;
            })->count();

            $counts['push'] = $campaigns->filter(function ($campaign) {
                return ($campaign->campaign_type->isPush() === true) ? $campaign : null;
            })->count();
        }

        return $counts;
    }

    public static function checkAppDuplication($appName, $appId, $platform, $companyId)
    {
        return Apps::where("name", $appName)
            ->where("app_id", $appId)
            ->where("company_id", $companyId)
            ->where("platform", $platform)
            ->where("is_deleted", 0)
            ->count();
    }


    public static function getLookupIdByCode($postedData, $campaignType, $actionType, $rowId = null)
    {

        $code = strtoupper($postedData['code']);
        $value = $postedData['value'];
        if ($actionType == 'conversion') {

            $trackKey = $postedData['track_key'];

            $actionCampaignId = DB::table("lookup as l")
                ->join('lookup as lparent', 'l.parent_id', '=', 'lparent.id')
                ->join('campaign_action as ca', 'l.id', '=', 'ca.action_id')
                ->join('campaign as c', 'ca.campaign_id', '=', 'c.id')
                ->join('campaign_tracking as ct', 'ca.campaign_id', '=', 'ct.campaign_id')
                ->where("c.status", CommonHelper::$_ACTIVE)
                ->where("lparent.code", $campaignType)
                ->where("l.code", $code)
                ->where("ca.value", $value)
                ->where("ca.action_type", $actionType)
                ->where("c.start_time", '<=', Carbon::now('UTC'))
                ->where("c.end_time", '>=', Carbon::now('UTC'))
                ->where("ct.track_key", $trackKey)
                ->select("ct.sent_at", "ca.campaign_id", "ca.period", "ca.validity", "ca.id as cam_action_id", "ca.action_id")
                ->first();
        } else {

            $actionCampaignId = DB::table("lookup as l")
                ->join('lookup as lparent', 'l.parent_id', '=', 'lparent.id')
                ->join('campaign_action as ca', 'l.id', '=', 'ca.action_id')
                ->join('campaign as c', 'ca.campaign_id', '=', 'c.id')
                ->where("c.status", CommonHelper::$_ACTIVE)
                ->where("lparent.code", $campaignType)
                ->where("l.code", $code)
                ->where("ca.value", $value)
                ->where("ca.action_type", $actionType)
                ->where("c.start_time", '<=', Carbon::now('UTC'))
                ->where("c.end_time", '>=', Carbon::now('UTC'))
                ->where("c.delivery_type", CommonHelper::$_CAMPAIGN_ACTION)
                ->whereNotIn('c.id', function ($q) use ($rowId) {
                    $q->select('ctc.campaign_id')->from('campaign_tracking as ctc')
                        ->join('campaign as c1', 'ctc.campaign_id', '=', 'c1.id')
                        ->where('c1.enable_delivery_control', 0)
                        ->where('ctc.row_id', $rowId)
                        ->whereIn('ctc.status', [CommonHelper::$_STATUS_ADDED, CommonHelper::$_STATUS_COMPLETE, CommonHelper::$_STATUS_EXECUTING]);
                })
                ->select(DB::raw("DISTINCT(ca.campaign_id)"), "ca.action_id")
                ->orderBy('c.start_time', 'desc')
                ->get();
        }
        if (empty($actionCampaignId)) {

            return false;
        }
        return $actionCampaignId;
    }


    public static function validateLookUp($code, $campaignType, $actionType, $rowId = null)
    {

        $lookupId = self::getLookupIdByCode($code, $campaignType, $actionType, $rowId);

        if (!$lookupId) {

            throw new \RuntimeException(CommonError::LOOK_UP_CODE_ERROR, CommonError::STATUS_CODE_LENGTH_REQUIRED);
        }
        return $lookupId;
    }

    public static function removeExtraHeader($postedData)
    {
        unset($postedData['Authorization']);
        unset($postedData['cache_control']);
        unset($postedData['content_type']);
        unset($postedData['authorization']);
        unset($postedData['connection']);
        unset($postedData['content_length']);
        unset($postedData['accept_encoding']);
        unset($postedData['host']);
        unset($postedData['accept']);
        unset($postedData['postman_token']);
        unset($postedData['Host']);
        unset($postedData['Connection']);
        unset($postedData['content-length']);
        unset($postedData['accept-encoding']);
        unset($postedData['cookie']);
        unset($postedData['User-Agent']);
        unset($postedData['Accept']);
        unset($postedData['cache-control']);
        unset($postedData['Postman-Token']);
        unset($postedData['Content-Type']);
        return $postedData;
    }


    public static function getAllUserData($rowId, $companyId)
    {
        $data = UserAttribute::where("row_id", $rowId)->first()->toArray();
        $dataSecond = self::getAttributeDataExtra($rowId, $companyId);
        $dataToPopulate = array_merge($dataSecond, $data);
        return $dataToPopulate;
    }

    public static function getAttributeDataExtra($rowId, $companyId)
    {

        $data = AttributeData::where("row_id", $rowId)->where("company_id", $companyId)->where("data_type", 'user');

        $data = $data->get();
        $finalArr = [];
        foreach ($data as $datum) {

            $finalArr[$datum->code] = $datum->value;
        }
        return $finalArr;
    }

    public static function validateHeader($request, $user)
    {
        $get_first = function ($x) {
            return $x[0];
        };
        $headerData = array_map($get_first, $request->headers->all());
        $headerData = self::changeHeader($headerData);
        list($appNameListing, $appIds) = self::getAppList($user->id);
        $validator = Validator::make($headerData, [
            'app_id' => 'required|in:' . implode(',', $appIds),
            'device_type' => 'required|in:web,ios,android',
            'version' => 'required',
            'build' => 'required',
            'app_name' => 'required|in:' . implode(',', $appNameListing),
        ]);

        if (!empty($validator->errors()->all())) {

            throw new \RuntimeException(implode(',', $validator->errors()->all()), 411);
        }

        return self::removeExtraHeader($headerData);
    }

    public static function verifyingUserToken($company_id, $appName, $userId, $userToken)
    {

        $cacheKey = \App\Helpers\CommonHelper::generateCacheKey($company_id, $appName, $userId);
        $attributeDataObj = \Cache::get($cacheKey);
        if (!$attributeDataObj) {

            throw new \RuntimeException(CommonError::INVALID_USER_ID, CommonError::STATUS_CODE_LENGTH_REQUIRED);
        }

        $attributeDataData = json_decode($attributeDataObj, true);
        if ($attributeDataData['data']['user_token'] !== $userToken) {
            throw new \RuntimeException(CommonError::INVALID_USER_TOKEN, CommonError::STATUS_CODE_LENGTH_REQUIRED);
        }

        return $attributeDataData;
    }


    public static function do_upload($request, $imageFile)
    {

        $file = $request->file($imageFile);
        $allowedMimeType = ["image/png", "image/jpeg", "image/gif", "image/psd", "image/bmp"];
        /**
         * @var  $disk FilesystemAdapter
         */
        $disk = Storage::disk('s3');

        if (!in_array($file->getMimeType(), $allowedMimeType)) {

            return response()->json([
                'status' => 'type_err'
            ]);
        }
        $companyDir = "company_" . auth()->user()->id . '/gallery';

        $temp = explode(".", $_FILES["file"]["name"]);
        $newfilename = substr($temp[0], 0, 20) . '.' . end($temp);
        //generate unique file name
        $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\_\-\.]/', '', basename($newfilename));

        if (!$disk->exists($companyDir)) {
            $disk->makeDirectory($companyDir);
        }


        $disk->put($companyDir . '/' . $fileName, File::get($file), 'public');
        $imagePath = $disk->url($companyDir . '/' . $fileName);

        $image_url = $imagePath;
        $newImageObj = new Gallery();
        $newImageObj->company_id = Auth::user()->id;
        $newImageObj->image_name = $fileName;
        $newImageObj->image_url = $image_url;
        $newImageObj->created_by = Auth::user()->id;
        $newImageObj->updated_by = Auth::user()->id;
        $newImageObj->save();
        return $image_url;
    }

    public static function engagement_file_get_contents($url)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);

        return $output;

    }
}