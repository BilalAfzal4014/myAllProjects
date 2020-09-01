<?php

namespace App\Jobs;

use App\Components\CompanyAttributeData;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SegmentsDataCacheJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $segments;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Support\Collection $segments
     *
     * @return void
     */
    public function __construct($segments)
    {
        $this->segments = $segments;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->segments as $segment) {
            CompanyAttributeData::segments($segment);

            $status = CompanyAttributeData::segmentCache($segment, true);
            if ($status === false) {
                CompanyAttributeData::segmentCache($segment);
            }

            CompanyAttributeData::campaignSegmentsCache($segment);
        }
    }
}
