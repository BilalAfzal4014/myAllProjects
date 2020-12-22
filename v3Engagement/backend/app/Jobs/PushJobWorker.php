<?php

namespace App\Jobs;

use App\Campaign;
use App\CampaignQueue;
use App\Cache\CampaignTrackingCache;
use App\CampaignTracking;
use App\CampaignTrackingLog;
use App\Components\AppPlatforms;
use App\Components\AppStatusCodes;
use App\Components\CampaignCappingControl;
use App\Components\CampaignWorkerPayload;
use App\Components\InteractsWithMessages;
use App\Http\Controllers\NotificationController;
use App\Helpers\CommonHelper;
use App\Components\CampaignQueueComponent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Helpers\CampaignValidation;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PushJobWorker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, InteractsWithMessages;

    /**
     * @Document Payload received for Push
     */
    private $payload;

    /**
     * PushJobWorker constructor.
     *
     * @param $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            $data = (isset($this->payload[0])) ? $this->payload[0] : $this->payload;

            $campaign_type = (isset($data['data']['campaign_type'])) ? strtolower($data['data']['campaign_type']) : Campaign::CAMPAIGN_PUSH_CODE;
            $campaign_id = (isset($data['data']['campaign_id'])) ? $data['data']['campaign_id'] : "";

            $disk = \Storage::disk('public');
            $logFile = strtolower($campaign_type)."_logs.log";
            $output = [];

            if ($campaign_type == Campaign::CAMPAIGN_PUSH_CODE || $campaign_type == Campaign::CAMPAIGN_INAPP_CODE) {
                $output[] = "Job started on: ". Carbon::now()->format("Y-m-d h:i:s");
                $output[] = "Campaign id: ". $campaign_id;
                $output[] = "Campaign type: ". $campaign_type;
                $output[] = 'Payload: '. \GuzzleHttp\json_encode($data);
                //$target = new CampaignQueueComponent($data);
                $result = $this->processCampaign($data);
                //dd($result);
                Log::info($campaign_type." job executed with result: ". \GuzzleHttp\json_encode($result));
                $output[] = $campaign_type.' job executed with result: '. \GuzzleHttp\json_encode($result);
                $content = implode("\n", $output)."\n";
                $disk->put($logFile, $content);
            }else{
                throw new \Exception("Failed, Campaign is not ". $campaign_type ." type.");
            }
        }catch (\Exception $exception) {
            //dd($exception);
            Log::error($campaign_type.' job failed: '. $exception->getMessage());
            $output[] = $campaign_type.' job failed: '. $exception->getMessage();
            $content = implode("\n", $output)."\n";
            $disk->put($logFile, $content);
        }
    }

    public function processCampaign($data)
    {
        $response = array();
        try {

            Log::emergency('Worker job processing...');
            //$data = $this->payload;
            $campaign_id = (isset($data['data']['campaign_id'])) ? $data['data']['campaign_id'] : "";
            $user_id = (isset($data['data']['user_id'])) ? $data['data']['user_id'] : "";
            $row_id = (isset($data['data']['row_id'])) ? $data['data']['row_id'] : "";
            $tokens_data = (isset($data['data']['tokens_data'])) ? $data['data']['tokens_data'] : "";
            $language =  (isset($data['data']['language'])) ? $data['data']['language'] : "";

            // apply campaign validation checks
            $validation = CampaignValidation::validation($campaign_id);

            $_tracking_keys=[];$device_tokens = [];$server_key='';
            $campaign_tracking_id=[];
            $device_type=strtolower(AppPlatforms::PLATFORM_ANDROID);
            foreach($tokens_data as $key=>$value){

                // device_type
                $device_type = strtolower($value['device_type']);
                if( $device_type == strtolower(AppPlatforms::PLATFORM_ANDROID)) {
                    $server_key = $value['server_key'];
                    //$server_key = "AAAAGOFAepI:APA91bHCNaJ6KAOFvivnQcCcbLfouFud56KSoLvuuGjWSFlvHu6-3tFSqd5F8ZMKlfj6UXpi6yDLGXo3QdKLdnk56Z3yY2lFn2uzIkk5bITzhy51hOKVXHSJ3VCd2oAj-T6bxVJxfP3e";
                }
                elseif( $device_type == strtolower(AppPlatforms::PLATFORM_IOS)) {
                    $server_key = $value['server_key'];
                    //$server_key = "AAAAHtc5HBo:APA91bH3hvkYhGYPzJ9vdFETqXKwBFJShdMExDbMbp4BYpNGAhZEq-r7H0QjYkTVGMPCqA0qkpJVxpkBiCOpFiGNVgRiHdmvTEIqbO-qWx7d36kfvPDzvHs0CDnv-1suENbVVSz7dl1oYwavUtude4g-8A-hyWPtHQ";
                }
                elseif ($device_type == strtolower(AppPlatforms::PLATFORM_WEB)) {
                    $server_key = $value['server_key'];
                    //$ios_server_key = "AAAAHtc5HBo:APA91bH3hvkYhGYPzJ9vdFETqXKwBFJShdMExDbMbp4BYpNGAhZEq-r7H0QjYkTVGMPCqA0qkpJVxpkBiCOpFiGNVgRiHdmvTEIqbO-qWx7d36kfvPDzvHs0CDnv-1suENbVVSz7dl1oYwavUtude4g-8A-hyWPtHQ";
                }
                else{
                    $server_key = $value['server_key'];
                }
                //dump($device_type,$server_key);

                foreach($value['tracking_key'] as $key=>$tracking){
                    $trackingKey = $tracking;
                    $_tracking_keys[] = $tracking;

                    // Checking Campaign Tracking id is valid
                    $campaignTracking = CampaignTracking::where('track_key', '=', $trackingKey)
                        ->where('campaign_id', '=', $campaign_id)
                        ->first();
                    if (!$campaignTracking) {
                        $tracking_error = "Tracking key is in-valid.";
                        Log::error($tracking_error);
                        //throw new \Exception($tracking_error);
                    }

                    $campaign_tracking_id[] = (isset($campaignTracking->id)) ? $campaignTracking->id : "";
                    Log::info('Campaign tracking found.');

                    // update the Campaign Tracking status from added to executing
                    $this->updateTrackingStatus($trackingKey, Campaign::CAMPAIGN_TRACKING_EXECUTING_STATUS);
                }

                foreach($value['device_token'] as $key=>$_tokens){
                    $device_tokens[] = $_tokens;
                }
                //dump($device_tokens);

            }
            //dd($android_server_key ." : ". $ios_server_key);

            $data['data']['track_keys'] = $_tracking_keys;

            $_params = [
                "title" => (isset($data['notification']['title'])) ? $data['notification']['title'] : "",
                "body" => (isset($data['notification']['body'])) ? $data['notification']['body'] : "",
                "link" => (isset($data['data']['action_value'])) ? $data['data']['action_value'] : "",
                "backgroundColor" => (isset($data['data']['backgroundColor'])) ? $data['data']['backgroundColor'] : "#FFFFFF",
                "device_type" => (isset($tokens_data['device_type'])) ? $tokens_data['device_type'] : $device_type, // remove later on
                "device_token" => '', // remove later on
                "campaign_code" => (isset($data['data']['campaign_code'])) ? $data['data']['campaign_code'] : "",
                "user_id" => (isset($data['data']['user_id'])) ? $data['data']['user_id'] : "",
                "track_keys" => (isset($data['data']['track_keys'])) ? $data['data']['track_keys'] : [],
                "action_url" => (isset($data['data']['action_url'])) ? $data['data']['action_url'] : "",
                "is_hermis_platform" => $data['data']['is_hermis_platform'],
                "is_silent" => $data['data']['is_silent'],
                "campaign_type" => $data['data']['campaign_type'],
                "message_position" => $data['data']['message_position'],
                "message_type" => (isset($data['data']['message_type'])) ? $data['data']['message_type'] : "",
                "priority" => $data['data']['priority'],
                "icon" => ($data['data']['icon'] != "") ? $data['data']['icon'] : "",
                "auto_close" => $data['data']['auto_close'],
                "action_type" => (isset($data['data']['action_type'])) ? $data['data']['action_type'] : "deep link",
                "action_value" => (isset($data['data']['action_value'])) ? $data['data']['action_value'] : "",
                "campaign_dispatch_date" => (isset($data['data']['campaign_dispatch_date'])) ? $data['data']['campaign_dispatch_date'] : "",
                "view_link" => $data['data']['view_link']
            ];
            //dump($_params);

            $content_available = 0;
            if((bool)$_params['is_silent'] == true){
                $content_available = 1;
            }

            if( $_params['campaign_type'] == Campaign::CAMPAIGN_PUSH_CODE ){ // && strtolower($_params['device_type']) == AppPlatforms::PLATFORM_IOS

                // generating notification payload for push campaigns
                $notification = CampaignWorkerPayload::generatePayloadNotification($_params);
            }
            else{
                $notification = null;
            }
            Log::info('Worker payload notify: ' . \GuzzleHttp\json_encode($notification));

            /*if( ($_params['campaign_type'] == Campaign::CAMPAIGN_INAPP_CODE ) &&
                (
                    (isset($tokens_data[0]['device_type']) && strtolower($tokens_data[0]['device_type']) == strtolower(AppPlatforms::PLATFORM_ANDROID))
                    ||
                    ( isset($tokens_data[1]['device_type']) && strtolower($tokens_data[1]['device_type']) == strtolower(AppPlatforms::PLATFORM_ANDROID))
                )
            ){
                $notification = null;
            }
            else{
                $_params['link'] = "";
                if(strtolower($_params['device_type']) == AppPlatforms::PLATFORM_ANDROID){
                    if(strtolower($_params['action_type']) == 'deep link'){
                        $_params['link'] = $_params['action_value'];
                    }
                }
                $notification = CampaignWorkerPayload::generatePayloadNotification($_params);
                Log::info('Worker payload notify: ' . \GuzzleHttp\json_encode($notification));
            }*/
            //dump($notification);
            //dump($content_available);

            $payload_data = CampaignWorkerPayload::generatePayloadData($_params);
            Log::info('Worker payload data: ' . \GuzzleHttp\json_encode($payload_data));
            //dump($payload_data);
            //dump($payload_data['data']['track_key']);

            //$payload_sandbox = CampaignWorkerPayload::generateWorkerPayloadSandbox($notification, $payload_data);

            \Artisan::call('config:cache');
            \Config::set('fcm.http.server_key', $server_key);

            if(isset($device_tokens) && count($device_tokens) > 0 ) {

                $notifications = new NotificationController($device_tokens, $notification, $payload_data, $server_key, $content_available);
                $response = $notifications->sendNotification();
                //dump($response);
                if(isset($response) && count($response) > 0){

                    // prepare and parse response params
                    $number_success = (isset($response['numberSuccess'])) ? $response['numberSuccess'] : 0;
                    $numberFailure = (isset($response['numberFailure'])) ? $response['numberFailure'] : 0;
                    $tokensToDelete = (isset($response['tokensToDelete'])) ? $response['tokensToDelete'] : [];
                    $tokensWithError = (isset($response['tokensWithError'])) ? $response['tokensWithError'] : [];

                    $is_sent=true;
                    if(!empty($data['data']['track_keys'])){
                        foreach($data['data']['track_keys'] as $_track_key){
                            $_tracking = CampaignTracking::where('track_key', $_track_key)->first();
                            $_device_key = $_tracking->device_key;
                            $_tracking_id = $_tracking->id;
                            $variant_id = (isset($_tracking->variant_id)) ? $_tracking->variant_id : 1;
                            $_tracking_sent_at = Carbon::now()->format('Y-m-d h:i:s');

                            if((int)$number_success > 0 ) {

                                Log::info('Notification send with status: ' . AppStatusCodes::HTTP_OK);
                                $success_tokens = [];
                                $success_tokens = array_diff($device_tokens, $tokensToDelete);

                                if(in_array($_device_key, $success_tokens)){
                                    $_tracking->status=Campaign::CAMPAIGN_TRACKING_COMPLETED_STATUS;
                                    $_tracking->sent='1';
                                    $_tracking->sent_at=$_tracking_sent_at;
                                    $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->save();
                                    Log::info('Campaign tracking status updated.');

                                    $tracking_log=[];
                                    $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                    $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_COMPLETED_STATUS;
                                    $tracking_log['message'] = 'Notification sent successfully.';
                                    CampaignTrackingLog::create($tracking_log);
                                    Log::info('Campaign tracking logs updated.');

                                    // update tracking cache
                                    //$language = $payload_data['data']['language'];
                                    $last_sent_date = Carbon::now()->toDateTimeString();
                                    $sent_count = 1;
                                    $_tracking = new CampaignTrackingCache();
                                    $content = $data['notification']['body'];
                                    Log::info("body: " . $content);
                                    $_tracking->updateCampaignTrackingCache($campaign_id, $row_id, $language, $variant_id, $content, $last_sent_date, $sent_count);
                                    Log::info('Campaign tracking cache updated.');
                                }
                            }
                            if( isset($tokensToDelete) ) {
                                Log::info('Notification: tokens to delete.');
                                $result = CommonHelper::updateDeviceToken($tokensToDelete, [
                                    'is_revoked' => '1',
                                    'status' => '0',
                                    'deleted_at' => Carbon::now()->format('Y-m-d h:i:s')
                                ]);

                                if(in_array($_device_key, $tokensToDelete)){
                                    $_tracking->status=Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                    $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->save();
                                    Log::info('Campaign tracking failed status updated.');

                                    $tracking_log=[];
                                    $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                    $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                    $tracking_log['message'] = 'Notification sending failed due to expire token(s).';
                                    CampaignTrackingLog::create($tracking_log);
                                    Log::info('Campaign tracking logs updated.');
                                }
                            }
                            if( (int)$numberFailure == count($device_tokens) && !empty($tokensWithError) ){

                                $notification_error = "";
                                if(in_array($_device_key, $device_tokens)){

                                    $error_token = (isset($tokensWithError[$_device_key])) ? $tokensWithError[$_device_key] : "";
                                    if($error_token == "MessageTooBig"){
                                        $notification_error = "`MessageTooBig`, Payload size is too big.";
                                    }
                                    elseif($error_token == "InvalidApnsCredential"){
                                        $notification_error = "`InvalidApnsCredential`, expire token(s).";
                                    }
                                    else{
                                        $notification_error = "expire token(s).";
                                    }

                                    $_tracking->status=Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                    $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->save();
                                    Log::info('Campaign tracking failed status updated.');

                                    $tracking_log=[];
                                    $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                    $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                    $tracking_log['message'] = 'Notification sending failed due to '. $notification_error;
                                    CampaignTrackingLog::create($tracking_log);
                                    Log::info('Campaign tracking logs updated.');
                                }
                                Log::info('Notification failed due to: '.$notification_error);
                                $is_sent=false;
                            }
                            if( (int)$numberFailure == count($device_tokens) && empty($tokensWithError) ){

                                $notification_error = "expired token(s).";
                                if(in_array($_device_key, $device_tokens)){

                                    $_tracking->status=Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                    $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                    $_tracking->save();
                                    Log::info('Campaign tracking failed status updated.');

                                    $tracking_log=[];
                                    $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                    $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                    $tracking_log['message'] = 'Notification sending failed due to '. $notification_error;
                                    CampaignTrackingLog::create($tracking_log);
                                    Log::info('Campaign tracking logs updated.');
                                }
                                Log::info('Notification failed due to: '.$notification_error);
                                $is_sent=false;
                            }

                            //$campaign = Campaign::find($campaign_id);
                            //CampaignCappingControl::setCappingInfo($campaign, $row_id, $language, $variant_id, $_tracking_sent_at);
                        }
                    }
                    if(!$is_sent){
                        $response = [
                            'status' => 'failed',
                            'message' => 'Notification sending failed.'
                        ];
                    }
                    else{
                        $response = [
                            'status' => 'success',
                            'message' => 'Notification sent successfully.'
                        ];
                    }
                    /*if((int)$number_success > 0 ){

                        Log::info('Notification send with status: ' . AppStatusCodes::HTTP_OK);

                        $success_tokens = [];
                        $success_tokens = array_diff($device_tokens, $tokensToDelete);
                        //dump($success_tokens);
                        if(!empty($data['data']['track_keys'])){
                            foreach($data['data']['track_keys'] as $_track_key){
                                $_tracking = CampaignTracking::where('track_key', $_track_key)->first();
                                if($_tracking){
                                    $track_key = $_tracking->device_key;
                                    $_tracking_id = $_tracking->id;
                                    if(in_array($track_key, $success_tokens)){
                                        $_tracking->status=Campaign::CAMPAIGN_TRACKING_COMPLETED_STATUS;
                                        $_tracking->sent='1';
                                        $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                                        $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                        $_tracking->save();
                                        Log::info('Campaign tracking status updated.');
                                    }

                                    $tracking_log=[];
                                    $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                    $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_COMPLETED_STATUS;
                                    $tracking_log['message'] = 'Notification sent successfully.';
                                    CampaignTrackingLog::create($tracking_log);
                                    Log::info('Campaign tracking logs updated.');

                                    // update tracking cache
                                    //$language = $payload_data['data']['language'];
                                    $last_sent_date = Carbon::now()->toDateTimeString();
                                    $sent_count = 1;
                                    $_tracking = new CampaignTrackingCache();
                                    $content = $data['data']['message'];
                                    Log::info("body: " . $content);
                                    $_tracking->updateCampaignTrackingCache($campaign_id, $row_id, $language, $content, $last_sent_date, $sent_count);
                                    Log::info('Campaign tracking cache updated.');
                                }
                            }
                        }
                    }
                    if( isset($tokensToDelete) ){
                        $result = CommonHelper::updateDeviceToken($tokensToDelete, [
                            'is_revoked' => '1',
                            'status' => '0',
                            'deleted_at' => Carbon::now()->format('Y-m-d h:i:s')
                        ]);
                        foreach($tokensToDelete as $_token){
                            $_tracking = CampaignTracking::where('device_key', $_token)->where('campaign_id', '=', $campaign_id)->first();
                            if($_tracking){
                                $_tracking_id = $_tracking->id;
                                $_tracking->status=Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                                $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                $_tracking->save();
                                Log::info('Campaign tracking failed status updated.');

                                $tracking_log=[];
                                $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                $tracking_log['message'] = 'Notification sending failed.';
                                CampaignTrackingLog::create($tracking_log);
                                Log::info('Campaign tracking logs updated.');
                            }
                        }
                    }
                    if( (int)$numberFailure == count($device_tokens) ){
                        //dump($device_tokens);
                        foreach($payload_data['data']['track_key'] as $track_key){
                            $_tracking = CampaignTracking::where('track_key', $track_key)->first();
                            //dump($_tracking);
                            if($_tracking){
                                $_tracking_id = $_tracking->id;
                                $_tracking->status=Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                                $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                                $_tracking->save();
                                Log::info('Campaign tracking failed status updated.');

                                $tracking_log=[];
                                $tracking_log['campaign_tracking_id'] = $_tracking_id;
                                $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                                $tracking_log['message'] = 'Notification sending failed.';
                                CampaignTrackingLog::create($tracking_log);
                                Log::info('Campaign tracking logs updated.');
                            }
                        }
                        $response = [
                            'status' => 'failed',
                            'message' => 'Notification sending failed.'
                        ];
                        return $response;
                    }*/
                }
            }
            return $response;
        } catch (\Exception $exception) {

            $track_keys = (isset($data['tokens_data']['tracking_key'])) ? $data['tokens_data']['tracking_key'] : [];
            if(!empty($track_keys)){
                foreach($track_keys as $_track_key){
                    $_tracking = CampaignTracking::where('track_key', $_track_key)->first();
                    if($_tracking){
                        $_tracking_id = $_tracking->id;
                        $_tracking->status=Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                        $_tracking->sent_at=Carbon::now()->format('Y-m-d h:i:s');
                        $_tracking->ended_at=Carbon::now()->format('Y-m-d h:i:s');
                        $_tracking->save();
                        Log::info('Campaign tracking failed status updated.');

                        $tracking_log=[];
                        $tracking_log['campaign_tracking_id'] = $_tracking_id;
                        $tracking_log['status'] = Campaign::CAMPAIGN_TRACKING_FAILED_STATUS;
                        $tracking_log['message'] = $exception->getMessage();
                        CampaignTrackingLog::create($tracking_log);
                        Log::info('Campaign tracking failed logs updated.');
                    }
                }
            }
            $response = [
                'status' => 'error',
                'message' => $exception->getMessage()
            ];
            Log::info('Worker Failed Exception: ' . $exception);
            return $response;
        }
    }
}
