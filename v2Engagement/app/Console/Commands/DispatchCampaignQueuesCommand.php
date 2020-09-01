<?php

namespace App\Console\Commands;

use App\CampaignQueue;
use App\Jobs\DispatchCampaignQueueJob;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DispatchCampaignQueuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:campaign:queue';

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
            $limit = config('engagement.limit.queues');
            
           
            $queuesList = CampaignQueue::where('status', 'Available')
                ->whereBetween('start_at', [$startTime, $endTime])
                ->orderBy('priority', 'ASC')
                ->orderBy('start_at', 'ASC')->limit($limit)->get();           
            $campaignIds = [];
            if ($queuesList->count() > 0) {
   
                foreach ($queuesList as $queue) {
                      if (!in_array($queue->campaign_id, $campaignIds)) {
                            $campaignIds[] = $queue->campaign_id;
                        } else {
                            $queue->error_message = "Campaign has already been dispatched!";
                            $queue->status = CampaignQueue::STATUS_FAILED;
                            $queue->save();
                            continue;
                        }

                       if ($queue->start_at->lte($startTime)) {
                            \Queue::pushOn('queue' . $queue->priorityKey(), new DispatchCampaignQueueJob($queue));
                        } else {
                            $delay = $startTime->diffInSeconds($queue->start_at);
                            \Queue::laterOn('queue' . $queue->priorityKey(), $delay, new DispatchCampaignQueueJob($queue));
                        }

                }
            } else {
                $this->error("No campaigns found for dispatch");
            }
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }
}
