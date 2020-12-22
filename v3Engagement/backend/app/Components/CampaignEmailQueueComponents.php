<?php
/**
 * Created by PhpStorm.
 * User: ets-rebel
 * Date: 2/20/19
 * Time: 5:37 PM
 */

namespace App\Components;

use App\Campaign;
use App\CampaignTracking;
use App\Cache\CampaignTrackingCache;
use App\Helpers\CampaignValidation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CampaignEmailQueueComponents
{
    use InteractsWithMessages;
    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $email_payload;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $campaign;

    /**
     * TargetedUsers constructor.
     *
     * @param mixed $queue
     */

    public function __construct($payload = null)
    {
        $this->email_payload = $payload;
    }

    /**
     * Sending Email processing methods
     */
    public function process()
    {
        try {
            Log::info('Email worker job processing...');

            $trackingKey = $this->email_payload['data']['track_key'];

            /**
             * Campaign Validation Checks
             */
            $validation = CampaignValidation::validation($this->email_payload['data']['campaign_id']);

            /**
             * Checking Campaign Tracking id is valid
             */
            $campaignTracking = CampaignTracking::where('track_key', '=', $trackingKey)->first();

            if (!$campaignTracking) {
                throw new \Exception("Tracking key Is InValid");
            }

            $campaign_tracking_id = $campaignTracking->id;
            $variant_id = (isset($campaignTracking->variant_id)) ? $campaignTracking->variant_id : 1;
            $this->email_payload['data']['campaign_tracking_id'] = $campaign_tracking_id;
            Log::info('Campaign tracking found.');

            /**
             * update the Campaign Tracking status from added to executing
             */
            $this->updateTrackingStatus($trackingKey, Campaign::CAMPAIGN_TRACKING_EXECUTING_STATUS);

            /**
             * Sending email message to the users,
             */
            Log::info('Tracking payload: ' . \GuzzleHttp\json_encode($this->email_payload['data']));
            $response = $this->sendEmail($this->email_payload['data']);

            /**
             * updating the campaign tracking status from executing to complete,
             */
            $trackinResponse=[];
            if ($response['status'] == "success") {
                Log::info('Email send with status: '. $response['status']);

                $response['campaign_tracking_id'] = $campaign_tracking_id;
                $trackinResponse = $this->savetrackingEmailLogs($response, $trackingKey);
                unset($response['campaign_tracking_id']);
                Log::info('Campaign tracking status updated.');

                // update tracking cache
                $last_sent_date = Carbon::now()->toDateTimeString();
                $sent_count = 1;
                $_tracking = new CampaignTrackingCache();
                $campaign_id = $this->email_payload['data']['campaign_id'];
                $row_id = $this->email_payload['data']['row_id'];
                $content = $this->email_payload['data']['email_body'];
                $language =  (isset($this->email_payload['data']['language'])) ? $this->email_payload['data']['language'] : "en";
                $_tracking->updateCampaignTrackingCache($campaign_id, $row_id, $language, $variant_id, $content, $last_sent_date, $sent_count);
                //$_tracking->updateCampaignTrackingCache($campaign_id, $app_user_row_id, $content, $last_sent_date, $sent_count);
                Log::info('Campaign tracking logs updated.');
            }
            return $trackinResponse;
        } catch (\Exception $exception) {
            Log::error('Campaign processed with error: '. $exception->getMessage());
            $result = $this->updateFailedTrackingStatus($this->email_payload['data']['track_key'], Campaign::CAMPAIGN_TRACKING_FAILED_STATUS);
            //$response = $this->trackingLogsFailedPushInApp($exception, $data);
            return $exception;
        }
    }
}