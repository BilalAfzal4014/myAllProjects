<?php

namespace App\Console\Commands;

use App\CampaignQueue;
use App\Components\CampaignQueueComponent;
use App\Components\TargetedUsers;
use App\Console\ConsoleOutputs;
use App\Queue;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class TargetUsersCommand extends Command
{
    use ConsoleOutputs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'target:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test command to dispatch campaign/newsfeed.';

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
        $id = $this->argument('id');
        $type = $this->option('queue');

        if (!empty($type) && in_array($type, ['campaign'])) {
            $queue = CampaignQueue::where('id', $id)->first();
            $services = new CampaignQueueComponent($queue);
        } else {
            $queue = Queue::where('id', $id)->first();
            $services = new TargetedUsers($queue);
        }

        dd($services->process());
    }

    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Queue ID');
        $this->addOption('queue', null, InputOption::VALUE_OPTIONAL, 'Queue Type');
    }
}
