<?php

namespace App\Components;

use App\Campaign;
use App\CampaignTrackingLogFiles;
use App\CampaignTypes;
use App\User;
use Illuminate\Http\Response;

class TargetedUsers
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
        if ($queue) {
            $this->queue = $queue;
            $this->type = 'campaign';
        }
    }


    public function process()
    {
        try {
            $class = "App\\Components\\".ucfirst($this->type)."Component";

            $response = (new $class)->dispatch($this->queue->id);

            if ($this->type == 'campaign') {
                $response = $this->processCampaign($response);

                $this->markQueueCompleted();

                return $response;
            }
        } catch (\Exception $exception) {
            $this->markExceptionWithQueue(
                $exception
            );

            return [
                'type'      => 'error',
                'message'   => $exception->getMessage()
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
            if($campaign->isPlatformAndroid()) {
                $response = $this->processFirebaseNotifications($campaign, $data);
            } elseif($campaign->isPlatformIOS()) {
                $response = $this->processPushNotifications($campaign, $data);
            } elseif($campaign->isPlatformUniversal()) {
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
            throw new \Exception("No notifications were sent as compiled data for campaign was invalid", Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Process iOS push notifications.
     *
     * @param \Illuminate\Database\Eloquent\Model $campaign
     * @param array $data
     *
     * @throws \Exception
     *
     * @return array|string
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
     * @throws \Exception
     *
     * @return array|string
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
     * @throws \Exception
     *
     * @return array|string
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
        \DB::table('queue')
            ->where('id', $this->queue->id)
            ->update([
                'status' => 'Complete',
                'error_message' => ''
            ]);
    }

    /**
     * @param \Exception $exception
     */
    protected function markExceptionWithQueue(\Exception $exception)
    {
        \DB::table('queue')
            ->where('id', $this->queue->id)
            ->update([
                'status' => 'Processing',
                'error_message' => $exception->getMessage()
            ]);
    }
}
