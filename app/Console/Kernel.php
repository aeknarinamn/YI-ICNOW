<?php

namespace YellowProject\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use YellowProject\Campaign;
use YellowProject\Campaign\CoreFunction;
use YellowProject\JobScheduleFunction;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            // \Log::debug('schedule-campaign-run');
            Campaign::scheduleSentMessage();
        })->everyMinute();

        $schedule->call(function () {
            JobScheduleFunction::checkFunctionDownload();
        })->everyMinute();

        $schedule->call(function () {
            JobScheduleFunction::refreshToken();
        })->dailyAt('00:00');

        $schedule->call(function () {
            CoreFunction::setDataTaskToSendMessage();
        })->everyMinute();

        $schedule->call(function () {
            Campaign::setScheduleRecurringData();
        })->dailyAt('00:00');

        $schedule->call(function () {
            JobScheduleFunction::checkMiniNoResponse();
        })->everyMinute();

        $schedule->call(function () {
            JobScheduleFunction::getMiniUser();
        })->dailyAt('00:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
