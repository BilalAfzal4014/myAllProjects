<?php

namespace App\Http\Controllers;

use App\Apps;
use App\Attribute;
use App\AttributeData;
use App\UserAttribute;
use App\Campaign;
use App\CompileTags;
use App\Components\CompanyAttributeData;
use App\Helpers\CommonHelper;
use App\Jobs\ImportJob;
use App\LinkTracking;
use App\Lookup;
use App\UserCampaign;
use Carbon\Carbon;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Http\Request;

use App\Http\Requests;

use App\Engagment\AttributeData\AttributeDataWrapper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Validator;
use DB;
use stdClass;
use Maatwebsite\Excel\Facades\Excel;
use Session;


class AttributeDataController extends Controller
{

    public $attributeDataWrapper;

    use CompileTags;

    public function __construct(AttributeDataWrapper $attributeDataWrapper)
    {
        $this->attributeDataWrapper = $attributeDataWrapper;
    }

    public function otherAttributedata()
    {
        $attributeobj = array(
            'id' => '0',
            'action' => '',
            'code' => '',
            'value' => ''
        );
        $companyId = Auth::user()->id;
        $platform = CommonHelper::$_PLATFORM_COMPANY;
        $lookuplisitng = Lookup::where('company_id', '=', $companyId)->where('level', '=', $platform)->get();
        return view('attributeData.createOtherAttributeData')->with([
            'companyId' => $companyId,
            'otherAttributeData' => $lookuplisitng,
            'attributeobj' => $attributeobj
        ]);
    }

    public function editOtherAttributedata($id)
    {
        $attributelist = $attributeData = AttributeData::where('id', $id)->first();
        $attributeobj = array(
            'id' => $attributelist->id,
            'action' => $attributelist->data_type,
            'code' => $attributelist->code,
            'value' => $attributelist->value
        );
        $companyId = Auth::user()->id;
        $platform = CommonHelper::$_PLATFORM_COMPANY;
        $lookuplisitng = Lookup::where('company_id', '=', $companyId)->where('level', '=', $platform)->get();
        return view('attributeData.createOtherAttributeData')->with([
            'companyId' => $companyId,
            'otherAttributeData' => $lookuplisitng,
            'attributeobj' => $attributeobj
        ]);

    }

    public function lookupCodeListing($type)
    {
        $action = '';
        $platform = CommonHelper::$_PLATFORM_COMPANY;
        $id = Auth::user()->id;
        $name = Auth::user()->name;
        if ($type == 0) {
            $action = CommonHelper::$_ACTION_TRIGGER;
        } else {
            $action = CommonHelper::$_CONVERSTION_TYPES;
        }
        $parentid = Lookup::where('code', '=', $action)->first();
        if (count($parentid) > 0) {
            $lookuplisting = Lookup::where(['company_id' => $id], ['level' => $platform])->where('parent_id', $parentid->id)->get();
            if (count($lookuplisting) > 0) {
                $response = $this->attributeDataWrapper->successResponse('listingFound', $lookuplisting);
            } else {
                $response = $this->attributeDataWrapper->failedResponse('listingFound');
            }
        } else {
            $response = $this->attributeDataWrapper->failedResponse('listingFound');
        }
        return $response;
    }

