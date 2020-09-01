<?php

namespace App\Console\Commands;

use App\AppGroup;
use App\Apps;
use App\AppUsers;
use App\Cache\AppUserLoginSignupCache;
use App\ImportData;
use App\Jobs\RebuildCacheJob;
use App\LinkTrackings;
use App\LocationArea;
use App\NewsFeedImpression;
use App\Translation;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class RemoveCompanyDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company:remove';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all data for specific company';

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
//        $id = $this->argument('id');
//        try {
//            $company = User::find($id);
//            if (empty($company)) {
//                echo 'Company not found.';
//                exit;
//            }
//
//            $users = AppUsers::select('row_id', 'app_id')->with([
//                'activities' => function ($q) {
//                    $q->select('id', 'row_id');
//                }])
//                ->where('company_id', $id)
//                ->get();
//
//            $groups = AppGroup::select('id')->with([
//                'locations' => function ($q) {
//                    $q->select('id', 'app_group_id');
//                }, 'newsFeeds' => function ($q) {
//                    $q->select('id', 'app_group_id');
//                }, 'segments' => function ($q) {
//                    $q->select('id', 'app_group_id');
//                }, 'campaigns' => function ($q) {
//                    $q->without(['segments', 'schedules', 'actions', 'variants', 'variants.translations']);
//                    $q->select('id', 'app_group_id');
//                }, 'campaigns.variants' => function ($q) {
//                    $q->select('id', 'campaign_id');
//                }, 'campaigns.campaign_tracking' => function ($q) {
//                    $q->select('id', 'row_id', 'campaign_id', 'variant_id', 'payload');
//                }])
//                ->where('company_id', $id)
//                ->get();
//
//            $keys = [];
//            $campaignIDs = [];
//
//            foreach ($groups as $group) {
//                $segments = $group->segments;
//                $campaigns = $group->campaigns;
//
//                array_push($keys, "app_group_id_" . $group->id . "_segments");
//                array_push($keys, "dashboard_stats_app_group_id_" . $group->id . "_popular_apps");
//                array_push($keys, "dashboard_stats_app_group_id_" . $group->id . "_newsfeed_clicks");
//                array_push($keys, "dashboard_stats_app_group_id_" . $group->id . "_newsfeed_views");
//                array_push($keys, "dashboard_stats_app_group_id_" . $group->id . "_campaign_conversion");
//                array_push($keys, "dashboard_stats_app_group_id_" . $group->id . "_campaigns");
//                array_push($keys, "dashboard_stats_app_group_id_" . $group->id . "_users");
//                array_push($keys, "process_export_users_app_group_id_" . $group->id . "_csv");
//                array_push($keys, "export_users_app_group_id_" . $group->id . "_csv");
//
//                foreach ($segments as $segment) {
//                    array_push($keys, "app_group_id_" . $group->id . "_segment_" . $segment->id . "_rows");
//                }
//
//                foreach ($campaigns as $campaign) {
//                    array_push($campaignIDs, $campaign->id);
//
//                    $campaignTrackings = $campaign->campaign_tracking;
//
//                    foreach ($campaignTrackings as $campaignTracking) {
//                        $payloadData = (!empty($campaignTracking->payload)) ? json_decode($campaignTracking->payload, true) : '';
//                        if (!empty($payloadData)) {
//                            $languageCode = (!empty($payloadData['data']['language'])) ? $payloadData['data']['language'] : '';
//                            if (!empty($languageCode)) {
//                                array_push($keys, "campaign_tracking_campaign_id_" . $campaignTracking->campaign_id . "_row_id_" . $campaignTracking->row_id . '_language_' . $languageCode . "_variant_" . $campaignTracking->variant_id);
//                                array_push($keys, "app_group_id_" . $group->id . "_campaign_" . $campaignTracking->campaign_id . "_row_" . $campaignTracking->row_id . "_language_" . $languageCode . "_variant_" . $campaignTracking->variant_id . "_caprule");
//                            }
//                        }
//                    }
//                    array_push($keys, "campaign_" . $campaign->id . "_segments");
//                }
//
//                $translations = Translation::select('template')->whereIn('translatable_id', $campaignIDs)
//                    ->where('translatable_type', 'campaign')
//                    ->get();
//
//                foreach ($translations as $translation) {
//                    array_push($keys, $translation->template);
//                }
//            }
//
//            foreach ($users as $user) {
//                $apps = Apps::where(['app_id' => $user->app_id])->first();
//                $app_group_id = (isset($apps->app_group_id)) ? $apps->app_group_id : "1";
//
//                array_push($keys, "app_group_id_" . $app_group_id . "_user_id_" . $user->row_id);
//                array_push($keys, "app_group_id_" . $app_group_id . "_row_id_" . $user->row_id);
//            }
//
//            \DB::beginTransaction();
//
//            foreach ($groups as $group) {
//
//                $newsFeeds = $group->newsFeeds->toArray();
//                $newsFeedIDs = [];
//                if (!empty($newsFeeds)) {
//                    $newsFeedIDs = array_column($newsFeeds, 'id');
//                }
//
//                $locations = $group->locations->toArray();
//                $locationIDs = [];
//                if (!empty($locations)) {
//                    $locationIDs = array_column($locations, 'id');
//                }
//
//                // Deleting News Feed Impressions
//                NewsFeedImpression::whereIn('news_feed_id', $newsFeedIDs)->forceDelete();
//
//                //Deleting Link Tracking
//                LinkTrackings::whereIn('rec_id', $newsFeedIDs)->forceDelete();
//
//                // Deleting Location Areas
//                LocationArea::whereIn('location_id', $locationIDs)->forceDelete();
//
//                // Deleting Translations
//                Translation::whereIn('translatable_id', $campaignIDs)->delete();
//
//                $segments = $group->segments;
//
//                // Deleting Segments
//                foreach ($segments as $segment) {
//                    $segment->delete();
//                }
//
//                // Deleting App Group
//                $group->delete();
//            }
//
//            // Deleting App User Activities and APP Users
//            foreach ($users as $user) {
//                $activities = $user->activities;
//                foreach ($activities as $activity) {
//                    $activity->forceDelete();
//                }
//                $user->forceDelete();
//            }
//
//            // Deleting Import Data
//            ImportData::where('company_id', $id)->delete();
//
//            // Deleting Company
//            $company->delete();
//
//            foreach ($keys as $key) {
//                AppUserLoginSignupCache::removeEntry($key);
//            }
//
//            \DB::commit();
//
//            echo 'Company removed successfully.';

//        } catch (\Exception $exp) {
//            \DB::rollBack();
//            $this->rebuildCache($id);
//
//            echo 'Company could not removed. Error Occurred: ' . $exp->getMessage();
//
//        }

    }

    public function rebuildCache($id)
    {
        try {
            $company = User::find($id);
            $company->update([
                'cache_status' => 'inprocess'
            ]);

            RebuildCacheJob::dispatch($company)->onQueue('rebuild_cache')->delay(Carbon::now()->addSeconds(10));

            echo 'Rebuild cache successfully!';
        } catch (\Exception $exp) {
            echo 'Rebuild cache Failed!';
        }
    }

    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Company ID');
    }
}
