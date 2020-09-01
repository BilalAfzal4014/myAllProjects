<?php

namespace App\Console\Commands;

use App\Jobs\CampaignSegmentsDataCacheJob;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class CampaignSegmentsDataCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaign:segmentscache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate segments cache for campaigns';

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
        $id = $this->option('company');
        $campaign_id = $this->option('campaign');
        $users = !empty($id) ? User::where('id', $id)->get() : User::all();

        foreach ($users as $user) {
            if ($user->campaigns->count() > 0) {
                if (!empty($campaign_id)) {
                    $campaigns = $user->campaigns->filter(function ($campaign) use($campaign_id) {
                        return ($campaign->id == $campaign_id) ? $campaign : null;
                    });
                } else {
                    $campaigns = $user->campaigns;
                }

                foreach ($campaigns as $campaign) {
                    \Queue::pushOn('segmentscache', new CampaignSegmentsDataCacheJob($campaign));

                    $this->info("Segments data has been cached for campaign id {$campaign->id}");
                }
            }
        }
    }

    /**
     * Configure input parameters.
     */
    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_OPTIONAL, 'Company ID');
        $this->addOption('campaign', null, InputOption::VALUE_OPTIONAL, 'Campaign ID');
    }
}
