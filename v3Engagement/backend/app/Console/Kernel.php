<?php

namespace App\Console;

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
        Commands\CreateCompanySegmentsCacheCommand::class,
        Commands\RateLimitingCommand::class,
        Commands\exportUsers::class,
        Commands\SegmentsDataCacheCommand::class,
        Commands\CampaignQueueUsersCommand::class,
        Commands\DispatchCampaignQueuesCommand::class,
        Commands\CampaignSegmentCommand::class,
        //Commands\DashboardStatsCacheCommand::class,
        Commands\AppUsersCacheCleanCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        /**
         * @var $schedule Schedule
         */
        $schedule->command('campaign:create_queues')
            ->everyMinute();
        $schedule->command('campaign:campaign:dispatch')
            ->everyMinute();
        $schedule->command('segment:cache')
            ->everyThirtyMinutes();
        $schedule->command('campaign:segment')
            ->everyMinute();
        $schedule->command('horizon:snapshot')
            ->everyFiveMinutes();
        $schedule->command('app_users:cache:clean')
            ->dailyAt('12:00');
        //$schedule->command('dashboard:stats')
        //    ->everyFiveMinutes();
        //$schedule->command('dashboard:stats')
        //    ->hourly();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
