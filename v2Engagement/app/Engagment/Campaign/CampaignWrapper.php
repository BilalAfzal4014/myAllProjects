<?php

namespace App\Engagment\Campaign;

use App\Apps;
use App\Campaign;
use App\CampaignAction;
use App\CampaignApp;
use App\CampaignSegments;
use App\CampaignTracking;
use App\Components\CampaignComponent;
use App\Components\CompanyAttributeData;
use App\Components\RunExternalCommand;
use App\Components\TargetedUsers;
use App\Engagment\Campaign\CampaignHandler;
use App\Helpers\CommonError;
use App\Http\Requests\Request;
use App\Libraries\tv_jwt;
use App\Segment;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use CommonHelper;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\DocBlock\Tags\Throws;

class CampaignWrapper
{
    protected $campaignHandler;
    protected $campaignComponent;
    protected $targetUsers;

    public function getActionList($postedData, $user)
    {

        return $this->campaignHandler->getActionList($postedData, $user);

    }

    public function __construct(CampaignHandler $campaign, CampaignComponent $campaignComponent, TargetedUsers $targetUsers)
    {
        $this->campaignHandler = $campaign;
        $this->campaignComponent = $campaignComponent;
        $this->targetUsers = $targetUsers;
    }

    public function getCompanyTemplate($companyId)
    {
        return $this->campaignHandler->getCompanyTemplate($companyId);
    }

    public function getCampaignConversionData($companyId)
    {
        return $this->campaignHandler->getCampaignConversionData($companyId);
    }

    public function getCampaignTemplate($companyId)
    {
        return $this->campaignHandler->getCampaignTemplate($companyId);
    }

    public function getCampaignActionData($companyId)
    {
        return $this->campaignHandler->getCampaignActionData($companyId);
    }

    public function getInAppData($companyId)
    {
        return $this->campaignHandler->getInAppData($companyId);
    }

    public function submitStep1($requestBody)
    {
        return $this->campaignHandler->submitStep1($requestBody);
    }

    public function submitStep2($requestBody)
    {
        return $this->campaignHandler->submitStep2($requestBody);
    }

    public function submitStep3($requestBody)
    {
        return $this->campaignHandler->submitStep3($requestBody);
    }

    public function submitStep4($requestBody)
    {
        return $this->campaignHandler->submitStep4($requestBody);
    }

    public function submitStep5($requestBody)
    {
        return $this->campaignHandler->submitStep5($requestBody);
    }

    public function campaignListing($request, $companyId)
    {
        list($totalData, $totalFiltered, $campaignListing) = $this->campaignHandler->campaignListing($request, $companyId);
        $commonHelper = new \App\Helpers\CommonHelper();
        foreach ($campaignListing as $key => $row) {



            $campaignListing[$key]->enCount = substr_count($row->en, 'href');
            $campaignListing[$key]->arCount = substr_count($row->ar, 'href');
            $segment_rowIds = CompanyAttributeData::campaignSegments(Auth::user()->id, $row->id, true);
            $segment_rowIds = array_unique($segment_rowIds);
            $campaignListing[$key]->targetUser = count($segment_rowIds);
            $totalRecord = $row->totalSuccess+$row->totalFailed;

        }
        return [$totalData, $totalFiltered, $campaignListing];
    }

    public function getCampaign($campaignId)
    {
        $steps = [];
        $step = $this->campaignHandler->getSteps($campaignId);
        switch ($step) {
            case 1:
                $steps[] = $this->campaignHandler->getStep1($campaignId);
                break;
            case 2:
                $steps[] = $this->campaignHandler->getStep1($campaignId);
                $steps[] = $this->campaignHandler->getStep2($campaignId);
                break;
            case 3:
                $steps[] = $this->campaignHandler->getStep1($campaignId);
                $steps[] = $this->campaignHandler->getStep2($campaignId);
                $steps[] = $this->campaignHandler->getStep3($campaignId);
                break;
            case 4:
                $steps[] = $this->campaignHandler->getStep1($campaignId);
                $steps[] = $this->campaignHandler->getStep2($campaignId);
                $steps[] = $this->campaignHandler->getStep3($campaignId);
                $steps[] = $this->campaignHandler->getStep4($campaignId);
                break;
            case 5:
                $steps[] = $this->campaignHandler->getStep1($campaignId);
                $steps[] = $this->campaignHandler->getStep2($campaignId);
                $steps[] = $this->campaignHandler->getStep3($campaignId);
                $steps[] = $this->campaignHandler->getStep4($campaignId);
                $steps[] = $this->campaignHandler->getStep5($campaignId);
        }
        return $steps;
    }

