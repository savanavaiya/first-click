<?php

namespace App\Console;

use App\Models\Importdata;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->call(function () {
        //     $scdatas = Importdata::all();
        //     foreach($scdatas as $scdata)
        //     {
        //         $scdata->status = 'Not Updated';
        //         $scdata->save();
        //     }
        // })->weeklyOn(1, '23:00');

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
