<?php

namespace App\Console\Commands\Facebook;

use App\Components\Console\Notify;
use App\Components\Facebook\ProcessDataMessaging;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use Illuminate\Console\Command;
use App\Model\FbProcess;
use Mockery\Exception;

class CommandMessaging extends Command
{
    protected $signature = 'command:FbMessaging {--status=}';

    protected $description = 'Run every';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info(Notify::notify('Start CommandMessaging'));
        $status = $this->option('status');
        if (isset($status)) {
            $status = (int)$status;
        } else {
            $status = 1;
        }
        $fb_process = FbProcess::wherestatus($status)->get();
        foreach ($fb_process as $value) {
            try {
                $data = json_decode($value->data, true);
                ProcessDataMessaging::index($data);
                UpdateOrCreate::fbProcess(['_id' => $value->_id, 'status' => 0]);
            } catch (Exception $exception) {
                UpdateOrCreate::fbProcess(['_id' => $value->_id, 'status' => 3, 'code' => $exception->getCode(), 'message' => $exception->getMessage()]);
                $this->info(Notify::notify($exception->getCode() . ' ' . $exception->getMessage()));
            }
        }
        $this->info(Notify::notify('Final CommandMessaging'));
    }
}