    public function launchCampaign($requestBody)
    {
        return $this->campaignHandler->launchCampaign($requestBody['obj']['campaignId']);
    }

    public function dcrypt($encryptUrl)
    {
        return list($campaignId, $url) = $this->campaignHandler->dcrypt($encryptUrl, 'req');
    }

    public function getUserDevice($userAgent)
    {

        $device = 'desktop';
        if (strpos($userAgent, 'iPad') || strpos($userAgent, 'iPhone')) {
            $device = 'iphone';
        } elseif (strpos($userAgent, 'Android')) {
            $device = 'android';

        }
        return $device;
    }

    public function submitLinkTracking($rec_type, $rec_id, $actual_url, $ip_address, $user_agent, $device, $rowId = null)
    {
        $this->campaignHandler->submitLinkTracking($rec_type, $rec_id, $actual_url, $ip_address, $user_agent, $device, $rowId);
    }

    public function getUserIdByEmail($companyKey)
    {
        return $this->campaignHandler->getUserIdByEmail($companyKey);
    }

    public function campaignFilters($companyId)
    {
        return $this->campaignHandler->campaignFilters($companyId);
    }

    public function updateView($updateRecord)
    {
        $updateRecord->viewed = $updateRecord->viewed + 1;
        $updateRecord->viewed_at = new \DateTime('now');
        $updateRecord->save();
    }

    public function updateClick($updateRecord,$request,$headerData)
    {

        $dataToSave['rec_type'] =  'Email';
        $dataToSave['rec_id'] =  $updateRecord->campaign_id;
        $dataToSave['row_id'] =  $updateRecord->row_id;
        $dataToSave['actual_url'] =  $request->action_url;
        $dataToSave['created_date'] =  Carbon::now();
        $dataToSave['user_agent'] =  $request->header('User-Agent');
        $dataToSave['device'] =  $headerData['device_type'];
        $dataToSave['viewed'] =  1;
        DB::table('link_tracking')->insert([$dataToSave]);
    }
    public function getUserFromKey(tv_jwt $jwt)
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization']))
            $token = $headers['Authorization'];
        elseif (isset($headers['authorization']))
            $token = $headers['authorization'];

        $jwtApiKey = config('common.JWT.apiKey.user');
        $data = $jwt->engagiveGetToken($token, $jwtApiKey);
        if (!$data) {

            throw new \RuntimeException("Token Expired");
        }
        if (!$data->company_key) {

            throw new \RuntimeException("User Not Found");
        }


        $user = User::where("company_key", $data->company_key)->first();

        if (!$user) {
            throw new \RuntimeException('invalid token decoded');
        }

