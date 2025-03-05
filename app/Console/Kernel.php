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
           //Commands\CronApiPendientes::class, 
           //Commands\CronApiDispensados::class,
           //Commands\CronApiDispensadosDolor::class,
           //Commands\CronApiPendientesDolor::class,
           //Commands\CronApiDispensadosSaludMental::class,
           //Commands\CronApiPendientesSaludMental::class,
           //Commands\CronApiDispensadosEmcali::class,
           Commands\CronApiDispensadosSos::class,
           
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
{
    //$schedule->command('cron:api_pendientes')->cron('0 */4 * * *');
    //$schedule->command('cron:api_dispensados')->cron('0 */3 * * *');
    //$schedule->command('cron:api_dispensadosdolor')->cron('0 */3 * * *');
    //$schedule->command('cron:api_pendientesdolor')->cron('0 */4 * * *');
    //$schedule->command('cron:api_dispensadossaludmental')->cron('0 */3 * * *');
    //$schedule->command('cron:api_pendientessaludmental')->cron('0 */4 * * *');
    //$schedule->command('cron:api_dispensadosemcali')->cron('*/30 * * * *');
    $schedule->command('cron:api_dispensadossos')->cron('*/10 * * * *');
    
    
    
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
