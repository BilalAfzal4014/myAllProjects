<?php

namespace App\Console\Commands;

use App\Console\ConsoleOutputs;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class DispatchCampaigns extends Command
{
    use ConsoleOutputs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dispatch:campaigns';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch Emails for a campaign';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

//        $this->getCommandDetails();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
//            $instances = $this->checkInstances();
//            if (! $instances) { exit; }
//
//            $this->incrementInstanceCount();

            $client = new Client();
            $request = $client->get(config('engagement.urls.broadcast'));
            $response = json_decode($request->getBody()->getContents(), true);

            if ($response['type'] == 'error') {
                throw new \Exception($response['message']);
            }

            $this->addToOutput($response['message']);
            $this->showOutput();

//            $this->decrementInstanceCount();
//
//            $this->resetRetryLimit();
        } catch (\Exception $exception) {
            $this->addToOutput($exception->getMessage());
            $this->showOutput();
        }
    }
}
