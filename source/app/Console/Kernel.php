<?php

namespace App\Console;

use App\Console\Commands\Facebook\CommandAddUserPage;
use App\Console\Commands\CommandTest;
use App\Console\Commands\Facebook\CommandMessaging;
use App\Console\Commands\Facebook\CommandSendMessagePage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        CommandAddUserPage::class,
        CommandMessaging::class,
        CommandTest::class,
        CommandSendMessagePage::class
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }


    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
