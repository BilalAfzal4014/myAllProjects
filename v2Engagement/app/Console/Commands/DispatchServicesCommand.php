<?php

namespace App\Console\Commands;

use App\Components\TargetedUsers;
use App\Console\ConsoleOutputs;
use App\Queue;
use Illuminate\Console\Command;

class DispatchServicesCommand extends Command
{
    use ConsoleOutputs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:services';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch Campaign/Newsfeed';

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
        $queue = Queue::where('status', 'Available')->first();

        if (!empty($queue->id)) {
            $this->call('queue:restart');

            $target = new TargetedUsers($queue);
            $target->process();
        }
    }
}