        return array("user" => $user, "user_token" => $data->user_token);
    }

    public function validateCampaignTracking($postedData, $user, $userAgent, $campaignActionId)
    {

        $campaignObj = Campaign::find($campaignActionId->campaign_id);
        $duration = 'days';
        switch ($campaignActionId->period) {

            case 'minute':
                $duration = 'minute';
                break;
            case 'hour':
                $duration = 'hour';
                break;
        }
        $validatyDateTime = new \DateTime(date('Y-m-d H:i', strtotime($campaignActionId->sent_at . ' + ' . $campaignActionId->validity . ' ' . $duration)));
        if ($validatyDateTime <= new \DateTime('now')) {

            throw new \RuntimeException(CommonError::CAMPAIGN_VALIDITY_EXPIRED, CommonError::STATUS_CODE_UNPROCESSABLE_ENTITY);
        }
        $campaignApp = CampaignApp::where("campaign_id", $campaignActionId->cam_action_id)->count();
        if ($campaignApp) {

            $this->validateAppId($postedData, $campaignObj, $userAgent, $user);
        }

        return $campaignObj;
    }


    public function validateRequest($postedData, $jwt, $type, $request)
    {
        /*decoding jwt*/
        $data = \App\Helpers\CommonHelper::getUserFromKey($jwt, $request);
        $user = $data['user'];


        /*getting and validating user header*/
        $headerData = \App\Helpers\CommonHelper::validateHeader($request, $user);

        if ($type == "conversion") {

            $validator = Validator::make($postedData, [
                'track_key' => 'required',
                'user_id' => 'required',
                'code' => 'required',
            ]);


        } elseif ($type == "trigger") {

            $validator = Validator::make($postedData, [
                'user_id' => 'required',
                'code' => 'required',
            ]);

        } elseif ($type == "action") {

            $validator = Validator::make($postedData, [
                'user_id' => 'required',
                'data_type' => 'required',
            ]);
        } elseif ($type == "api") {

            $validator = Validator::make($postedData, [
                'user_id' => 'required',
                'campaign_code' => 'required',
            ]);
        }

        if (!empty($validator->errors()->all())) {

            throw new \RuntimeException(implode(',', $validator->errors()->all()), 411);
        }

        $userFromCache = \App\Helpers\CommonHelper::verifyingUserToken($user->id, $headerData['app_name'], $postedData['user_id'], $data['user_token']);

        $postedData['row_id'] = $userFromCache['row_id'];
        $postedData['app_name'] = $headerData['app_name'];
        $postedData['build'] = $headerData['build'];
        $postedData['version'] = $headerData['version'];
        $postedData['device_type'] = $headerData['device_type'];
        $postedData['app_id'] = $headerData['app_id'];

        return array("user" => $user, "posted_data" => $postedData);
    }


    public function validateAppId($postedData, $campaignObj, $userAgent, $user)
    {


        $appObject = Apps::where("app_id", $postedData['app_id'])->where("name", $postedData['app_name'])->where("company_id", $user->id)->where("platform", $userAgent)->first();
        if (!$appObject) {

            throw new \RuntimeException('No App Found');
        }

        $campaignApp = CampaignApp::where("campaign_id", $campaignObj->id)->where("app_id", $appObject->id)->get();
        if (!$campaignApp) {

            throw new \RuntimeException(CommonError::APP_NOT_REGISTER, CommonError::STATUS_CODE_LENGTH_REQUIRED);
        }

        return true;
    }

    public function validateCampaignSegment($row_id, $campaignObj, $companyId)
    {

        $userSegemnts = CompanyAttributeData::campaignSegments($companyId, $campaignObj->id, true);
        if (empty($userSegemnts)) {
            return false;
        }

        if (in_array($row_id, $userSegemnts)) {
            return true;
        }

        return false;
    }

    public function saveRequest($postedData)
    {
        unset($postedData['company_key']);
        unset($postedData['code']);
        unset($postedData['value']);
//        $postedData['device_type'] = strtoupper($postedData['device_type']);
        DB::table('user_campaign')->insert([$postedData]);

    }

    public function passToJob($item, $postedData)
    {

        $response = $this->campaignComponent->dispatch($item->id, 'campaign', $postedData['row_id']);
        $response = $this->targetUsers->processCampaign($response);

        return $response;


    }

    public function saveToUserCampaign($user, $campaignObj, $postedData, $lookupId)
    {

        if (isset($postedData['extra_params'])) {

            $postedData['event_value'] = json_encode($postedData['extra_params']);
            unset($postedData['extra_params']);
        }
        $postedData['event_id'] = $lookupId;
        $postedData['created_at'] = new \DateTime();
        $postedData['company_id'] = $user->id;
        $postedData['campaign_id'] = $campaignObj->id;
        $postedData['campaign_code'] = $campaignObj->code;
        $postedData['rec_type'] = 'api_trigger';
        $this->saveRequest($postedData);

    }

    public function getAttributes($companyId)
    {
        $attrData = (object)[];
        $attrData->attributes = $this->campaignHandler->getAttributes($companyId);
        $attrData->attributesData = $this->campaignHandler->getAttributeData($companyId);
        return $attrData;
    }

    public function getCompanySegmentsWithSearch($companyId, $searchStr)
    {
        return $this->campaignHandler->getCompanySegmentsWithSearch($companyId, $searchStr);
    }

    public function getTestUsersData($companyId, $searchStr, $campaignType, $deviceType)
    {
        return $this->campaignHandler->getTestUsersData($companyId, $searchStr, $campaignType, $deviceType);
    }

    public function getCampaignApps($companyId)
    {
        return $this->campaignHandler->getCampaignApps($companyId);
    }

    public function getMobilePlatform($dateRange, $campaignId)
    {
        $platform = $this->campaignHandler->getPlatform($campaignId);
        $mobilePlatform = (object)[];
        switch (strtolower($platform)) {
            case 'android':
                $mobilePlatform->android = $this->campaignHandler->getClicksAndViews('android', $dateRange, $campaignId);
                $mobilePlatform->ios = (object)[];
                $mobilePlatform->ios->views = 0;
                $mobilePlatform->ios->clicks = 0;
                break;
            case 'ios':
                $mobilePlatform->ios = $this->campaignHandler->getClicksAndViews('ios', $dateRange, $campaignId);
                $mobilePlatform->android = (object)[];
                $mobilePlatform->android->views = 0;
                $mobilePlatform->android->clicks = 0;
                break;
            case 'universal':
                $mobilePlatform->android = $this->campaignHandler->getClicksAndViews('android', $dateRange, $campaignId);
                $mobilePlatform->ios = $this->campaignHandler->getClicksAndViews('ios', $dateRange, $campaignId);
                break;
            default:
                $mobilePlatform->ios = (object)[];
                $mobilePlatform->ios->views = 0;
                $mobilePlatform->ios->clicks = 0;
                $mobilePlatform->android = (object)[];
                $mobilePlatform->android->views = 0;
                $mobilePlatform->android->clicks = 0;
        }
        $mobilePlatform->platform = $platform;
        return $mobilePlatform;
    }

    public function getCharts($dateRange, $campaignId, $platform)
    {
        $chart = (object)[];
        $chart->interval = $this->campaignHandler->getIntervalForChart($dateRange);
        $chart->view = $this->campaignHandler->getChartsForViewsAndroidAndIos($campaignId, $chart->interval, $platform);
        $chart->clicks = $this->campaignHandler->getChartsForClicksAndroidAndIos($campaignId, $chart->interval, $platform);
        $chart->clickRate = $this->campaignHandler->getChartsForClickThroughRateAndroidAndIos($chart->clicks, $chart->view);
        return $chart;
    }

    public function getCampaignDetails($campaignId)
    {
        return $this->campaignHandler->getCampaignDetails($campaignId);
    }

    public function checkAndGetCampaignTemplate($campaignId, $column)
    {
        return $this->campaignHandler->checkAndGetCampaignTemplate($campaignId, $column);
    }

    public function submitCappingSettings($cappingArr, $companyId)
    {
        $this->campaignHandler->submitCappingSettings($cappingArr, $companyId);
    }

    public function getCappingSettings($companyId)
    {
        return $this->campaignHandler->getCappingSettings($companyId);
    }

    public function getUsersOfSegmentsAgainstCampaign($campaignId)
    {
        $content = "";
        $records = $this->campaignHandler->getUsersOfSegmentsAgainstCampaign($campaignId);
        foreach ($records as $rec) {
            $content .= implode(", ", $rec) . PHP_EOL;
        }
        return $content;
    }
}
