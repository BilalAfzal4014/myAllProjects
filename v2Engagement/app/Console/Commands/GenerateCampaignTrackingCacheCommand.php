<?php

namespace App\Console\Commands;

use App\Campaign;
use App\CampaignTracking;
use App\Components\CompanyAttributeData;
use App\Jobs\CampaignTrackingCacheJob;
use App\UserCampaign;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class GenerateCampaignTrackingCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate campaign tracking cache';

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
        $company = (int)$this->option('company');
        $campaign_id = (int)$this->option('campaign');
        $orphan = ((bool)$this->option('remove-orphan') === true) ? true : false; // if remove-orphan is true then delete tracking cache cd
        $campaigns = [];

        $statusCondition = 'status = "active"';
        if (empty($company) && empty($campaign_id)) {
            $campaigns = \Illuminate\Support\Facades\DB::select("SELECT * FROM campaign ;");

        } else {
            if (!empty($company)) {
                $campaigns = \Illuminate\Support\Facades\DB::select("SELECT * FROM campaign where company_id = {$company} ;");
            }

            if (!empty($campaign_id)) {

                //if (!empty($campaigns)) {
                    $campaigns = \Illuminate\Support\Facades\DB::select("SELECT * FROM campaign where id = {$campaign_id} ;");
                //}
            }

        }

        if ($orphan === true) {

            // Deleting campaign tracking data
            \Illuminate\Support\Facades\DB::delete("DELETE FROM campaign_tracking  WHERE campaign_id NOT IN(SELECT id FROM campaign);");

            // Deleting campaign tracking logs data
            \Illuminate\Support\Facades\DB::delete("DELETE FROM campaign_tracking_logs WHERE campaign_tracking_id NOT IN (SELECT id FROM campaign_tracking);");

            // Deleting user campaign data
            \Illuminate\Support\Facades\DB::delete("DELETE FROM user_campaign  WHERE campaign_id NOT IN(SELECT id FROM campaign);");
        }

        if (count($campaigns) > 0) {

            $company_campaignIds = [];

            foreach ($campaigns as $campaign) {
                if (!isset($company_campaignIds[$campaign->company_id])) {
                    $company_campaignIds[$campaign->company_id] = [];

                }

                array_push($company_campaignIds[$campaign->company_id], $campaign->id);

                $cache_key = "company_{$campaign->company_id}_campaign_{$campaign->id}_tracking";

                CompanyAttributeData::removeEntry($cache_key);


                \Queue::pushOn('trackingcache', new CampaignTrackingCacheJob($cache_key, $campaign->id));

                $this->info("Tracking cache job created for campaign id {$campaign->id}");


                $conversions = \Illuminate\Support\Facades\DB::select("SELECT * FROM user_campaign where campaign_id = {$campaign->id};");

                $conversions = array_map(function ($value) {
                    return (array)$value;
                }, $conversions);

                $cache_key = "company_{$campaign->company_id}_campaign_{$campaign->id}_conversions";
                CompanyAttributeData::removeEntry($cache_key);

                if (count($conversions) > 0) {

                    \Cache::forever($cache_key, \GuzzleHttp\json_encode($conversions));

                    $this->info("Conversion data has been cached for campaign id {$campaign->id}");
                }
            }

            if (isset($company)) {
                $cache_key = "company_{$company}_campaigns";
                CompanyAttributeData::removeEntry($cache_key);
            }

            if (!empty($company_campaignIds)) {
                foreach ($company_campaignIds as $company_id => $campaignIds) {
                    $cache_key = "company_{$company_id}_campaigns";
                    CompanyAttributeData::removeEntry($cache_key);
                    \Cache::forever($cache_key, \GuzzleHttp\json_encode($campaignIds));
                }
            }
        }
    }

    protected function configure()
    {
        $this->addOption('remove-orphan', null, InputOption::VALUE_OPTIONAL, 'Remove tracking entries for non-existent campaigns', false);
        $this->addOption('company', null, InputOption::VALUE_OPTIONAL, 'Generate cache for a specific company id');
        $this->addOption('campaign', null, InputOption::VALUE_OPTIONAL, 'Generate cache for a specific campaign id');
    }
}