    public function saveOtherAttribute(Request $request)
    {
        $companyId = Auth::user()->id;
        $allinput = $request->all();
        $id = $request->input('attributeid');
        if ($id != 0) {
            $attributeCheck = AttributeData::
            where('code', $request->input('code'))
                ->where('value', $request->input('value'))
                ->where('company_id', $companyId)
                ->get();
            if (count($attributeCheck) > 0) {
                return redirect('/other-attribute-data-view')->with(['flash_message' => 'Attribute data key value already exist in table']);
            } else {
                $attributeCheck = AttributeData::where('id', $id)
                    ->update([
                        'value' => $request->input('value')
                    ]);
                if ($attributeCheck) {
                    return redirect('/other-attribute-data-view')->with(['flash_message' => 'Attribute data value added']);
                } else {
                    return redirect('/other-attribute-data-view')->with(['flash_message' => 'failed']);
                }
            }
        } else {

            $action = $request->input('actionType');
            if ($action == 0) {
                $action = 'action';
            } else {
                $action = 'conversion';
            }

            $rowId = CommonHelper::generateRowId($companyId);
            $attributeCheck = AttributeData::where('code', '=', $request->input('code'))
                ->where('value', '=', $request->input('value'))
                ->where('company_id', $companyId)
                ->get();
            //  dd($attributeCheck);
            if (count($attributeCheck) == 0) {
                $attributeModel = new AttributeData();
                $attributeModel->company_id = $companyId;
                $attributeModel->row_id = $rowId;
                $attributeModel->code = $request->input('code');
                $attributeModel->value = $request->input('value');
                $attributeModel->updated_at = 0;
                $attributeModel->is_import = 0;
                $attributeModel->data_type = $action;
                $result = $attributeModel->save();
                if ($result) {
                    return redirect('/other-attribute-data-view')->with(['flash_message' => 'Attribute data added']);
                } else {
                    return redirect('/other-attribute-data-view')->with(['flash_message' => 'Failed']);
                }
            } else {
                return redirect('/other-attribute-data-view')->with(['flash_message' => 'Attribute data key value already exist in table']);
            }
        }
    }

    public function otherAttributeDataView()
    {
        $companyId = Auth::user()->id;
        return view('attributeData.otherAttributeData', ['companyId' => $companyId]);
    }

    public function deleteOtherAttributeData($id)
    {
        $result = AttributeData::where('id', $id)->delete();
        if ($result) {
            $response = $this->attributeDataWrapper->successResponse('Attribute Data Record Has Been Deleted', '');
        } else {
            $response = $this->attributeDataWrapper->failedResponse('Failed');
        }
        return $response;
    }

