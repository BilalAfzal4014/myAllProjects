<?php

namespace App\Console\Commands;

use App\Components\CompanyAttributeData;
use App\Jobs\SegmentsDataCacheJob;
use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

class SegmentsDataCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'segment:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate segments cache';

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
        $segment_id = $this->option('segment');
        $users = !empty($id) ? User::where('id', $id)->get() : User::all();

        $interval = config('engagement.batch.segments.interval');
        $increment = config('engagement.batch.segments.increment');

        foreach ($users as $user) {
            $segments = $user->segments;
            if ($segments->count() > 0) {
                if (!empty($segment_id)) {
                    $segments = $segments->filter(function ($segment) use($segment_id) {
                        return ($segment->id == $segment_id) ? $segment : null;
                    });
                }

                $segmentLists = $segments->chunk(config('engagement.limit.segments'));

                $segmentInterval = $interval;
                foreach ($segmentLists as $segmentList) {
                    \Queue::laterOn('segmentscache', $segmentInterval, new SegmentsDataCacheJob($segmentList));
                    $segmentInterval += $increment;

                    $this->info("Segment data cached for segment ids " . $segmentList->pluck('id')->implode(','));
                }
            } else {
                $this->error("No segment data found for company id {$user->id}");
            }
        }
    }

    /**
     * Configure input parameters.
     */
    protected function configure()
    {
        $this->addOption('company', null, InputOption::VALUE_OPTIONAL, 'Company ID');
        $this->addOption('segment', null, InputOption::VALUE_OPTIONAL, 'Segment ID');
    }
}
