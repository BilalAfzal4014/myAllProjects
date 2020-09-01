<?php

namespace App\Jobs;

use App\CampaignTracking;
use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignTrackingCacheJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $campaign;

    /**
     * Create a new job instance.
     *
     * @param string $key
     * @param \Illuminate\Database\Eloquent\Model $campaign
     *
     * @return void
     */
    public function __construct($key, $campaign)
    {
        $this->key = $key;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $campaignTracks = \Illuminate\Support\Facades\DB::select("SELECT 
		id,
		campaign_id,
        email,
        firebase_key,
        device_key,
        device_type,
        sent,
        created_at,
        viewed,
        viewed_at,
        sent_at
        FROM
        campaign_tracking where campaign_id = {$this->campaign};");

        if (!empty($campaignTracks)) {

            $campaignTracks = array_map(function ($value) {
                return (array)$value;
            }, $campaignTracks);

            \Cache::forget($this->key);
            \Cache::forever($this->key, \GuzzleHttp\json_encode($campaignTracks));
        }
    }
}
