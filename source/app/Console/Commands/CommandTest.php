<?php

namespace App\Console\Commands;

use App\Components\Facebook;
use App\Model\FbConversation;
use App\Model\UserFbPage;
use App\Model\UserPage;
use Illuminate\Console\Command;

class CommandTest extends Command
{
    protected $signature = 'command:Test';

    protected $description = 'Command add user page';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->info('[' . date('Y-m-d H:i:s') . ']' . PHP_EOL);
            sleep(1);
        }
    }
}
