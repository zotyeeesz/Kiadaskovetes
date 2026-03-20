<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Árfolyamok frissítése napi egyszer (reggel 6:00-kor)
        $schedule->command('app:update-exchange-rates')
            ->dailyAt('06:00')
            ->runInBackground();

        // Opcionálisan: nyomkövetéshez naplózz minden futást
        // ->onSuccess(function () {
        //     \Log::info('Árfolyamok sikeresen frissítve');
        // })
        // ->onFailure(function () {
        //     \Log::error('Árfolyam frissítés sikertelen');
        // });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
