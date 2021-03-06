<?php namespace LMK\Console;

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
        Commands\FetchData::class,
        Commands\ListDataSources::class,
        Commands\ListActivityDetails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Update fitness data
        $schedule->command('lmk:fetch-data today')->hourly();
        $schedule->command('lmk:fetch-data yesterday')->twiceDaily(3, 13);

        // Backup database and clean old backups
        $schedule->command('backup:run --only-db')->daily();
        $schedule->command('backup:clean')->daily();
    }
}
