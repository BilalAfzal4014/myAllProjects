<?php

namespace App\Console\Commands;

use App\CampaignQueue;
use Illuminate\Console\Command;

class AddItemsToCampaignQueuesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:queues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add campaigns for dispatch to campaign_queues table';

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
        $results = \DB::select("SELECT * FROM vw_campaignQueues");
        if (!empty($results)) {
            $campaignIds = [];

            foreach ($results as $result) {
                if (in_array($result->id, $campaignIds)) {
                    continue;
                }

                $campaignIds[] = $result->id;

                $queue = new CampaignQueue();
                $queue->campaign_id = $result->id;
                $queue->status = CampaignQueue::STATUS_AVAILABLE;
                $queue->priority = CampaignQueue::priority($result->campaign_priority);
                $queue->start_at = $result->start_at;
                $queue->details = $result->details;
                $queue->save();
            }

            $this->info("Items added into campaign queues successfully");
        } else {
            $this->error("Unable to add items to campaign queues");
        }
    }

}
