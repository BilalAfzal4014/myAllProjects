<?php

namespace App\Components;

use App\Campaign;
use App\CampaignQueue;
use App\CampaignTypes;
use App\Helpers\CommonError;
use Carbon\Carbon;
use App\CampaignQueueLog;

class CampaignQueueComponent
{
    use CompileActiveAppsList, InteractsWithMessages;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $queue;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $campaign;

    /**
     * TargetedUsers constructor.
     *
     * @param mixed $queue
     */
    public function __construct($queue = null)
    {
        $this->queue = $queue;
    }


    public function process()
    {
        try {
            if ( $this->queue->status  ==  CampaignQueue::STATUS_PROCESSING) return false;
            $this->queue->status = CampaignQueue::STATUS_PROCESSING;
            $this->queue->save();

            $campaign = Campaign::findOrFail($this->queue->campaign_id);
            $segment_rowIds = CompanyAttributeData::campaignSegments($campaign->company_id, $campaign->id, true);

          
            // campaign queue log check


            $query = CampaignQueueLog::where([
               ['campaign_id' , '=', $campaign->id],
               ['created_at' , '=', Carbon::now()->toDateString()],
            ])->first();

            if ($query) {
                $query->attempts = $query->attempts + 1;
                $query->save();
            } else {

                $campaignQueueLog = new CampaignQueueLog();
                $campaignQueueLog->campaign_id = $campaign->id;
                $campaignQueueLog->created_at = Carbon::now()->toDateString();
                $campaignQueueLog->attempts = 1;
                $campaignQueueLog->row_ids = count($segment_rowIds) ;//implode(',', $segment_rowIds);
                $campaignQueueLog->save();

                // ============================================== // CHECK COMPANY // ============================================== //
            if ($campaign->isDraft()) {
                throw new \Exception("Cannot dispatch campaign with status 'draft'", 422);
            } elseif ($campaign->isSuspended()) {
                throw new \Exception("Cannot dispatch campaign with status 'suspended'", 422);
            } elseif ($campaign->isExpired()) {
                throw new \Exception("Cannot dispatch campaign with status 'expired'", 422);
            } else {
                if ($campaign->isDeliveryTypeSchedule()) {
                    $now = Carbon::now(config('app.timezone'));
                    $startDate = Carbon::parse($campaign->start_time);

                    if ($now->lt($startDate)) {
                        throw new \Exception("Cannot dispatch campaign as its start date is greater than current date", 422);
                    }
                }
            }

            $company = $campaign->company;
            if (empty($company)) {
                throw new \Exception("Company not found.", 404);
            }

            if ((bool)$company->status === false) {
                throw new \Exception("Company is currently disabled.", 401);
            }

            if ((bool)$company->is_deleted === true) {
                throw new \Exception("Company is currently disabled.", 403);
            }


                // =========== Memory was exhausting due to large data in cache =============================//
                // ======= Below block was included for mahing the chunks of large data set ================ //
                $chunksSegmentRowids = array_chunk($segment_rowIds, env("CAMPAIGN_DISPATCH_CHUNK_SIZE"));

                for ($index = 0; $index < count($chunksSegmentRowids); $index++) {

                    $pre_dispatched_data = '';
                    if ($pre_dispatched_data) unset($pre_dispatched_data);

                    $pre_dispatched_data = (new CampaignComponent())->dispatchCampaign($campaign, $chunksSegmentRowids[$index]);

                    if ($pre_dispatched_data) {
                        $this->processCampaign($pre_dispatched_data);
                    }
                    
                }
                // ============================================== //


                $this->markQueueCompleted();
            }


        } catch (\Exception $exception) {

            $this->markExceptionWithQueue($exception);


            return [
                'type' => 'error',
                'message' => $exception->getMessage()
            ];
        }
    }

    /**
     * Process associated campaign.
     *
     * @param array $data
     *
     * @return array
     *
     * @throws \Exception
     */
    public function processCampaign($data)
    {
        $campaign = Campaign::findOrFail($data['campaign_id']);
        $type = CampaignTypes::findOrFail($campaign->type_id);

        if ($type->isPush() && $campaign->isPlatformAndroid() && $campaign->isPlatformIOS() && $campaign->isPlatformUniversal()) {
            throw new \Exception("For push notifications, a valid platform must be provided.");
        }

        $this->campaign = $campaign;

        $response = true;

        if ($type->isEmail()) {
            $response = $this->sendEmail($campaign, $data);
        } elseif ($type->isPush()) {
            if ($campaign->isPlatformAndroid()) {
                $response = $this->processFirebaseNotifications($campaign, $data);
            } elseif ($campaign->isPlatformIOS()) {
                $response = $this->processPushNotifications($campaign, $data);
            } elseif ($campaign->isPlatformUniversal()) {
                $apns = $this->processPushNotifications($campaign, $data);
                $fcm = $this->processFirebaseNotifications($campaign, $data);

                if (($apns === false) && ($fcm === false)) {
                    $response = false;
                }
            }
        } elseif ($type->isInapp()) {
            $response = $this->processFirebaseNotifications($campaign, $data);
        }

        if ($response === false) {
//            throw new \Exception("No notifications were sent as compiled data for campaign was invalid", CommonError::STATUS_CODE_NOT_FOUND);
        }
    }

    /**
     * Process iOS push notifications.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $data
     *
     * @return array|string
     * @throws \Exception
     *
     */
    protected function processPushNotifications($campaign, $data)
    {
        $pushResponse = $this->useIOSPushNotifications($campaign, $data);

        return $pushResponse;
    }

    /**
     * Process Firebase notifications.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $data
     *
     * @return array|string
     * @throws \Exception
     *
     */
    protected function processFirebaseNotifications($campaign, $data)
    {
        $inAppResponse = $this->useFireBaseNotifications($campaign, $data);

        return $inAppResponse;
    }

    /**
     * Send email to users.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $users
     *
     * @return array|string
     * @throws \Exception
     *
     */
    protected function sendEmail($campaign, $users)
    {
        $emailResponse = $this->useEmailMessage($campaign, $users);

        return $emailResponse;
    }

    /**
     * Mark queue as Completed.
     */
    public function markQueueCompleted()
    {
        $this->queue->status = CampaignQueue::STATUS_COMPLETE;
        $this->queue->error_message = '';
        $this->queue->save();
    }

    /**
     * @param \Exception $exception
     */
    protected function markExceptionWithQueue(\Exception $exception)
    {
        $this->queue->status = CampaignQueue::STATUS_FAILED;
        $this->queue->error_message = $exception->getMessage();
        $this->queue->save();
    }
}
