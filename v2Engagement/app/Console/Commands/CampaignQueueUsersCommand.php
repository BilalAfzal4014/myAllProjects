<?php

namespace App\Console\Commands;

use App\CampaignQueue;
use App\Components\CampaignQueueComponent;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class CampaignQueueUsersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backend:campaign:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to dispatch campaign queue for admin backend';

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

        $queue = CampaignQueue::where('id', $id)->first();
        $services = new CampaignQueueComponent($queue);

        dd($services->process());
    }

    protected function configure()
    {
        $this->addArgument('id', InputArgument::REQUIRED, 'Queue ID');
    }
}
