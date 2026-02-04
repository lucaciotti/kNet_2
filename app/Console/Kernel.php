<?php

namespace knet\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use knet\Jobs\FetchReportToSend;

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
        // $schedule->command('inspire')
        //          ->hourly();
        // $schedule->job(new FetchReportToSend('weekly'), 'jobs')->hourlyAt(17);
        $schedule->job(new FetchReportToSend('weekly'), 'jobs')->weeklyOn(6, '8:00');
        $schedule->job(new FetchReportToSend('monthly'), 'jobs')->monthlyOn(6, '8:00');
        $schedule->job(new FetchReportToSend('quarterly'), 'jobs')->quarterly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
