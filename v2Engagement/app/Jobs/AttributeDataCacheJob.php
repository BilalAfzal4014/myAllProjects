<?php

namespace App\Jobs;

use App\Components\CompanyAttributeData;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Artisan;

class AttributeDataCacheJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $company;
    protected $row;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($company, $row)
    {
        $this->company = $company;
        $this->row = $row;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        CompanyAttributeData::updateRow($this->company, $this->row);
        CompanyAttributeData::appCache($this->company, $this->row);

    }
}
