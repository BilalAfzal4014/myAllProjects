<?php

namespace App\Engagment\AttributeData;

use App\Apps;
use App\Attribute;
use App\AttributeData;
use App\Campaign;
use App\CampaignTracking;
use App\Components\CompanyAttributeData;
use App\Helpers\CommonHelper;
use App\Jobs\AttributeDataCacheJob;
use App\Jobs\ImportJob;
use App\LinkTracking;
use App\Lookup;
use App\Segment;
use App\User;
use App\UserAttribute;
use App\UserCampaign;
use Carbon\Carbon;
use DB;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class AttributeDataHandler
{
    public $attributeDataModel;

    public function __construct(AttributeData $attributeData)
    {
        $this->attributeDataModel = $attributeData;
    }

    public function getAttributeData()
    {
        DB::table('attribute_data')->get();
    }

    public function insertData($data)
    {

        DB::table('attribute_data')->insert($data);
    }


    public function getOtherAttributeData($search, $limit, $start, $order, $dir, $companyId, $dataTypeArr)
    {
        $attributeData = DB::table('attribute_data as ad')
            ->selectRaw('ad.id, ad.code, ad.value, ad.created_at, data_type')
            ->where([
                ['ad.company_id', '=', $companyId],
            ])
            ->whereIn('ad.data_type', $dataTypeArr);
        if (!empty($search)) {

            $attributeData->whereRaw("ad.code LIKE '%{$search}%' OR ad.value LIKE '%{$search}%'");
        }
        $attributeData = $attributeData->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get();
        //dd($attributeData->toSql());
        return $attributeData;
    }


    public function getOtherAttributeDataDT($request, $companyId)
    {
        $columns = array(
            0 => 'id',
            1 => 'code',
            2 => 'value',
            3 => 'created_at',
            4 => 'data_type',
        );

        $dataType = $request->input('data_type');//dd($dataType);

        $dataTypeArr = (!empty($dataType)) ? [$dataType] : ['conversion', 'action', 'app', 'gamification'];

        $totalData = DB::table("attribute_data as ad")
            ->where([
                ['ad.company_id', '=', $companyId]
            ])
            ->whereIn('ad.data_type', $dataTypeArr)
            ->count();

        $totalFiltered = $totalData;  // 2
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""


        $attributeDataListing = $this->getOtherAttributeData($search, $limit, $start, $order, $dir, $companyId, $dataTypeArr);

        if (!empty($search)) {
            $totalFiltered = sizeof($attributeDataListing);
        }

        return array($totalData, $totalFiltered, $attributeDataListing);
    }


    public function getEmailListDT($request, $companyId)
    {
        $columns = array(
            0 => 'id',
            1 => 'email',
            2 => 'rec_type',
            3 => 'created_at'
        );

        $userType = $request->input('userType');//dd($dataType);

        $userTypeArr = (!empty($userType)) ? [$userType] : ['blacklist', 'whitelist'];

        $totalData = DB::table("email_list as el")
            ->where([
                ['el.company_id', '=', $companyId]
            ])
            ->whereIn('el.rec_type', $userTypeArr)
            ->count();
        $totalFiltered = $totalData;//2
        $limit = $request->input('length'); //25
        if (!$limit) {
            $limit = 10;
        }
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column') ? $request->input('order.0.column') : 0];
        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""


        $emailListData = DB::table('email_list as el')
            ->selectRaw('el.id, el.email, el.rec_type, el.created_at')
            ->where([
                ['el.company_id', '=', $companyId],
            ])
            ->whereIn('el.rec_type', $userTypeArr);
        if (!empty($search)) {

            $emailListData->whereRaw("el.email LIKE '%{$search}%'");
        }
        $emailListData = $emailListData->orderBy($order, $dir);
        if ($start) {
            $emailListData = $emailListData
                ->offset($start);
        }
        $emailListData = $emailListData
            ->limit($limit)
            ->get();
        //dd($emailListData->toSql());
        if (!empty($search)) {
            $totalFiltered = sizeof($emailListData);
        }
        return array($totalData, $totalFiltered, $emailListData);
    }


    public function getImportFileDT($search, $limit, $start, $order, $dir, $companyId)
    {
        $attributeData = DB::table('import_data as imd')
//                            ->join('users as u','u.id','=','imd.created_by')
            ->selectRaw('imd.id, imd.file_name, imd.created_at, imd.file_size, is_processed, imd.process_date')
            ->where([
                ['imd.company_id', '=', $companyId],
                ['imd.is_deleted', '=', 0]
            ]);
        if (!empty($search)) {

            $attributeData->where('imd.file_name', 'LIKE', "%{$search}%");
        }

        $importFiles = $attributeData->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get();
        foreach ($importFiles as $file) {

            $file->remaning_file = $this->getFileCount($file, $companyId);
        }
        return $importFiles;
    }


    public function getFileCount($fileId, $companyId)
    {

        $directory = 'company_' . $companyId . '/' . 'attribute_file_' . $fileId->id;
        /**
         * @var  $disk FilesystemAdapter
         */
        $disk = \Storage::disk("s3");
        $fileCount = count($disk->allFiles($directory));

        if ($fileId->is_processed && $fileCount == 0) {

            $fileId->is_processed = "Completed";
        } else {

            $fileId->is_processed = $fileId->is_processed ? "In Process" : "Pending";
        }
        return $fileCount;
    }

    public function getImportFileListing($request, $companyId)
    {
        $columns = array(
            0 => 'id',
            1 => 'file_name',
            2 => 'file_size',
            3 => 'created_at',
            4 => 'is_processed',
            5 => 'process_date'
        );

        $totalData = DB::table("import_data")
            ->where([
                ['company_id', '=', $companyId],
                ['is_deleted', '=', 0]
            ])
            ->count();

        $totalFiltered = $totalData;  // 2
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $order = $columns[$request->input('order.0.column')];  //firstname

        $dir = $request->input('order.0.dir'); //desc
        $search = $request->input('search.value');  // ""


        $attributeDataListing = $this->getImportFileDT($search, $limit, $start, $order, $dir, $companyId);
        if (!empty($search)) {
            $totalFiltered = sizeof($attributeDataListing);
        }

        return array($totalData, $totalFiltered, $attributeDataListing);
    }


    public function getAttributeDataListing($request, $companyId)
    {
        ini_set('memory_limit',config('engagement.memory_limit'));
        ini_set('max_execution_time',config('engagement.max_execution_time'));
        
        $columns = array(
            0 => 'row_id',
            1 => 'user_id',
            2 => 'firstname',
            3 => 'device_type',
            4 => 'app_name',
            5 => 'last_login'
        );

        $totalData = \Illuminate\Support\Facades\DB::select("SELECT count(1) as cnt FROM `user_attribute` WHERE company_id = $companyId");
        $totalData = $totalData[0]->cnt;
        $totalFiltered = $totalData;  // 2

        $limit = $totalData;  //25
        $start = "0"; // 0
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir'); //desc
        $limit = $request->input('length');  //25
        $start = $request->input('start'); // 0
        $search = $request->input('search.value');  // ""
        $filter = $request->filter;
        $filterType = $request->filterType;

        $attributeDataListing = $this->getListingForDataTable($search, $limit, $start, $order, $dir, $companyId, $filter, $filterType);
        $attributeDataListingForCount = $this->getListingCountForDataTable($search, null, null, $order, $dir, $companyId, $filter, $filterType);
        //$totalFiltered = sizeof($attributeDataListingForCount);
		$totalFiltered = $attributeDataListingForCount;
		
        return array($totalData, $totalFiltered, $attributeDataListing);
    }

    public function getListingForDataTable($search, $limit = null, $start = null, $order, $dir, $companyId, $filter, $filterType)
    {


        $attributeData = UserAttribute::where('company_id', $companyId)->orderBy($order, $dir);
        if ($limit) {


            $attributeData = $attributeData->
            offset($start)
                ->limit($limit);
        }
        if ($search) {
            $attributeData = $attributeData->where(function ($query) use ($search) {

                $query->orWhere('user_id', 'LIKE', "%{$search}%");
                $query->orWhere('email', 'LIKE', "%{$search}%");
                $query->orWhere('firstname', 'LIKE', "%{$search}%");

            });
        }

        switch ($filterType) {

            case 'status':
                $attributeData = $attributeData->where("is_active", $filter);
                break;
            case 'app_name':
                $attributeData = $attributeData->where("app_name", $filter);
                break;
            case 'type':
                $attributeData = $attributeData->where("is_import", $filter);
                break;
        }
        $attributeData = $attributeData->get();


        return $attributeData;
    }
    
    public function getListingCountForDataTable($search, $limit = null, $start = null, $order, $dir, $companyId, $filter, $filterType)
    {


        $attributeData = UserAttribute::where('company_id', $companyId);
        
        if ($search) {
            $attributeData = $attributeData->where(function ($query) use ($search) {

                $query->orWhere('user_id', 'LIKE', "%{$search}%");
                $query->orWhere('email', 'LIKE', "%{$search}%");
                $query->orWhere('firstname', 'LIKE', "%{$search}%");

            });
        }

        switch ($filterType) {

            case 'status':
                $attributeData = $attributeData->where("is_active", $filter);
                break;
            case 'app_name':
                $attributeData = $attributeData->where("app_name", $filter);
                break;
            case 'type':
                $attributeData = $attributeData->where("is_import", $filter);
                break;
        }
       
        return $attributeData->count();
    }
    

    public function getProfileDetails($companyId)
    {
        return User::where('id', $companyId)
            ->first();
    }

    public function getCustomAttributes($companyId)
    {
        return Attribute::where('company_id', $companyId)
            ->select('code', 'data_type')
            ->get();
    }

    public function getTokens($companyId)
    {
        return AttributeData::where('company_id', $companyId)
            ->where('code', 'device_token')
            ->select('value')
            ->get();
    }

    public function getCampaignClick($companyId)
    {
        return DB::select("SELECT campaign.name, campaign.created_at, IFNULL(clicker.clicks, '0') AS clicks FROM campaign LEFT JOIN (SELECT rec_type, rec_id, COUNT(*) as clicks FROM `link_tracking` WHERE rec_type='Email' GROUP BY rec_type, rec_id ) as clicker on campaign.id = clicker.rec_id WHERE campaign.company_id = $companyId");
    }

    public function getSegmentsInfo($companyId)
    {
        $segments = Segment::where('company_id', $companyId)
            ->select('id', 'name')
            ->get();

        $segmentArr = [];
        foreach ($segments as $segment) {
            $obj = (object)[];
            $obj->name = $segment->name;
            $obj->campaigns = DB::table('campaign_segments')
                ->join('campaign', 'campaign_segments.campaign_id', '=', 'campaign.id')
                ->where('campaign_segments.segment_id', $segment->id)
                ->select('campaign.name')
                ->get();
            $segmentArr[] = clone $obj;
        }

        return $segmentArr;
    }

    public function getNewsfeedClick($companyId)
    {
        return DB::select("SELECT news_feed.name, IFNULL(clicker.clicks, '0') AS clicks FROM news_feed LEFT JOIN (SELECT rec_type, rec_id, COUNT(*) as clicks FROM `link_tracking` WHERE rec_type='Newsfeed' GROUP BY rec_type, rec_id ) as clicker on news_feed.id = clicker.rec_id WHERE news_feed.company_id = $companyId");
    }

    public function getNewsfeedClickUser($rowId)
    {
        $linkTrackingArr = LinkTracking::where("rec_type", "Newsfeed")
            ->join("news_feed", "link_tracking.rec_id", '=', 'news_feed.id')
            ->select(DB::raw('news_feed.name,link_tracking.rec_id as newsfeed_id'))
            ->where("link_tracking.row_id", $rowId)->get();

        $newsfeedWithCount = $linkTrackingArr->groupBy('name')->map(function ($values) {
            return $values->count();
        })->sort()->reverse();

        return $newsfeedWithCount;
    }

    public function getAttributeExtraData($rowId, $companyId, $type = null)
    {

        $data = AttributeData::where("row_id", $rowId)->where("company_id", $companyId);
        if ($type) {

            $data = $data->where("data_type", $type);
        }
        $data = $data->get();
        $finalArr = [];
        foreach ($data as $datum) {

            $finalArr[$datum->code] = $datum->value;
        }
        return $finalArr;
    }

    public function getUserObject($rowId)
    {
        $attributeData = AttributeData::where("row_id", $rowId)->get();
        $obj = (object)[];
        foreach ($attributeData as $data) {
            $obj->row_id = $data->row_id;
            if ($data->code == "user_id")
                $obj->user_id = $data->value;

            else if ($data->code == "firstname")

                $obj->firstname = $data->value;
            else if ($data->code == "email")
                $obj->email = $data->value;
            else if ($data->code == "device_token")
                $obj->device_token = $data->value;
            else if ($data->code == "device_type")
                $obj->device_type = $data->value;

            else if ($data->code == "last_login")
                $obj->last_login = $data->value;

            else if ($data->code == "date_of_birth")
                $obj->date_of_birth = $data->value;

            else if ($data->code == "lastname")
                $obj->lastname = $data->value;

            else if ($data->code == "app_name")
                $obj->app_name = $data->value;

            else if ($data->code == "phone_number")
                $obj->phone_number = $data->value;


            else if ($data->code == "profile_image")
                $obj->profile_image = $data->value;


            else if ($data->code == "latitude")
                $obj->latitude = $data->value;


            else if ($data->code == "longitude")
                $obj->longitude = $data->value;


            else if ($data->code == "app_id")
                $obj->app_id = $data->value;


            else if ($data->code == "lang")
                $obj->lang = $data->value;


            else if ($data->code == "device_type")
                $obj->device_type = $data->value;

        }
        return $obj;
    }

    public function getCampaignUserType($type, $rowid)
    {

        $campaignTrackingArr = CampaignTracking::where("campaign_tracking.row_id", $rowid)
            ->join("campaign", "campaign_tracking.campaign_id", '=', 'campaign.id')
            ->where("campaign.type_id", $type)
            ->where("campaign_tracking.sent", 1)
            ->select(DB::raw('campaign.name,campaign_tracking.campaign_id as campaign_id'))
            ->get();

        $campaignWithCount = $campaignTrackingArr->groupBy('name')->map(function ($values) {
            return $values->count();
        })->sort()->reverse();
        return $campaignWithCount;
    }

    public function getCampaignConversionType($type, $rowid)
    {

        $campaignTrackingArr = UserCampaign::where("user_campaign.row_id", $rowid)
            ->join("campaign", "user_campaign.campaign_id", '=', 'campaign.id')
            ->where("campaign.type_id", $type)
            ->where("user_campaign.rec_type", "conversion")
            ->select(DB::raw('campaign.name,user_campaign.campaign_id as campaign_id'))
            ->get();

        $campaignWithCount = $campaignTrackingArr->groupBy('name')->map(function ($values) {
            return $values->count();
        })->sort()->reverse();
        return $campaignWithCount;
    }

    public function getCompanyLastLogin($companyId, $rowid)
    {

//        $userData = AttributeData::where("value",$rowid)
//            ->where("company_id",$companyId)->get();
//        $rowidsArray = array();
//        foreach ($userData as $data){
//
//            array_push($rowidsArray,$data->row_id);
//        }
        $userDataExtract = AttributeData::where('row_id', $rowid)->where("company_id", $companyId)->get();

        $obj = (object)[];
        $arr = array();
        foreach ($userDataExtract as $data) {

            if ($data->code == "app_name" || $data->code == "last_login") {
                if ($data->code == "last_login")
                    $obj->last_login = $data->value;

                else if ($data->code == "app_name")
                    $obj->app_name = $data->value;
                $arr[] = clone $obj;
            }
        }

        return $arr;
    }

    public function getCompanyAppListing($companyId, $rowid)
    {


        $userDataExtract = AttributeData::where('row_id', $rowid)->where("company_id", $companyId)->get();

        $obj = (object)[];
        $arr = array();
        foreach ($userDataExtract as $data) {

            if ($data->code == "app_name") {

                if ($data->code == "app_name")
                    $obj->app_name = $data->value;
                $arr[] = clone $obj;
            }
        }

        return $arr;
    }


    public static function checkDataFromAttributeTable($companyId, $appName, $userId)
    {

        $sql = "SELECT row_id FROM `attribute_data` WHERE company_id = $companyId AND (`code`='app_name' AND `value`='$appName') AND row_id IN ( SELECT row_id FROM `attribute_data` WHERE company_id = $companyId AND (`code`='user_id' AND `value`=$userId) )";
        $recordCheck = DB::select($sql);
        if (empty($recordCheck)) {

            return null;
        }
        return $recordCheck[0]->row_id;

    }

    public static function getAllDataFromRowId($rowId)
    {

        $dataToSave = UserAttribute::find($rowId);
        $tempArr = [];
        foreach ($dataToSave as $item) {
            $tempArr[$item->code] = $item->value;

        }
        return $tempArr;
    }


    public function conversionAndAction($filePath, $companyId, $importDataId, $dataType)
    {
        $isError = false;
        Excel::selectSheets($dataType)->load($filePath, function ($reader) use ($companyId, $importDataId, $dataType, &$isError) {

            $excelAttributeDataArr = $reader->select(['key', 'value'])->get()->toArray();
            if (!empty($excelAttributeDataArr)) {

                $match = array_diff(['key', 'value'], $reader->get()->getHeading());

                if (!empty($match)) {
                    $isError = true;
                    return;
                }

                $dbData = DB::table('attribute_data')->select('code', 'value')->where(['company_id' => $companyId, 'data_type' => $dataType])->get();

                foreach ($dbData as $dbRow) {

                    $dbArr[] = $dbRow->code;
                }

                $keyValueArr = [];
                $formatedArr = [];
                foreach ($excelAttributeDataArr as $excelAttributeDataKey => $excelAttributeDataRow) {


                    if (
                        !isset($excelAttributeDataRow['key']) &&
                        empty($excelAttributeDataRow['key'])
                    ) {
                        continue;
                    }

                    if (preg_match('/[\'^£$%&*()}{@#~?><>,|=+¬-]/', $excelAttributeDataRow['key'])) {
                        continue;
                    }

                    $excelAttributeDataRow['key'] = strtoupper(str_replace(' ', '_', $excelAttributeDataRow['key']));
                    $keyValue = $excelAttributeDataRow['key'] . '-' . $excelAttributeDataRow['value'];
                    if (!in_array($keyValue, $keyValueArr)) {
                        $keyValueArr[] = $keyValue;

                        $formatedArr[$excelAttributeDataRow['key']][] = $excelAttributeDataRow['value'] ? $excelAttributeDataRow['value'] : "";
                    }
                }
                $this->saveOtherAttribute($companyId, $formatedArr, $importDataId, $dataType);
            }
        });
        return $isError;
    }

    public function saveOtherAttribute($companyId, $formatedArr, $importDataId = null, $dataType, $maxRawId = null)
    {

        $date = Carbon::now();
        foreach ($formatedArr as $code => $formatedRow) {


            if (!$maxRawId) {
                $maxRawId = CommonHelper::generateRowId($companyId);

                $attributeData = DB::table('attribute_data')
                    ->where([
                        ["company_id", "=", $companyId],
                        ["data_type", "=", $dataType],//conversion
                        ["code", "=", $code],
                    ]);
                if ($attributeData->count() > 0) {//if record exist.

                    DB::table('attribute_data')
                        ->where([
                            ["company_id", "=", $companyId],
                            ["data_type", "=", $dataType],//conversion
                            ["code", "=", $code],
                        ])->delete();
                }

            } else {

                DB::table('attribute_data')
                    ->where([
                        ["company_id", "=", $companyId],
                        ["data_type", "=", $dataType],//conversion
                        ["row_id", "=", $maxRawId],//conversion
                        ["code", "=", $code],
                    ])->delete();
            }
            if ($importDataId) {
                foreach ($formatedRow as $key => $value) {


                    $finalData = ['company_id' => $companyId, 'row_id' => $maxRawId, 'code' => $code, 'value' => $value, 'created_at' => $date, 'updated_at' => $date, 'is_import' => true, 'data_type' => $dataType];
                    DB::table('attribute_data')->insert($finalData);
                }
            } else {

                $finalData = ['company_id' => $companyId, 'row_id' => $maxRawId, 'code' => $code, 'value' => $formatedRow, 'created_at' => $date, 'updated_at' => $date, 'is_import' => true, 'data_type' => $dataType];
                DB::table('attribute_data')->insert($finalData);

            }
        }
    }


    public function storeAttributeData($postedData, $companyId, $dataFromCache = null, $importDataId = false)
    {

        $finalData = array();
        $companyCodeArr = CommonHelper::getAttributeData($companyId, $importDataId);
        $date = Carbon::now();


        if (!$dataFromCache) {

            $userAttributeData = CompanyAttributeData::getUserData($companyId, $postedData);
            if ($userAttributeData) {


                $extraData = CommonHelper::getAttributeDataExtra($userAttributeData->row_id, $companyId);
                $userAttributeData = (object)array_merge((array)$extraData, (array)$userAttributeData);
                $cacheKey = CommonHelper::generateCacheKey($companyId, $postedData['app_name'], $postedData['user_id']);

                $dataTOSave = json_encode(array("row_id" => $userAttributeData->row_id, "company_id" => $companyId, "data" => $userAttributeData));

                Cache::forget($cacheKey);
                Cache::forever($cacheKey, $dataTOSave);
                return $this->storeAttributeData($postedData, $companyId, $dataTOSave, $importDataId);

            } else {

                list($maxRawId, $extraData) = CompanyAttributeData::storeUserAttributes($companyId, $postedData);
            }

            foreach ($extraData as $key => $value) {

                if ((!empty($value) || $value == 0) && in_array(strtolower($key), array_map('strtolower', $companyCodeArr)) && !in_array($key, ['company_key'])) {

                    $finalData[] = ['company_id' => $companyId, 'row_id' => $maxRawId, 'code' => $key, 'value' => $value, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now(), 'is_import' => $importDataId, 'data_type' => 'user'];
                }
            }

            if (!empty($finalData)) {
                DB::table('attribute_data')->insert($finalData);
            }
            CompanyAttributeData::updateRow($companyId, $maxRawId);
            return array("row_id" => $maxRawId, "company_id" => $companyId, "data" => $postedData);
        } else {


            $decodedAttributeData = json_decode($dataFromCache, true);

            $specificAppAllData = $decodedAttributeData['data'];

            if (CompanyAttributeData::getUserData($companyId, $postedData)) {
                list($rowId, $extraData) = CompanyAttributeData::storeUserAttributes($companyId, $postedData, $decodedAttributeData['row_id']);
            } else {

                list($rowId, $extraData) = CompanyAttributeData::storeUserAttributes($companyId, $postedData);
            }
            if ($importDataId == 0) {

                foreach ($extraData as $key => $value) {
                    if ((!empty($value) || $value == 0) && in_array($key, $companyCodeArr) && !in_array($key, ['company_key'])) {

                        if (!array_key_exists($key, $specificAppAllData)) {

                            $finalData = ['company_id' => $companyId, 'row_id' => $rowId, 'code' => $key, 'value' => $value, 'created_at' => $date, 'updated_at' => $date, 'is_import' => true, 'data_type' => 'user'];

                            DB::table('attribute_data')->insert($finalData);
                        } else if (array_key_exists($key, $specificAppAllData)) {


                            $finalData = ['value' => $value, 'updated_at' => $date];
                            $whereCluase = ['company_id' => $companyId, 'row_id' => $rowId, 'code' => $key];
                            $atrributeDataCheck = DB::table('attribute_data')->where($whereCluase);
                            if ($atrributeDataCheck->count() > 0) {
                                $atrributeDataCheck->update($finalData);
                            } else {

                                $finalData = ['company_id' => $companyId, 'row_id' => $rowId, 'code' => $key, 'value' => $value, 'created_at' => $date, 'updated_at' => $date, 'is_import' => true, 'data_type' => 'user'];

                                DB::table('attribute_data')->insert($finalData);
                            }
                        }
                    }
                }
            } else {

                $extraData = CompanyAttributeData::getExtraAttributes($postedData);
                foreach ($extraData as $key => $value) {


                    if ((!empty($value) || $value == 0) && in_array($key, $companyCodeArr) && !in_array($key, ['company_key'])) {

                        if (!is_array($value)) {
                            $attributeID = AttributeData::where("company_id", $companyId)->where("row_id", $rowId)->where("code", $key)->select("id")->first();
                            $id = null;
                            if ($attributeID) {

                                $id = $attributeID->id;
                                $finalData = [$id, $companyId, $rowId, '\'' . $key . '\'', '\'' . $value . '\'', '\'' . $companyId . '\'', '\'' . $date . '\'', '\'' . $companyId . '\'', '\'' . $date . '\'', $importDataId, '\'' . 'user' . '\''];
                            } else {

                                $finalData = [$companyId, $rowId, '\'' . $key . '\'', '\'' . $value . '\'', '\'' . $companyId . '\'', '\'' . $date . '\'', '\'' . $companyId . '\'', '\'' . $date . '\'', $importDataId, '\'' . 'user' . '\''];
                            }
                            $this->updateRowAttribute($finalData, $id);
                        }
                    }

                }
            }
            $dataTOSave = array_merge($specificAppAllData, $postedData);

            CompanyAttributeData::updateRow($companyId, $rowId);
            return array("row_id" => $rowId, "company_id" => $companyId, "data" => $dataTOSave);
        }
    }

    public function storeAttributeDataFromSDK($postedData, $companyId, $dataFromCache = null)
    {

        $companyCodeArr = CommonHelper::getAttributeData($companyId, null);
        $date = Carbon::now();
        if (!$dataFromCache) {
            //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n dataFromCache is empty:". PHP_EOL ,FILE_APPEND);
            $userAttributeData = CompanyAttributeData::getUserData($companyId, $postedData);
            if ($userAttributeData) {
                //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n storeAttributeDataFromSDK function - data found in user attribute:".print_r($userAttributeData,true). PHP_EOL ,FILE_APPEND);
                $rowId = $userAttributeData->row_id;
                $extraData = CommonHelper::getAttributeDataExtra($userAttributeData->row_id, $companyId);
                $userAttributeData = (object)array_merge((array)$extraData, (array)$userAttributeData);

                $cacheKey = CommonHelper::generateCacheKey($companyId, $postedData['app_name'], $postedData['user_id']);

                $dataTOSave = json_encode(array("row_id" => $rowId, "company_id" => $companyId, "data" => $userAttributeData));

                
                Cache::forever($cacheKey, $dataTOSave);
                //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n storeAttributeDataFromSDK function - data to save in cache:".print_r($dataTOSave,true). PHP_EOL ,FILE_APPEND);
                $dataFromCache  = $dataTOSave;

            }

            list($dataTOSave,$rowId) = $this->handleData($dataFromCache,$companyId,$postedData,$companyCodeArr,$date);
        } else {
            
            //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n dataFromCache is NOT empty:".print_r($dataFromCache,true). PHP_EOL ,FILE_APPEND);
            list($dataTOSave,$rowId) = $this->handleData($dataFromCache,$companyId,$postedData,$companyCodeArr,$date);

        }

        CompanyAttributeData::updateRow($companyId,$rowId);

        return array("row_id" => $rowId, "company_id" => $companyId, "data" => $dataTOSave);
    }


    public function handleData($dataFromCache,$companyId,$postedData,$companyCodeArr,$date)
    {

        $decodedAttributeData = json_decode($dataFromCache, true);

        $specificAppAllData = $decodedAttributeData['data'];

        //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n Row ID from Cache:". print_r($decodedAttributeData['row_id'],true) . PHP_EOL ,FILE_APPEND);
        if (CompanyAttributeData::getUserData($companyId, $postedData)) {
            //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n getUserData from DB Found:". PHP_EOL ,FILE_APPEND);
            list($rowId, $extraData) = CompanyAttributeData::storeUserAttributes($companyId, $postedData, $decodedAttributeData['row_id']);
        } else {
            //file_put_contents('storage/logs/signup-'.$companyId.'-'.$postedData["user_id"].'.log', date("Y-m-d H:i:s")." \n getUserData from DB Not Found:". PHP_EOL ,FILE_APPEND);
            list($rowId, $extraData) = CompanyAttributeData::storeUserAttributes($companyId, $postedData);
        }

        foreach ($extraData as $key => $value) {
            if ((!empty($value) || $value == 0) && in_array($key, $companyCodeArr) && !in_array($key, ['company_key'])) {

                if (!$specificAppAllData || !array_key_exists($key, $specificAppAllData)) {

                    $finalData = ['company_id' => $companyId, 'row_id' => $rowId, 'code' => $key, 'value' => $value, 'created_at' => $date, 'updated_at' => $date, 'is_import' => true, 'data_type' => 'user'];

                    DB::table('attribute_data')->insert($finalData);
                } else if (array_key_exists($key, $specificAppAllData)) {


                    $finalData = ['value' => $value, 'updated_at' => $date];
                    $whereCluase = ['company_id' => $companyId, 'row_id' => $rowId, 'code' => $key];
                    $atrributeDataCheck = DB::table('attribute_data')->where($whereCluase);
                    if ($atrributeDataCheck->count() > 0) {
                        $atrributeDataCheck->update($finalData);
                    } else {

                        $finalData = ['company_id' => $companyId, 'row_id' => $rowId, 'code' => $key, 'value' => $value, 'created_at' => $date, 'updated_at' => $date, 'is_import' => true, 'data_type' => 'user'];

                        DB::table('attribute_data')->insert($finalData);
                    }
                }
            }
        }

        if($specificAppAllData) {
            $dataTOSave = array_merge($specificAppAllData, $postedData);

        }else{
            $dataTOSave =  $postedData;
        }
        return array($dataTOSave,$rowId);
    }
    public function updateRowAttribute($data, $id)
    {

        if ($id) {
            $sql = "REPLACE INTO attribute_data(`id`,`company_id`,`row_id`,`code`,`value`,`created_by`,`created_at`,`updated_by`,`updated_at`,`is_import`,`data_type`) VALUES";

        } else {

            $sql = "REPLACE INTO attribute_data(`company_id`,`row_id`,`code`,`value`,`created_by`,`created_at`,`updated_by`,`updated_at`,`is_import`,`data_type`) VALUES";

        }

        $sql .= '(' . implode(',', $data) . ');';
        DB::statement($sql);
    }

    public function getCustomUserAttributes($postedData)
    {

        $userCampaign = UserCampaign::where("user_id", $postedData['user_id'])->where("rec_type", $postedData['rec_type'])
            ->join("lookup", "user_campaign.event_id", '=', 'lookup.id')->orderBy("user_campaign.id", "desc")->select("lookup.name", "user_campaign.event_id as id", "user_campaign.event_value as value")->get();
        return $userCampaign;
    }
}