    public function otherAttributeDataDT(Request $request)
    {
        $companyId = Auth::user()->id;

        list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataWrapper->getOtherAttributeDataDT($request, $companyId);

        $arrayTemp = [];
        foreach ($attributeDataListing as $attr) {

            array_push($arrayTemp, [
                'id' => $attr->id,
                'code' => $attr->code,
                'value' => $attr->value,
                'created_at' => $attr->created_at,
                'data_type' => $attr->data_type,
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $arrayTemp,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Attribute Data listing'
        ]);
    }


    public function emailListView()
    {
        //$companyId = Auth::user()->id;
        return view('attributeData.emailList');
    }

    public function emailListDT(Request $request)
    {
        $companyId = Auth::user()->id;

        list($totalData, $totalFiltered, $emailList) = $this->attributeDataWrapper->getEmailListDT($request, $companyId);
        $arrayTemp = [];
        foreach ($emailList as $attr) {

            array_push($arrayTemp, [
                'id' => $attr->id,
                'email' => $attr->email,
                'type' => $attr->rec_type,
                'created_date' => $attr->created_at
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $arrayTemp,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'email list.'
        ]);
    }

    public function deleteEmailList(Request $request)
    {
        $id = $request->get('id');
        DB::table('email_list')->where(['id' => $id])->delete();
        return '<div class="alert alert-success">
                           <strong>Success!</strong> Record is deleted successfully.
                        </div>';
    }


    public function attributeDataView()
    {
        $companyId = Auth::user()->id;
        $appCount = self::appCount($companyId);
        return view('attributeData.attributeData', ['companyId' => $companyId, 'appCount' => $appCount]);
    }

    public function getUserAttributeDataCache(Request $request)
    {

        $companyId = Auth::user()->id;
        $rowId = $request->rowId;
        $type = $request->type;
        $dataToPopulate = [];
        switch ($type) {

            case 'user':
                $dataToPopulate = CommonHelper::getAttributeDataExtra($rowId, $companyId);
                break;
            case 'action':
            case 'conversion':
                $dataToPopulate = $this->attributeDataWrapper->getAttributeDataExtra($rowId, $companyId, $type);
                break;
        }

        if ($dataToPopulate) {

            return new JsonResponse(array(
                "status" => 200,
                "data" => $dataToPopulate
            ));
        } else {

            return new JsonResponse(array(
                "status" => 500,
                "data" => null
            ));
        }
    }

    public function attributeDataStat(Request $request)
    {

        $id = $request->id;
        $companyId = Auth::user()->id;
        $response = UserAttribute::where("row_id", $id)->where('company_id', '=', $companyId)->first();
        if ($response) {
            $attributeDataObj = CommonHelper::getAllUserData($id, $companyId);
            return view('users.userStats', array(
                "data" => (object)$attributeDataObj
            ));
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function attributeDataViewFilter(Request $request)
    {
        $companyId = Auth::user()->id;


        list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataWrapper->getAttributeDataListing($request, $companyId);


        $arrayTemp = [];
        foreach ($attributeDataListing as $attr) {

            $rowId = "";
            $userId = "";
            $device_type = "";
            $email = "";
            $appId = "";
            $lastLogin = "";
            $isActive = 1;
            $profile_image = "<img src=" . asset('assets/images/profile_placeholder.png') . ">";
            if (isset($attr->row_id)) {
                $rowId = $attr->row_id;
            }
            if (isset($attr->device_type)) {
                $device_type = $attr->device_type;
            }
            if (isset($attr->email) || isset($attr->firstname) || isset($attr->user_id)) {

                if (isset($attr->user_id)) {
                    if ($attr->user_id == 443) {
                    }
                    $email .= '<b>User Id:</b> ' . $attr->user_id . ' <br>';
                    $userId = $attr->user_id;
                }

                if (isset($attr->firstname)) {
                    $email .= '<b>Name: </b>' . $attr->firstname . ' <br>';
                }
                if (isset($attr->email)) {
                    $email .= '<b>Email: </b>' . $attr->email;
                }
            }
            if (isset($attr->app_name)) {
                $appId = $attr->app_name;
            }
            if (!empty($attr->profile_image)) {
                $profile_image = "<img src=" . $attr->profile_image . ">";
            }
            if (isset($attr->last_login)) {
                $lastLogin = $attr->last_login;
            }
            if (isset($attr->is_active)) {

                $isActive = $attr->is_active;
            }

            array_push($arrayTemp, [
                $rowId,
                $profile_image,
                $email,
                $device_type,
                $appId,
                $lastLogin,
                $userId,
                $isActive


            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $arrayTemp,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign listing'
        ]);
    }

    public function attributeDataListing(Request $request, $companyId)
    {
        list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataWrapper->getAttributeDataListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $attributeDataListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Attribute Data listing'
        ]);
    }

    public function deleteAttr(Request $request)
    {
        $userId = $request->get('userId');
        $appName = $request->get('appName');
        $action = $request->get('action');
        $companyId = Auth::user()->id;
        $this->attributeDataWrapper->operateDelete($companyId, $appName, $userId, $action);
        return response()->json([
            'status' => true,
        ]);
    }


    public function importAttributeDataView()
    {
        return view('attributeData.importAttributeData');
    }

    public function companyStats()
    {
        $companyId = Auth::user()->id;
        return view('attributeData.companyStats', ['companyId' => $companyId]);
    }

    public function campaignTrackingType(Request $request, $companyId)
    {

        $type = $request->type;
        $rowId = $request->rowId;
        $responseData = $this->attributeDataWrapper->getCampaignUserStatType($type, $rowId);
        return $responseData;
    }

    public function getCampaignConversion(Request $request)
    {

        $type = $request->type;
        $rowId = $request->rowId;
        $responseData = $this->attributeDataWrapper->getCampaignConversionStatType($type, $rowId);
        return $responseData;
    }

    public function getAppLastLogin(Request $request)
    {

        $companyId = Auth::user()->id;
        $rowId = $request->rowId;
        $responseData = $this->attributeDataWrapper->getCompanyLastLogin($companyId, $rowId);
        return $responseData;
    }

    public function getAppListing(Request $request)
    {

        $companyId = Auth::user()->id;
        $rowId = $request->rowId;
        $responseData = $this->attributeDataWrapper->getCompanyAppListing($companyId, $rowId);
        return $responseData;
    }

    public function getProfileDetails(Request $request, $companyId)
    {

        $data = $this->attributeDataWrapper->getEmailListDT($request, $companyId);
        $triggerArr = [];
        $campaignArr = [];
        $trigggerActionList = UserCampaign::where(["rec_type" => "action_trigger", "company_id" => $companyId])->limit(10)->orderBy("id", "DESC")->get();
        foreach ($trigggerActionList as $item) {

            $triggerArr [] = [
                "id" => $item->id,
                "action_name" => $this->getActionNameFromId($item->event_id),
                "action_value" => $item->event_value,
            ];
        }
        $campaignActionList = UserCampaign::where(["rec_type" => "conversion", "company_id" => $companyId])->limit(10)->orderBy("id", "DESC")->get();

        foreach ($campaignActionList as $item) {

            $campaignArr [] = [
                "id" => $item->id,
                "action_name" => $this->getActionNameFromId($item->id),
                "action_value" => $item->event_value,
            ];
        }

        $userProfile = $this->attributeDataWrapper->getProfileDetails($companyId);

        return response()->json([
            'status' => true,
            'data' => ["trigger" => $triggerArr, "conversion" => $campaignArr, "profile" => $userProfile],
            'message' => 'Profile Information'
        ]);
    }

    public function getActionNameFromId($id)
    {

        $lookUp = Lookup::find($id);
        if ($lookUp) {
            return $lookUp->name;
        } else {
            return $id;
        }
    }

    public function getCustomAttributes($companyId)
    {
        return response()->json([
            'status' => true,
            'data' => $this->attributeDataWrapper->getCustomAttributes($companyId),
            'message' => 'Custom Attributes'
        ]);
    }

    public function getTokens(Request $request, $companyId)
    {

        list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataWrapper->getEmailListDT($request, $companyId);

        return response()->json([
            'status' => true,
            'data' => $attributeDataListing,
            'message' => 'Custom Attributes'
        ]);
    }

    public function getCampaignClick($companyId)
    {
        return response()->json([
            'status' => true,
            'data' => $this->attributeDataWrapper->getCampaignClick($companyId),
            'message' => 'Campaign Clicks'
        ]);
    }

    public function getSegmentsInfo($companyId)
    {
        return response()->json([
            'status' => true,
            'data' => $this->attributeDataWrapper->getSegmentsInfo($companyId),
            'message' => 'Segment Information'
        ]);
    }

    public function testDelete(Request $request)
    {

        $info = curl_version();
        dd($info);
        /**
         * @var $disk FilesystemAdapter
         */
        $disk = Storage::disk('s3');
        dump($disk->allFiles());
        die;

        $campaigns = Campaign::all();
        $campaignIds = $campaigns->pluck('id')->unique()->toArray();

        foreach ($campaignIds as $campaignId) {
            $cache_key = "company_{$companyId}_campaign_{$campaignId}_tracking";

            \Cache::forget($cache_key);

        }
        return new JsonResponse(array(
            "success" => 200,
            "file_delete" => $fileCount
        ));
    }

    public function testDeleteFile(Request $request)
    {


        $companyId = $request->company_id;
        $getAllAttributes = $this->attributeDataWrapper->getListingForDataTable($companyId);
        foreach ($getAllAttributes as $allAttribute) {

            if (isset($allAttribute->app_name) && $allAttribute->user_id) {
                $cahcheKey = CommonHelper::generateCacheKey($companyId, $allAttribute->app_name, $allAttribute->user_id);

                Cache::forget($cahcheKey);
            }
        }

        /**
         * @var $disk FilesystemAdapter
         */
        $disk = Storage::disk('importJson');
        $fileCount = $disk->deleteDirectory('company_' . $companyId);

        return new JsonResponse(array(
            "success" => 200,
            "file_delete" => $fileCount
        ));
    }

    public function getNewsfeedClick($rowId)
    {


        return response()->json([
            'status' => true,
            'data' => $this->attributeDataWrapper->getNewsfeedClick($rowId),
            'message' => 'Segment Information'
        ]);
    }


    public function importFileView(Request $request)
    {
        $companyId = Auth::user()->id;
//        Session::flash('MSG', 'Usman');
        /**
         * @var $disk FilesystemAdapter
         */
        $disk = Storage::disk('s3');
        $fileCount = count($disk->allFiles('company_' . $companyId));
        return view('attributeData.importData', array("fileCount" => $fileCount));
    }


    public function importFileViewFilter(Request $request)
    {

        $companyId = Auth::user()->id;


        if (in_array($request->operation, ["completed", "inprocess"])) {
            $dataImportList = DB::table('import_data')->where(['is_processed' => 1, 'is_deleted' => 0, 'company_id' => $companyId])->get();

        } elseif ($request->operation == "pending") {

            $dataImportList = DB::table('import_data')->where(['is_processed' => 0, 'is_deleted' => 0, 'company_id' => $companyId])->get();
        }

        $arrayTemp = [];
        foreach ($dataImportList as $item) {

            $fileCount = $this->attributeDataWrapper->getFileCount($item, $companyId);
            if ($item->is_processed && $fileCount == 0) {

                $fileStatus = "Completed";
            } else {

                $fileStatus = $item->is_processed ? "In Process" : "Pending";
            }

            array_push($arrayTemp, [
                $item->id,
                $item->file_name,
                $item->file_size,
                $item->created_at,
                $fileStatus,
                $fileCount,
                $item->process_date,
                view('attributeData.ajax.action', ["data" => $item])->render()

            ]);
        }

        if ($request->operation == "inprocess") {

            $arrayTemp = $this->getInProcessData($arrayTemp, 'In Process');
        } elseif ($request->operation == "completed") {
            $arrayTemp = $this->getInProcessData($arrayTemp, 'Completed');

        }
        $arrayToReturn['data'] = $arrayTemp;
        return new Response(json_encode($arrayToReturn));
    }


    public function getInProcessData($arrayTemp, $type)
    {

        $tempArr = [];
        foreach ($arrayTemp as $item) {

            if ($item[4] == $type) {

                array_push($tempArr, $item);
            }
        }
        return $tempArr;
    }

    public function importFileListing(Request $request)
    {

        $companyId = Auth::user()->id;
        list($totalData, $totalFiltered, $attributeDataListing) = $this->attributeDataWrapper->getImportFileListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $attributeDataListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Import File listing'
        ]);
    }


    public function importFileDelete(Request $request)
    {

        $id = $request->get('id');
        DB::table('import_data')->where(['id' => $id])->update(['is_deleted' => 1]);
        Session::flash('MSG', '<div class="alert alert-success"><strong>Success!</strong> Record is deleted successfully.</div>');
        return response()->json([
            'status' => true,
        ]);
    }


    public function uploadFile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'imported_file' => 'required'
        ]);

        if ($validator->fails()) {
            //dd($validator->errors()->toArray());
            return response('This file is not allowed.', 500);
        }

        $bytes = $request->file('imported_file')->getClientSize();
        if ($bytes > config("engagement.allowed_upload_limit")) {

            return response(' File Size Exceed the limit of ' . config("engagement.allowed_upload_limit") / 1048576 . ' MB.', 500);

        }
        $destinationPath = storage_path('attribute_data_file');
        $destinationPath = $destinationPath . '/company_' . Auth::user()->id;
        $actualFileName = $request->file('imported_file')->getClientOriginalName();
        $extension = $request->file('imported_file')->getClientOriginalExtension();

        if ($extension !== 'xlsx') {

            throw new \RuntimeException('Invalid File Type');
        }
        $fileSize = $this->attributeDataWrapper->formatSizeUnits($bytes);
        $fileName = time() . '-' . rand(11111, 99999) . '-' . $actualFileName;
        $request->file('imported_file')->move($destinationPath, $fileName);
        \DB::table('import_data')->insert([
            ['company_id' => \Auth::user()->id,
                'actual_file_name' => $actualFileName,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'created_by' => \Auth::user()->id,
                'created_at' => Carbon::now()]
        ]);
        Session::flash('MSG', '<div class="alert alert-success"><strong>Success!</strong>File is upload successfully.</div>');
        return response('Yes', 200);
    }


    public function testDeleteFileUpload(Request $request)
    {


        /**
         * @var  $disk FilesystemAdapter
         */
        $disk = \Storage::disk("importJson");
        dump($disk->get('company_20/1538052630_company_data_import_204541.json'));
        die;
//        $appLIsting = Apps::where("company_id",20)->where("is_active",1)->select("name",'app_id')->get()->toArray();
//        $appNameListing = array_pluck($appLIsting,'name');
//        dump(in_array('CoreDirection',$appNameListing),$appNameListing);die;
        $disk = \Storage::disk("importJson");

        $items = json_decode(
            $disk->get('test.json'),
            true
        );


        foreach ($items as $item) {
            if (isset($item['last_login'])) {
                $item['last_login'] = $item['last_login']['date'];
            }

            if ((isset($item['user_id']) && $item['user_id'] != "") && (isset($item['app_name']) && $item['app_name'] != "")) {
                $this->attributeDataWrapper->saveAttributeData(20, $item, 5);
            }
        }
        return view('attributeData.importData', array("fileCount" => 0));
    }

    public function importTargetedUsers(Request $request)
    {
        ini_set('memory_limit',config('engagement.memory_limit'));
        ini_set('max_execution_time',config('engagement.max_execution_time'));
        $pattern = preg_quote('#$%^&* ()+=-[]\';,./{}|\":<>?~', '#');
        $companyId = Auth::user()->id;
        $importDataId = $request->id;
        $fileName = \DB::table('import_data')
            ->select('file_name')
            ->where(['id' => $importDataId])
            ->first()->file_name;

        $filePath = storage_path('/attribute_data_file/company_' . $companyId . '/' . $fileName);
        if ($filePath) {

            $sheets = Excel::load($filePath)->get();
            foreach ($sheets as $sheet) {

                /**
                 * @var CellCollection $item
                 */
                /**
                 * @var CellCollection $sheet
                 */
                if ($sheet->getTitle() == 'standard') {


                    foreach ($sheet as $item) {


                        /**
                         * @var \ArrayIterator $itemIterator
                         */
                        $itemIterator = $item->getIterator()->getArrayCopy();
                        $data = [];
                        $data['company_id'] = $companyId;
                        $data['code'] = strtolower($itemIterator['code']);
                        $code = $data['code'];
                        $count = Attribute::where($data)->count();
                        if (!preg_match("#[{$pattern}]#", $code) && $count == 0 && isset($data['code']) && $data['code'] != '') {


                            $data['name'] = $code;
                            $data['alias'] = $code;
                            $data['data_type'] = $itemIterator['data_type'];
                            $data['length'] = $itemIterator['length'];
                            $data['type'] = 'custom';
                            $data['is_deleted'] = 0;
                            $data['created_at'] = Carbon::now();
                            $data['updated_at'] = Carbon::now();
                            $data['created_by'] = $companyId;
                            $data['updated_by'] = $companyId;
                            \DB::table('attribute')->insert($data);

                        }
                    }


                } elseif ($sheet->getTitle() == 'user') {

                    $chunkedData = $sheet->chunk(env("IMPORT_DATA_CHUNK_SIZE", 500));
                    $directory = 'company_' . $companyId . '/' . 'attribute_file_' . $importDataId;
                    /**
                     * @var $disk FilesystemAdapter
                     */
                    $disk = Storage::disk('s3');
                    if (!$disk->exists($directory)) {

                        $disk->makeDirectory($directory);
                    } else {

                        $disk->deleteDirectory($directory);
                    }
                    foreach ($chunkedData as $item) {

                        /**
                         * @var \ArrayIterator $itemIterator
                         */
                        $excelAttributeDataRow = $item->getIterator()->getArrayCopy();
                        $excelAttributeDataRowJson = json_encode($excelAttributeDataRow);
                        $fileName = time() . "_company_data_import_" . $companyId . rand(1, 12000) . '.json';

                        $disk->put($directory . '/' . $fileName, $excelAttributeDataRowJson, 'public');

                        $new_job = array(

                            'job_interval' => "",
                            'job_file_name' => $fileName,
                            'import_data_id' => $importDataId,
                            'attributeDataWrapper' => $this->attributeDataWrapper,
                            'company_id' => $companyId

                        );

                        $interval = config('engagement.queue.interval');
                        \Queue::laterOn('import', $interval, new ImportJob($new_job, $this->attributeDataWrapper));
                    }

                } elseif ($sheet->getTitle() == 'conversion') {

                    $this->attributeDataWrapper->conversionAndAction($filePath, $companyId, $importDataId, 'conversion');
                } elseif ($sheet->getTitle() == 'action') {

                    $this->attributeDataWrapper->conversionAndAction($filePath, $companyId, $importDataId, 'action');
                } elseif ($sheet->getTitle() == 'app') {

                    $this->attributeDataWrapper->conversionAndAction($filePath, $companyId, $importDataId, 'app');
                } elseif ($sheet->getTitle() == 'gamification') {

                    $this->attributeDataWrapper->conversionAndAction($filePath, $companyId, $importDataId, 'gamification');
                } else {
                    continue;
                }
            }

        }
        $MSG = 'Data is imported successfully.';
        $STATUS = 200;
        \DB::table('import_data')->where(['id' => $importDataId])->update([
            'is_processed' => 1,
            'process_date' => Carbon::now()
        ]);

        return response($MSG, $STATUS);
    }

    public function downloadSampleFile()
    {
        $pathToFile = storage_path('sample_att_data_file/sample-attribute-data-file.xlsx');
        return response()->download($pathToFile);
    }

    public
    function downloadADFile(Request $request)
    {
        $importDataId = $request->id;
        $fileName = DB::table('import_data')->select('file_name')->where(['id' => $importDataId])->first()->file_name;

        $filePath = storage_path('attribute_data_file/company_' . Auth::user()->id . '/' . $fileName);
        return response()->download($filePath);
    }

    public function attributeList()
    {
        $companyId = Auth::user()->id;
        return view('attributes.attributelist')->with('companyId', $companyId);
    }

    public function createAttribute()
    {
        $attributdata = array(
            'id' => '',
            'name' => '',
            'code' => '',
            'data_type' => '',
            'length' => '',
            'type' => ''
        );
        $datatype = [
            0 => 'INT',
            1 => 'VARCHAR',
            2 => 'DATE'
        ];
        return view('attributes.createAttributes')->with(['attributdata' => $attributdata, 'datatype' => $datatype]);
    }

    public function attributeListing(Request $request)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'data_type',
            4 => 'length',
            5 => 'type'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
        $myQuery = Attribute::where('is_deleted', '0')
            ->where('company_id', $companyId);
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery = $myQuery->where(function ($query) use ($search) {
                $query->orWhere('code', 'LIKE', "%{$search}%");
                $query->orWhere('name', 'LIKE', "%{$search}%");
                $query->orWhere('name', 'LIKE', "%{$search}%");
                $query->orWhere('data_type', 'LIKE', "%{$search}%");

            });
        }
        switch ($filterType) {
            case 'app_name':
                $myQuery = $myQuery->where('type', $filter)
                    ->where('is_deleted', '0')
                    ->where('company_id', $companyId);
                break;
        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get();
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Attribute Data Found'
        ]);
    }

    public function deleteAttributes($id)
    {
        $result = Attribute::where('id', $id)->update([
            'is_deleted' => '1'
        ]);
        if ($result) {
            $response = $this->attributeDataWrapper->successResponse('Attribute Record Has Been Deleted', '');

        } else {
            $response = $this->attributeDataWrapper->failedResponse('Failed');

        }
        return $response;
    }

    public function attributeDuplicationCheck($code)
    {
        $companyId = Auth::user()->id;
        $attribute = Attribute::where('code', '=', $code)->where('company_id', $companyId)->get();
        if (count($attribute) > 0) {
            $response = $this->attributeDataWrapper->successResponse('This code already exist', '');
        } else {
            $response = $this->attributeDataWrapper->failedResponse('Failed');

        }
        return $response;
    }

    public function saveAttribute(Request $request)
    {
        $companyId = Auth::user()->id;
        $id = $request->input('attributeId');
        $name = $request->input('name');
        $code = $request->input('code');
        $data_type = $request->input('data_type');
        $length = $request->input('length');
        if ($data_type === 'VARCHAR') {
            $len = $length;
        } else {
            $len = '10';
        }
        if ($id != "") {
            $result = Attribute::where('id', '=', $id)->update([
                'name' => $name,
                'data_type' => $data_type,
                'length' => $len,
                'alias' => $name
            ]);
            if ($result) {
                return redirect('/attributes/list')->with(['flash_message' => 'Attribute  Updated']);
            } else {
                return redirect('/attributes/list')->with(['flash_message' => 'Failed']);
            }
        } else {
            $attribute = Attribute::where('code', '=', $code)->where('company_id', '=', $companyId)->first();
            if ($attribute) {
                if($attribute->is_deleted){

                    $attribute->is_deleted = 0;
                    $result = $attribute->save();
                    if ($result) {
                        return redirect('/attributes/list')->with(['flash_message' => 'Attribute added']);
                    } else {
                        return redirect('/attributes/list')->with(['flash_message' => 'failed ']);

                    }
                }else {
                    return redirect('/attributes/list')->with(['flash_message' => 'Attribute code against this company already exist']);
                }
            } else {
                $attributeModel = new Attribute();
                $attributeModel->code = $code;
                $attributeModel->company_id = $companyId;
                $attributeModel->name = $name;
                $attributeModel->alias = $name;
                $attributeModel->data_type = $data_type;
                $attributeModel->length = $len;
                $attributeModel->is_deleted = 0;
                $attributeModel->type = 'CUSTOM';
                $result = $attributeModel->save();
                if ($result) {
                    return redirect('/attributes/list')->with(['flash_message' => 'Attribute added']);
                } else {
                    return redirect('/attributes/list')->with(['flash_message' => 'failed ']);

                }
            }
        }
    }

    public function editAttributes($id)
    {

        $datatype = [
            0 => 'INT',
            1 => 'VARCHAR',
            2 => 'DATE'
        ];
        $attribute = Attribute::where('id', '=', $id)->first();
        $attributdata = array(
            'id' => $attribute->id,
            'name' => $attribute->name,
            'code' => $attribute->code,
            'data_type' => $attribute->data_type,
            'length' => $attribute->length,
            'type' => $attribute->type
        );
        return view('attributes.createAttributes')->with(['attributdata' => $attributdata, 'datatype' => $datatype]);
    }
}
