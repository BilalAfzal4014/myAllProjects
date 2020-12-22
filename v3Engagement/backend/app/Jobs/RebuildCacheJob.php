<?php

namespace App\Jobs;

use App\AppGroup;
use App\AppUsers;
use App\Cache\AppGroupSegmentCache;
use App\Cache\AppUserLoginSignupCache;
use App\Cache\CacheKeys;
use App\Cache\CampaignSegmentCache;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class RebuildCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $company;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company)
    {
        $this->company = $company;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('max_execution_time', 600); //600 seconds = 10 minutes
        ini_set('memory_limit', '2048M');

        $limit = config('engagement.api.headers.limit');

        $company = $this->company;

        $app_users = AppUsers::where('company_id', $company->id)
                                ->where('is_deleted', 0)
                                ->where('deleted_at', NULL)
                                ->get()->chunk($limit);
        if (count($app_users) > 0) {
            foreach ($app_users as $users){
                foreach($users as $user){
                    // app_group_id_100_row_id_500
                    $cache = new AppUserLoginSignupCache();
                    $cache->saveAppUserSignupCache([
                        'user_id' => $user->user_id,
                        'app_id' => $user->app_id,
                        'company_id' => $company->id,
                        'app_group_id' => $user->app_group_id,
                        'mode' => AppUsers::USER_REBUILD_CACHE
                    ]);
                }
                Log::info(" app users count: ". count($users) ."\n");
            }
        }

        $groups = AppGroup::with('segments', 'segments.campaigns')
            ->where('company_id', $company->id)
            ->get();

        if(count($groups) > 0) {
            foreach ($groups as $group) {
                $segments = $group->segments;

                $_key = new CacheKeys($group->id);
                $cache_key = $_key->generateAppGroupSegmentKey();
                AppGroupSegmentCache::removeEntry($cache_key);

                foreach ($segments as $segment) {
                    // app_group_id_1_segment_1_rows
                    $_key = new CacheKeys($segment->app_group_id);
                    $cache_key = $_key->generateAppGroupSegmentRowsKey($segment->id);
                    AppGroupSegmentCache::removeEntry($cache_key);

                    $cache = new AppGroupSegmentCache();
                    $cache->saveAppGroupSegmentRowsCache($segment, true);

                    // app_group_id_1_segments
                    $segment_cache = new AppGroupSegmentCache();
                    $segment_cache->saveAppGroupSegmentCache($segment);

                    $campaigns = $segment->campaigns;
                    foreach ($campaigns as $campaign) {
                        $campaignCache = new CampaignSegmentCache();
                        $campaignCache->saveCampaignSegmentCache($campaign);
                    }
                }
            }
        }

        $company->update([
            'cache_status' => 'completed'
        ]);
    }
}
