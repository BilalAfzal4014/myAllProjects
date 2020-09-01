<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DispatchServicesJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;


    /**
     * @var int
     */
    protected $queue;

    /**
     * Create a new job instance.
     *
     * @param string $queue
     *
     * @return void
     */
    public function __construct($queue)
    {
        $this->queue = $queue;
    }

    public function _add_job($params = [])
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

    }
}
