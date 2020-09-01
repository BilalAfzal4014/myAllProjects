<?php

namespace App\Console\Commands;

use App\Components\CompanyAttributeData;
use App\LinkTracking;
use App\NewsFeed;
use App\NewsFeedImpression;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class GenerateNewsfeedCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsfeed:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate newsfeed cache';

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
        $newsfeedId = $this->option('newsfeed');
        $users = !empty($id) ? User::where('id', $id)->get() : User::all();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                $newsfeeds = NewsFeed::where('company_id', $user->id);

                if (!empty($newsfeedId)) {
                    $newsfeeds = $newsfeeds->where('id', $newsfeedId);
                }

                if ($newsfeeds->count() > 0) {
                    $newsfeeds = $newsfeeds->get();

                    $newsfeedIds = $newsfeeds->pluck('id')->unique()->toArray();
                    $cache_key = "company_{$user->id}_newsfeeds";

                    CompanyAttributeData::removeEntry($cache_key);
                    \Cache::forever($cache_key, \GuzzleHttp\json_encode($newsfeedIds));

                    foreach ($newsfeeds as $newsfeed) {
                        $cache_key = "company_{$user->id}_newsfeed_{$newsfeed->id}_clicks";

                        $newsfeed_clicks = LinkTracking::where('rec_type', 'Newsfeed')
                            ->where('rec_id', $newsfeed->id);

                        CompanyAttributeData::removeEntry($cache_key);

                        if ($newsfeed_clicks->count() > 0) {
                            $newsfeed_clicks = $newsfeed_clicks->get()->toArray();

                            \Cache::forever($cache_key, \GuzzleHttp\json_encode($newsfeed_clicks));

                            $this->info("Clicks data has been cached for newsfeed id {$newsfeed->id}");
                        }

                        $cache_key = "company_{$user->id}_newsfeed_{$newsfeed->id}_views";
                        CompanyAttributeData::removeEntry($cache_key);

                        $newsfeed_views = NewsFeedImpression::where('news_feed_id', $newsfeed->id);
                        if ($newsfeed_views->count() > 0) {
                            $newsfeed_views = $newsfeed_views->get()->toArray();

                            \Cache::forever($cache_key, \GuzzleHttp\json_encode($newsfeed_views));

                            $this->info("Views data has been cached for newsfeed id {$newsfeed->id}");
                        }
                    }
                } else {
                    $this->error("No Newsfeed found for company id {$user->id}");
                }
            }
        }
    }

    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_OPTIONAL, 'Generate cache for a specific company id');
        $this->addOption('newsfeed', null, InputOption::VALUE_OPTIONAL, 'Generate cache for a specific newsfeed id');
    }
}
