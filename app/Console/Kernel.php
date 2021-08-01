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

    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {

        // https://crontab.guru/
        // Every 12 Hours... (0 */12 * * *)
        // Every Sunday... (0 0 * * SUN)
        // $schedule->command('inspire')->hourly();
        // $schedule->command('orders:refresh')->cron('0 */12 * * *');

        // Crontab entry in linux OS
        // command-> crontab -e
        // * * * * * cd /var/www/public_html/latest.orderstalker.com && php artisan schedule:run >> /dev/null 2>&1
        $fileName = 'order_refresh.txt';
        $schedule->command('orders:refresh')
            ->cron('0 */12 * * *')
//            ->everyMinute()
            ->appendOutputTo(CRON_OUTPUT_BASE_PATH . '' . $fileName);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
