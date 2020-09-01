<?php

namespace App\Console\Commands;

use App\CampaignQueue;
use App\Http\Resources\V1\Notifications\SendNotifications;
use App\Jobs\DispatchCampaignQueueJob;
use App\Jobs\PushJobWorker;
use App\Jobs\TestJobNew;
use App\Components\CampaignDispatcher;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Queue;

class DispatchCampaignQueuesCommand extends Command
{
    use CommonTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:campaign:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch campaign queues.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

            $startTime = Carbon::now()->startOfMinute();
            $endTime = Carbon::now()->endOfMinute();

            Log::emergency('Listening for Campaign.' . $startTime . " : " . $endTime);

            $queuesList = CampaignQueue::where('status', '=', CampaignQueue::STATUS_AVAILABLE)
                ->whereBetween('start_at', [$startTime, $endTime])
                ->orderBy('priority', 'ASC')
                ->get();
            $campaignIds = [];
            if (count($queuesList) > 0) {
                Log::emergency('Found campaign to dispatch.');
                foreach ($queuesList as $queue) {
                    Log::info('Queue: ' . \GuzzleHttp\json_encode($queue));
                    if (!in_array($queue->campaign_id, $campaignIds)) {
                        $campaignIds[] = $queue->campaign_id;
                    } else {
                        $error_message = "Campaign has already been dispatched!";
                        Log::emergency($error_message);
                        Log::info($error_message);
                        $queue->error_message = $error_message;
                        $queue->status = CampaignQueue::STATUS_FAILED;
                        $queue->save();
                        continue;
                    }

                    self::campaignQueueDispatch($queue);
                }
                Log::emergency('------------------------------------------');
            } else {
                Log::emergency('No campaign found.');
                $this->error("No campaigns found for dispatch.");
            }
        } catch (\Exception $exception) {
            Log::error($exception); // ->getMessage()
            dd($exception);
        }
    }
}
