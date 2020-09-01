<?php

namespace App\Jobs;

use App\Campaign;
use App\InteractsWithCampaignCappingCache;
use App\InteractsWithJobHistory;
use App\Jobs\Job;
use App\Messaging\Message;
use App\User;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendInAppNotificationsJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels, InteractsWithJobHistory, InteractsWithCampaignCappingCache;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $field;

    /**
     * @var int
     */
    protected $row_id;

    /**
     * @var array
     */
    protected $data;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $track;

    /**
     * SendInAppNotificationsJob constructor.
     *
     * @param string $type
     * @param array $data
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return void
     */
    public function __construct($type, $data, $model = null)
    {
        $this->type = $type;
        $this->data = $data;
        $this->field = 'firebase_key';

        if (in_array($type, ['campaign'])) {
            $this->model = $model;
        }

        $this->addToHistory();

        $this->row_id = $this->track->row_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $track = $this->track;

        $company_id = '';
        if (!empty($this->data['company_id'])) {
            $company_id = $this->data['company_id'];
        } elseif (!empty($this->model->company_id)) {
            $company_id = $this->model->company_id;
        }

        $disk = \Storage::disk('public');
        $class = explode("\\", self::class);
        $className = $class[sizeof($class)-1];
        $logFile = $className."_".$this->track->id."_".Carbon::now()->format("YmdHis").".log";
        $output = [];
        $skipExecution = false;

        try {
            if (in_array($this->type, ['campaign'])) {
                $campaign = Campaign::find($this->model->id);
                if ($campaign->isSuspended()) {
                    throw new \Exception("Cannot send inapp notification as the campaign is currently suspended");
                }

                $campaign_type = $campaign->campaign_type;

                $company = User::where('id', $company_id)->first();
                if (empty($company)) {
                    throw new \Exception("Company not found.", 404);
                }

                if ((bool)$company->status === false) {
                    throw new \Exception("Company is currently disabled.", 401);
                }

                if ((bool)$company->is_deleted === true) {
                    throw new \Exception("Company is currently disabled.", 403);
                }
            }
        } catch (\Exception $exception) {
            $output[] = $this->failed($exception);

            $content = implode("\n\n", $output);
            //$disk->put($logFile, $content);

            $skipExecution = true;
        }

        if ($skipExecution === true) {
            return;
        }

        $this->updateStart();

        if (!empty($this->track->started_at)) {
            try {
                if (empty($track->firebase_key)) {
                    throw new \Exception("Firebase key is empty");
                }

                $data = unserialize(stripslashes(base64_decode($track->payload)));
                $apiKey = $data['apiKey'];
                unset($data['apiKey']);

                $output[] = "Sending the following payload for inapp notifications\n" . \GuzzleHttp\json_encode($data);

                $notification = new Message('firebase');

                if ($campaign_type->isInapp()) {
                    $data['alert']['data'] = 'success';
                }

                $notification->setApiKey($apiKey);
                $notification->setMessageTokens($track->firebase_key);
                $notification->compileData($data);

                $notifyResponse = $notification->send();
                if (!empty($notifyResponse['results']['error'])) {
                    throw new \Exception($notifyResponse['results']['error']);
                }

                $response = [
                    'status' => 'success',
                    'message' => 'Inapp message sent successfully!'
                ];

                $output[] = "It was delivered successfully. Status is: \n\n" . \GuzzleHttp\json_encode($response);

                $track->sent = '1';
                $track->sent_at = Carbon::now()->toDateTimeString();
                $track->save();

                $track->log()->create($response);

                $this->updateComplete();

                $this->setCappingInfo($this->model);

                $content = implode("\n\n", $output);
                //$disk->put($logFile, $content);
            } catch (\Exception $exception) {
                $output[] = $this->failed($exception);

                $content = implode("\n\n", $output);
                //$disk->put($logFile, $content);
            }
        }
    }

    /**
     * Update fields when job fails.
     *
     * @param \Exception $exception
     *
     * @return string
     */
    public function failed(\Exception $exception)
    {
        // Processing to be done when handling failed
        $this->updateFailed();

        $response = [
            'status' => 'error',
            'message' => $exception->getMessage()
        ];

        $this->track->log()->create($response);

        return "Errors occurred on delivery. Status is: \n\n" . \GuzzleHttp\json_encode($response);
    }
}
