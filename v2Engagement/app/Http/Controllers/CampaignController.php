<?php

namespace App\Http\Controllers;

use App\AttributeData;
use App\CampaignTracking;
use App\CampaignTypes;
use App\Components\CompanyAttributeData;
use App\UserAttribute;
use App\UserCampaign;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Engagment\Campaign\CampaignWrapper;
use App\Gallery;
use App\Http\Requests;
use App\Campaign;
use DB;
use CommonHelper;
use Illuminate\Support\Facades\Config;
use Illuminate\View\View;
use App\CampaignTrackingLogs;

class CampaignController extends Controller
{
    protected $campaignClass;

    public function __construct(CampaignWrapper $campaign)
    {
        $this->campaignClass = $campaign;
    }

    public function campaignView()
    {
        $companyId = Auth::user()->id;
        return view('campaign.campaignListing', ['companyId' => $companyId]);
    }

    public function campaignFilters($companyId)
    {
        return response()->json([
            'status' => true,
            'data' => $this->campaignClass->campaignFilters($companyId),
            'message' => 'campaign Filters'
        ]);
    }

    public function createCampaign(Request $request)
    {
        $CampaignType = '';
        if ($request->has('select')) {
            if ($request->input('select') == 'email')
                $CampaignType = 1;
            else if ($request->input('select') == 'push')
                $CampaignType = 2;
            else if ($request->input('select') == 'inApp')
                $CampaignType = 3;
        }

        $companyId = Auth::user()->id;
        $attributeData = $this->campaignClass->getAttributes($companyId);
        return view('campaign.campaignCrud', ['companyId' => $companyId, 'CampaignType' => $CampaignType, 'attributeData' => $attributeData]);
    }

    public function campaignAction($action, $id)
    {
        $companyId = Auth::user()->id;
        $attributeData = $this->campaignClass->getAttributes($companyId);

        if (($action == 'edit' || $action == 'view')) {
            $campaign = Campaign::where('id', $id)
                ->where('company_id', $companyId)
                ->first();
            if (isset($campaign)) {
                if ($action == 'edit' && $campaign->status != 'draft'/*Campaign::STATUS_ACTIVE*/) {
                    return view('campaign.error');
                }
                return view('campaign.campaignCrud', ['companyId' => $companyId, 'attributeData' => $attributeData, 'action' => $action, 'id' => $id]);
            }
            return view('campaign.error');
        }
        return view('campaign.error');
    }

    public function campaignStaticsView($campaignId)
    {
        $companyId = Auth::user()->id;
        $campaign = Campaign::where('id', $campaignId)
            ->where('company_id', $companyId)
            ->where('status', '<>', 'draft'/*Campaign::STATUS_DRAFT*/)
            ->first();

        if (isset($campaign)) {
            return view('campaign.campaignStats', ['campaignId' => $campaignId]);
        }
        return view('campaign.error');
    }

