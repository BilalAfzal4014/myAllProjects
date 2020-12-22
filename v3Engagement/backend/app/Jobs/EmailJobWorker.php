<?php

namespace App\Jobs;

use App\Campaign;
use App\CampaignQueue;
use App\Components\CampaignEmailQueueComponents;
use App\Components\CampaignQueueComponent;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EmailJobWorker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @Document Payload recived for Push
     */
    private $payload;
    /**
     * EmailJobWorker constructor.
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

            $campaign_type = Campaign::CAMPAIGN_EMAIL_CODE;
            $campaign_id = (isset($data['data']['campaign_id'])) ? $data['data']['campaign_id'] : "";

            $disk = \Storage::disk('public');
            $logFile = strtolower($campaign_type)."_logs.log";
            $output = [];

            if ($data['data']['campaign_type'] == Campaign::CAMPAIGN_EMAIL_CODE) {

                $output[] = "Job started on: ". Carbon::now()->format("Y-m-d h:i:s");
                $output[] = "Campaign id: ". $campaign_id;
                $output[] = "Campaign type: ". $campaign_type;
                $output[] = 'Payload: '. \GuzzleHttp\json_encode($data);

                $target = new CampaignEmailQueueComponents($data);
                $result=$target->process();

                Log::info($campaign_type." job executed with result: ". \GuzzleHttp\json_encode($result));
                $output[] = $campaign_type.' job executed with result: '. \GuzzleHttp\json_encode($result);
                $content = implode("\n", $output)."\n";
                $disk->put($logFile, $content);

                //Log::emergency("Email job worker completed");
                //Log::info("Email job worker completed: ". \GuzzleHttp\json_encode($result));
            }else{
                throw new \Exception("Failed, campaign type is not email.");
            }
        }catch (\Exception $exception) {
            //Log::emergency('Email job worker failed: '. $exception->getMessage());
            //Log::info('Email job worker failed: '. $exception->getMessage());
            Log::error($campaign_type.' job failed: '. $exception->getMessage());
            $output[] = $campaign_type.' job failed: '. $exception->getMessage();
            $content = implode("\n", $output)."\n";
            $disk->put($logFile, $content);
        }
    }
}
