<?php

namespace App\Jobs;

use App\Components\CompanyAttributeData;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CampaignSegmentsDataCacheJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($campaign)
    {
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CompanyAttributeData::segmentsCache($this->campaign);
    }
}