    /**********************************************Campaign Tracking  method*****************************************************/
    public function campaignTrackingListing(Request $request, $id)
    {
        $companyId = Auth::user()->id;
        $campaignResult = Campaign::where('id', $id)->where('company_id', '=', $companyId)->first();
        if (!$campaignResult) {
            abort(403, 'Unauthorized');
        }

        $columns = array(
            0 => 'id',
            1 => 'useremail',
            2 => 'sent_at',
            3 => 'completestatus',
            4 => 'viewed_at',
            5 => 'message',
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->filter;
        $filterType = $request->filterType;
        $startDate = $request->start_date;
        $endDate = $request->end_Date;
//        echo 'startdate'.$startDate;
//        echo 'enddate'.$endDate;
//        die;
        $myQuery = CampaignTracking::leftjoin('campaign_tracking_logs', 'campaign_tracking_logs.campaign_tracking_id', '=', 'campaign_tracking.id')
            ->leftjoin('user_campaign', function ($join) {
                $join->on('user_campaign.track_key', '=', 'campaign_tracking.track_key');
                $join->where('user_campaign.rec_type', '=', "conversion");
            })->leftjoin('user_attribute', 'user_attribute.row_id', '=', 'campaign_tracking.row_id')
            ->leftjoin('lookup', 'lookup.id', '=', 'user_campaign.event_id')
            ->whereDate('campaign_tracking.created_at', '>=', $startDate)
            ->whereDate('campaign_tracking.created_at', '<=', $endDate)
            ->where('campaign_tracking.campaign_id', $id);
        // echo  $myQuery->toSql();
        $totalCountQuery = clone $myQuery;

        if (!empty($search)) {
            $myQuery->where(function ($query) use ($search) {
                $query->orWhere('campaign_tracking.track_key', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_tracking.row_id', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_tracking.sent_at', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_tracking.status', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_tracking.viewed_at', 'LIKE', "%{$search}%");
                $query->orWhere('campaign_tracking_logs.message', 'LIKE', "%{$search}%");
                $query->orWhere('user_attribute.email', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.event_value', 'LIKE', "%{$search}%");
                $query->orWhere('lookup.code', 'LIKE', "%{$search}%");
            });
        }
        switch ($filterType) {
            case 'app_name':
                if ($filter != '') {
                    $myQuery->where('campaign_tracking.status', $filter);
                }

                break;
        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get([
                'campaign_tracking.id as id',
                'campaign_tracking.campaign_id as campaign_id',
                'campaign_tracking.track_key as Trackkey',
                'campaign_tracking.row_id as rowId',
                'campaign_tracking.email as email',
                'campaign_tracking.firebase_key as firebase_key',
                'campaign_tracking.device_key as device_key',
                'campaign_tracking.job as jobstatus',
                'campaign_tracking.sent_at as sent_at',
                'campaign_tracking.status as completestatus',
                'campaign_tracking.viewed_at as viewed_at',
                'campaign_tracking_logs.message as message',
                'user_campaign.id as uid',
                'user_campaign.event_value as event_value',
                'user_campaign.build as build',
                'user_campaign.version as version',
                'user_campaign.event_id as event_id',
                'user_campaign.device_type as device_type',
                'user_campaign.created_at as created_at',
                'campaign_tracking.created_at as datetime',
                'lookup.code as event_name',
                'user_attribute.email as UserEmail']);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign Tracking List'
        ]);
    }

    /**********************************************Campaign Action Trigger  method*****************************************************/
    public function campaignActionTrigger(Request $request, $id)
    {
        $companyId = Auth::user()->id;
        $columns = array(
            0 => 'id',
            1 => 'email',
            2 => 'app_name',
            3 => 'event_name',
            4 => 'event_value',
            5 => 'device_type',
            6 => 'build',
            7 => 'version',
            8 => 'created_at'
        );
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $filter = $request->actionFilter;
        $filterType = $request->actionfilterType;
        $actionStartDate = $request->actionStartDate;
        $actionENdDate = $request->actionENdDate;
        $myQuery = UserCampaign::join('user_attribute', 'user_attribute.row_id', '=', 'user_campaign.row_id')
            ->join('lookup', 'lookup.id', '=', 'user_campaign.event_id')
            ->whereDate('user_campaign.created_at', '>=', $actionStartDate)
            ->whereDate('user_campaign.created_at', '<=', $actionENdDate)
            ->where('user_campaign.campaign_id', $id)
            ->where('user_campaign.rec_type', '=', 'action_trigger');
        $totalCountQuery = clone $myQuery;
        if (!empty($search)) {
            $myQuery->where(function ($query) use ($search) {
                $query->orWhere('user_campaign.app_name', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.id', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.event_value', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.device_type', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.build', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.version', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.created_at', 'LIKE', "%{$search}%");
                $query->orWhere('user_campaign.event_value', 'LIKE', "%{$search}%");
                $query->orWhere('lookup.code', 'LIKE', "%{$search}%");
                $query->orWhere('user_attribute.email', 'LIKE', "%{$search}%");
            });

//            $myQuery->where('user_campaign.app_name', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_campaign.id', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_campaign.event_value', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_campaign.device_type', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_campaign.build', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_campaign.version', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_campaign.created_at', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('lookup.code', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger')
//
//                ->orWhere('user_attribute.email', 'LIKE', "%{$search}%")
//                ->where('user_campaign.campaign_id', $id)
//                ->where('rec_type', '=', 'action_trigger');
        }
//        switch ($filterType) {
//            case 'app_name':
//                $myQuery->where('campaign_tracking.status', $filter);
//                break;
//        }
        $totalFilterCountQuery = clone $myQuery;
        $galleryListing = $myQuery->orderBy($order, $dir)
            ->offset($start)
            ->limit($limit)
            ->get([
                'user_campaign.id as id',
                'user_campaign.app_name as app_name',
                'user_campaign.row_id as row_id',
                'user_campaign.campaign_code as campaign_code',
                'user_campaign.event_id as event_id',
                'user_campaign.event_value as event_value',
                'user_campaign.device_type as device_type',
                'user_campaign.build as build',
                'user_campaign.version as version',
                'user_campaign.created_at as created_at',
                'lookup.code as event_name as event_name',
                'user_attribute.email as email']);
        $totalData = $totalCountQuery->count();
        $totalFiltered = $totalFilterCountQuery->count();
        return response()->json([
            'status' => true,
            'data' => $galleryListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign Action Trigger Data'
        ]);
    }

    /**********************************************Notification Send method*****************************************************/
    public function resendNotification($campaignId, $id)
    {
        $companyId = Auth::user()->id;
        $companyKey = Auth::user()->company_key;
        $email = Auth::user()->email;
        $campaign = Campaign::find($campaignId);
        if (!empty($campaign->id)) {
            $campaign_type = $campaign->campaign_type;
            $campaignTracking = CampaignTracking::where('campaign_tracking.id', $id)
                ->leftjoin('campaign_tracking_logs', 'campaign_tracking_logs.campaign_tracking_id', '=', 'campaign_tracking.id')->first();
            //   dd($campaignTracking);
            $rowId = $campaignTracking->row_id;
            $payload = unserialize(stripslashes(base64_decode($campaignTracking->payload)));
            $type = CampaignTypes::TYPE_EMAIL;
            if ($campaign_type->isPush()) {
                $type = CampaignTypes::TYPE_PUSH;
                if ($campaign->isPlatformIOS()) {
                    if (!empty($payload['certificate']) && strpos($payload['certificate'], 'development')) {
                        $sandbox = true;
                    }
                }
            }
            if ($campaign_type->isInapp()) {
                $type = CampaignTypes::TYPE_INAPP;
            }

            if (!empty($campaignTracking->firebase_key)) {
                $message = $payload['alert']['data'];
                $attributeData = UserAttribute::where('row_id', '=', $rowId)->where('company_id', '=', $companyId)->get(['device_type']);
                if (count($attributeData) > 0) {
                    $value = $attributeData['0']->device_type;
                    if (in_array(strtolower($value), ['ios', 'iphone'])) {
                        $platform = 'ios';
                    } else {
                        $platform = 'android';
                    }
                } else {
                    $platform = 'android';
                }
                $post = array(
                    'company_key' => $companyKey,
                    'row_id' => [$rowId],
                    "message" => $message,
                    "platform" => $platform,
                    "type" => $type
                );
            } else if (!empty($campaignTracking->device_key)) {
                $message = $payload['message'];
                $platform = 'ios';
                $post = array(
                    'company_key' => $companyKey,
                    'row_id' => [$rowId],
                    "message" => $message,
                    "platform" => $platform,
                    "type" => $type
                );
            } else {
                $message = $payload['message'];
                $post = array(
                    'company_key' => $companyKey,
                    'row_id' => [$rowId],
                    "message" => $message,
                    "type" => $type
                );
            }
            //dd($post);
            if (!empty($sandbox) && ($sandbox === true)) {
                $post['is_test_device'] = true;
            }
            $ch = curl_init(config::get('app.url') . '/api/v1/message/send');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
            $res = curl_exec($ch);
            //echo $res;
            $errors = curl_error($ch);
            $response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            $result = json_decode($res, true);
            //dd($result);
            if ($result['meta']['code'] == '200') {
                if ($result['data']['0']['code'] == '200') {
                    CampaignTracking::where('campaign_tracking.id', $id)->update([
                        'status' => 'completed',
                        'sent' => '1'
                    ]);
                    $logs = CampaignTrackingLogs::where('campaign_tracking_id', $id)->first();
                    $logs->message = $result['data']['0']['message'];
                    $logs->save();
                    $resp = $this->successResponse($result['data']['0']['message'], [], $result);
                } else {
                    $logs = CampaignTrackingLogs::where('campaign_tracking_id', $id)->first();
                    $logs->message = $result['data']['0']['message'];
                    $logs->save();
                    $resp = $this->multipleFailedResponse('multipleFailedResponse', $result['data']);
                }
            } else {
                $resp = $this->failedResponse($result['errors']['0']);
            }
            //dd($response->getBody()->getContents());

        } else {
            $resp = $this->failedResponse('No Campaign Found');
        }
        return $resp;
    }

    /********************************************** Success Response  method*****************************************************/
    public function successResponse($message, $data, $curlResponse)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 200,
            'error' => false,
            'message' => $message,
            'data' => $data,
            'curlResponse' => $curlResponse
        ));
    }

    /********************************************** multiple failed Response  method*****************************************************/
    public function multipleFailedResponse($message, $data)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 402,
            'error' => true,
            'message' => $message,
            'data' => $data
        ));
    }

    /********************************************** failed Response  method*****************************************************/
    public function failedResponse($message)
    {
        return \Response::json(array(
            'Exception' => '',
            'status' => 400,
            'error' => true,
            'message' => $message
        ));
    }

    public function campaignListing(Request $request, $companyId)
    {
        $authCompanyId = Auth::user()->id;
        if ($authCompanyId != $companyId) {
            abort(403, 'Unauthorized');
        }
        list($totalData, $totalFiltered, $campaignListing) = $this->campaignClass->campaignListing($request, $companyId);
        return response()->json([
            'status' => true,
            'data' => $campaignListing,
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            'message' => 'Campaign listing'
        ]);
    }

    public function campaignPreData($companyId, $campaignId = -1)
    {
        $userCompanyId = Auth::user()->id;
        if ($userCompanyId != $companyId) {
            abort(403, 'Unauthorized');
        }
        if ($campaignId != -1) {
            $campaignResponse = Campaign::where('id', '=', $campaignId)->where('company_id', '=', $companyId)->first();
            if (!$campaignResponse) {
                abort(403, 'Unauthorized');
            }
        }
        $template = $this->campaignClass->getCompanyTemplate($companyId);
        $campaignTemplates = $this->campaignClass->getCampaignTemplate($companyId);
        $inAppData = $this->campaignClass->getInAppData($companyId);
        $campaignConversion = $this->campaignClass->getCampaignConversionData($companyId);
        $campaignAction = $this->campaignClass->getCampaignActionData($companyId);
        $campaignApps = $this->campaignClass->getCampaignApps($companyId);

        $readData = [];
        if ($campaignId != -1) {
            $readData = $this->campaignGet($campaignId);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'template' => $template,
                'campaignTemplate' => $campaignTemplates,
                'inAppData' => $inAppData,
                'campaignConversion' => $campaignConversion,
                'campaignAction' => $campaignAction,
                'campaignApps' => $campaignApps,
                'readData' => $readData
            ],
            'message' => 'campaign prefetch data'
        ]);
    }

    public function campaignGet($campaignId)
    {
        $steps = $this->campaignClass->getCampaign($campaignId);

        return [
            'steps' => $steps,
        ];
    }

    public function getCompanySegmentsWithSearch(Request $request)
    {
        $companyId = $request->input('companyId');
        $searchStr = $request->input('searchStr');
        $authUserId = Auth::user()->id;
        if ($companyId != $authUserId) {
            abort(403, 'Unauthorized');
        }
        $obj = (object)[];
        $obj->results = $this->campaignClass->getCompanySegmentsWithSearch($companyId, $searchStr);
        return response()->json($obj);
    }

    public function campaignInsertion(Request $request)
    {
        $requestBody = $request->all();
        switch ($requestBody['obj']['step']) {
            case 1:
                $campaignId = $this->campaignClass->submitStep1($requestBody);
                break;
            case 2:
                $campaignId = $this->campaignClass->submitStep2($requestBody);
                break;
            case 3:
                $campaignId = $this->campaignClass->submitStep3($requestBody);
                break;
            case 4:
                $campaignId = $this->campaignClass->submitStep4($requestBody);
                break;
            case 5:
                $campaignId = $this->campaignClass->submitStep5($requestBody);
                break;
            case 6:
                $campaignId = $this->campaignClass->launchCampaign($requestBody);
        }

        if ($campaignId === false) {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Campaign title already exist'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $campaignId,
            'message' => 'Inserted'
        ]);
    }

    function addhttp($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
        return $url;
    }

    public function getCampaignUrl(Request $request)
    {


        $encryptUrl = $request->get('enc');
        list($campaignId, $type, $url, $rowId) = $this->campaignClass->dcrypt($encryptUrl);

        $this->campaignClass->submitLinkTracking($type, $campaignId, $url, $request->ip(), $request->header('user-agent'), $this->campaignClass->getUserDevice($request->header('user-agent')), $rowId);
        return redirect()->away($url);
    }

    public function suspendCampaign($campaignId)
    {
        $companyId = Auth::user()->id;
        $campaign = Campaign::where('id', $campaignId)->where('company_id', '=', $companyId)->first();
        if (!$campaign) {
            abort(403, 'Unauthorized');
        }

        $campaign->status = 'suspend';
        $campaign->save();

        return response()->json([
            'status' => true,
            'data' => [],
            'message' => 'Campaign suspended'
        ]);
    }

    public function getUserIdByEmail(Request $request)
    {
        $payLoad = $this->campaignClass->getUserIdByEmail($request->input('companyId'));
        if ($payLoad) {
            return response()->json([
                'status' => true,
                'data' => $payLoad,
                'message' => 'Details to test preview'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Email not Found'
            ]);
        }
    }

    public function getCampaignStatics(Request $request)
    {
        $campaignId = $request->input('campaignId');
        $companyId = Auth::user()->id;
        $camapignResult = Campaign::where('id', $campaignId)->where('company_id', '=', $companyId)->first();
        if ($camapignResult) {
            $data = (object)[];
            $data->mobilePlatform = $this->campaignClass->getMobilePlatform($request->input('dateRange'), $request->input('campaignId'));
            $data->chart = $this->campaignClass->getCharts($request->input('dateRange'), $request->input('campaignId'), $data->mobilePlatform->platform);
            $data->campaignDetails = $this->campaignClass->getCampaignDetails($request->input('campaignId'));

            return response()->json([
                'status' => true,
                'data' => $data,
                'message' => 'Campaign Statics'
            ]);
        } else {
            abort(403, 'Unauthorized');
        }
    }

    public function getSendTestUsers(Request $request)
    {
        $companyId = $request->input('companyId');
        $userCompanyId = Auth::user()->id;
        if ($userCompanyId != $companyId) {
            abort(403, 'Unauthorized');
        }
        $searchStr = $request->input('searchStr');
        $campaignType = $request->input('campaignType');
        $deviceType = $request->input('deviceType');

        $obj = (object)[];
        $obj->results = $this->campaignClass->getTestUsersData($companyId, $searchStr, $campaignType, $deviceType);
        return response()->json($obj);
    }

    public function checkAndGetCampaignTemplate(Request $request)
    {
        $content = $this->campaignClass->checkAndGetCampaignTemplate($request->input('campaignId'), $request->input('column'));

        if ($content) {
            return response()->json([
                'status' => true,
                'data' => $content,
                'message' => 'Found'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'data' => [],
                'message' => 'Not Found'
            ]);
        }

    }

    public function getCappingSettingsView()
    {
        return view("campaign.campaignSettings", ["companyId" => Auth::user()->id]);
    }

    public function getCappingSettings($companyId)
    {
        $userCompanyId = Auth::user()->id;
        if ($userCompanyId != $companyId) {
            abort(403, 'Unauthorized');
        }
        return response()->json([
            "status" => "true",
            "data" => $this->campaignClass->getCappingSettings($companyId),
            "message" => "Capping Rules"
        ]);
    }

    public function submitCappingSettings(Request $request, $companyId)
    {
        $userCompanyId = Auth::user()->id;
        if ($userCompanyId != $companyId) {
            abort(403, 'Unauthorized');
        }
        $this->campaignClass->submitCappingSettings(isset($request["rules"]) ? $request["rules"] : [], $companyId);
        return response()->json([
            "status" => "true",
            "data" => [],
            "message" => "Rules saved successfully"
        ]);
    }

    public function campaignGetCacheUsers($campaignId)
    {
        $content = $this->campaignClass->getUsersOfSegmentsAgainstCampaign($campaignId);
        $fileName = $campaignId . '-' . date('Y-m-d H:i:s') . '.csv';

        header('Content-Description: File Transfer');
        header('Content-Type: application/csv');
        header('Content-disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . strlen($content));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Expires: 0');
        header('Pragma: public');
        echo $content;
        exit;
    }

}

//http://localhost/engagement/public/trackLink/RW1haWwvMzYvaHR0cHM6Ly93d3cueWFob28uY29t
