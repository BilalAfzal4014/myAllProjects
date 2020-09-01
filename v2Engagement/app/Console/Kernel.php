<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\AddItemsToCampaignQueuesCommand::class,
        Commands\AttributeDataImportCommand::class,
        Commands\FlagEmailsCommand::class,
        Commands\GenerateAttributeDataCacheCommand::class,
        Commands\GenerateCampaignTrackingCacheCommand::class,
        Commands\GenerateNewsfeedCacheCommand::class,
        Commands\SegmentsDataCacheCommand::class,
        Commands\TargetUsersCommand::class,
        Commands\SetupApplicationCommand::class,
        Commands\CreateCompanyUserCommand::class,
        Commands\ClearCompanyCacheCommand::class,
        Commands\CampaignQueueUsersCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        if (!empty(config('jobs.list'))) {
            foreach (config('jobs.list') as $key => $item) {
                $class = "App\\Console\\Commands\\".$key;
                if (class_exists($class) && !in_array($class, $this->commands)) {
                    $this->commands[] = $class;
                }

                try {
                    $schedule->command($item['name'])->{$item['interval']}()->when(function () use($item) {
                        $enabled = (isset($item['enabled']) && ($item['enabled'] === true)) ? true : false;

                        return $enabled;
                    });
                } catch (\Exception $exception) {

                }
            }
        }

        $schedule->command('config:clear')->daily();
    }
}
