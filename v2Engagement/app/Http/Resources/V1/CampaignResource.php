<?php

namespace App\Http\Resources\V1;

use App\AttributeData;
use App\Campaign;
use App\CampaignAction;
use App\CampaignTracking;
use App\Components\RunExternalCommand;
use App\Engagment\Campaign\CampaignWrapper;
use App\Helpers\CampaignDeliveryControlHelper;
use App\Helpers\CommonError;
use App\Helpers\CommonHelper;
use App\Http\Requests\Request;
use App\Libraries\tv_jwt;
use App\Libraries\VerifyEmail;
use App\Lookup;
use App\Messaging\Message;
use App\Notification;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CampaignResource
{

    public function analyticalTrackingForPush(tv_jwt $jwt, CampaignWrapper $campaignClass, $request)
    {
        $user = $campaignClass->getUserFromKey($jwt);
        $headerData = \App\Helpers\CommonHelper::validateHeader($request, $user['user']);
        $validator = Validator::make($request->all(), [
            'track_key' => 'required',
        ]);


        if (!empty($validator->errors()->all())) {

            throw new \RuntimeException(implode(',', $validator->errors()->all()), 411);
        }
        $trackKey = $request->track_key;
        $mode = $request->mode;

        $updateRecord = CampaignTracking::where('track_key', $trackKey)
            ->lockForUpdate()
            ->first();
        if (!$updateRecord) {

            throw new \RuntimeException(CommonError::INVALID_TRACK_KEY, CommonError::STATUS_CODE_NOT_FOUND);
        }
        if(!$mode || $mode == 'viewed') {

            $campaignClass->updateView($updateRecord);
        }elseif($mode == 'both'){

            $campaignClass->updateView($updateRecord);
            $campaignClass->updateClick($updateRecord,$request,$headerData);
        }else{
            $campaignClass->updateClick($updateRecord,$request,$headerData);
        }
        return true;
    }


    public function storeCampaign(tv_jwt $jwt, $data, CampaignWrapper $campaignClass,$request)
    {


        $data = $campaignClass->validateRequest($data, $jwt, "conversion",$request);
        $postedData = $data['posted_data'];
        $user = $data['user'];
        $campaignActionId = CommonHelper::validateLookUp($postedData,CommonHelper::$_CONVERSTION_TYPES,CommonHelper::$_CAMPAIGN_CONVERSION);
        $campaignObj = $campaignClass->validateCampaignTracking($postedData, $user, $postedData['device_type'],$campaignActionId);
        if(!$campaignClass->validateCampaignSegment($postedData['row_id'], $campaignObj, $user->id)){

            throw new \RuntimeException(CommonError::USER_ID_NOT_EXIST_SEGMENT,CommonError::STATUS_CODE_LENGTH_REQUIRED);
        }
        $postedData['created_at'] = new \DateTime();
        $postedData['company_id'] = $user->id;
        $postedData['rec_type'] = 'conversion';
        $postedData['event_id'] = $campaignActionId->action_id;
        $postedData['event_value'] = $postedData['value'];
        $postedData['campaign_code'] = $campaignObj->code;
        $postedData['campaign_id'] = $campaignObj->id;
        $campaignClass->saveRequest($postedData);

        return "Successfully recorded";
    }


    public function storeTrigger(tv_jwt $jwt, $postedData, CampaignWrapper $campaignClass,$request)
    {

        /*TODO: function need  to be optimize*/
        $data = $campaignClass->validateRequest($postedData, $jwt, "trigger",$request);
        $returnResposne = "Action Performed";
        $postedData = $data['posted_data'];

        $campaignActionId = CommonHelper::validateLookUp($postedData,CommonHelper::$_ACTION_TRIGGER,CommonHelper::$_CAMPAIGN_TRIGGER,$postedData['row_id']);
        $eventValue = $postedData['value'];
        $user = $data['user'];

        foreach ($campaignActionId as $item) {

            try {

                $eventId = $item->action_id;
                /**
                 * @var  $campaignObj Campaign
                 */
                $campaignObj = Campaign::find($item->campaign_id);

                if (!$campaignClass->validateCampaignSegment($postedData['row_id'], $campaignObj, $user->id)) {
                    continue;
                }


                if ($campaignObj->isDeliveryControlEnabled() === true) {
                    if (CampaignDeliveryControlHelper::canSendAgain($campaignObj, $postedData['row_id'])) {
                        $campaignClass->passToJob($campaignObj, $postedData);
                    }else{
                        continue;
                    }
                } else {
                    $campaignClass->passToJob($campaignObj, $postedData);
                }
            }catch (\Exception $exception){

                $returnResposne .= $exception->getMessage().' ';
                continue;
            }

            $postedData['created_at'] = new \DateTime();
            $postedData['event_id'] = $eventId;
            $postedData['event_value'] = $eventValue;
            $postedData['company_id'] = $user->id;
            $postedData['campaign_id'] = $campaignObj->id;
            $postedData['campaign_code'] = $campaignObj->code;
            $postedData['rec_type'] = 'action_trigger';
            $campaignClass->saveRequest($postedData);
        }

        return $returnResposne;
    }


    public function actionTriggerCampaign(tv_jwt $jwt, $postedData, CampaignWrapper $campaignClass,$request)
    {



            $userAgent = CommonHelper::getDeviceUserAgent();
            $lookUpObject = Lookup::where("code", CommonHelper::$_API_TRIGGER)->first();
            $data = $campaignClass->validateRequest($postedData, $jwt, "api", $request);
            $user = $data['user'];
            $postedData = $data['posted_data'];
            $rowId = $postedData['row_id'];
        try {
            /**
             * @var  $campaignObj Campaign
             */
            $campaignObj = Campaign::where("campaign.code", $postedData['campaign_code'])
                ->where("campaign.delivery_type", CommonHelper::$_CAMPAIGN_API)
                ->where("campaign.status", CommonHelper::$_ACTIVE)
                ->where("campaign.start_time", '<=', Carbon::now('UTC'))
                ->where("campaign.end_time", '>=', Carbon::now('UTC'))
                ->whereNotIn('campaign.id', function ($q) use ($rowId) {
                    $q->select('ctc.campaign_id')->from('campaign_tracking as ctc')
                        ->join('campaign as c1','ctc.campaign_id','=','c1.id')
                        ->where('c1.enable_delivery_control',0)
                        ->where('ctc.row_id', $rowId)
                        ->where('ctc.status', CommonHelper::$_STATUS_COMPLETE);
                })
                ->first();
            if (!$campaignObj) {

                throw new \RuntimeException('Invalid Campaign code provided');
            }

            if (!$campaignClass->validateCampaignSegment($postedData['row_id'], $campaignObj, $user->id)) {

                throw new \RuntimeException(CommonError::USER_ID_NOT_EXIST_SEGMENT, CommonError::STATUS_CODE_LENGTH_REQUIRED);
            }
            $user = $data['user'];
            $postedData['device_type'] = $userAgent;


            if ($campaignObj->isDeliveryControlEnabled() === true) {

                if (!CampaignDeliveryControlHelper::canSendAgain($campaignObj, $postedData['row_id'])) {

                    throw new \RuntimeException(CommonError::DELIVERY_CONTROL_ERROR, CommonError::STATUS_CODE_LENGTH_REQUIRED);
                }
            }


            $campaignClass->passToJob($campaignObj, $postedData);
            $campaignClass->saveToUserCampaign($user, $campaignObj, $postedData, $lookUpObject->id);
        }catch (\Exception $exception){

            return $exception->getMessage();
        }
        return "Successfully recorded";
    }

    public function getActionList(CampaignWrapper $campaignClass, tv_jwt $tv_jwt,$request)
    {

        $postedData = $request->all();;

        $data = $campaignClass->validateRequest($postedData, $tv_jwt, "action",$request);
        $postedData = $data['posted_data'];
        $user = $data['user'];

        $actionList = $campaignClass->getActionList($postedData, $user);
        return $actionList;
    }


}