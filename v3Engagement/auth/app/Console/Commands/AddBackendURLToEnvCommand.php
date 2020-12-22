<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class AddBackendURLToEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'engagement:backend-url';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add backend URL to engagement config';

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
        $host = trim(file_get_contents(
            base_path('backendhost')
        ));
        $url = "BACKEND_API_URL=http://{$host}/api/";

        $env = file_get_contents($this->laravel->environmentFilePath()) . "\n{$url}";
        file_put_contents(
            $this->laravel->environmentFilePath(),
            $env
        );
    }
}
