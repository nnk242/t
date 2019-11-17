<?php

namespace App\Console\Commands\Facebook;

use App\Components\Console\Notify;
use App\Components\Facebook\ProcessDataMessaging;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\Page;
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
                if (isset($data['entry'][0]['id'])) {
                    $fb_page_id = $data['entry'][0]['id'];
                    $page = Page::wherefb_page_id($fb_page_id)->first();
                    $this->info(Notify::notify('Page id: ' . $fb_page_id));
                    if (isset($page)) {
                        $this->info(Notify::notify('Run data: ' . $value->data));
                        if (isset($data[0]['messaging'])) {
                            $sender_id = isset($data[0]['messaging'][0]['sender']['id']) ? $data[0]['messaging'][0]['sender']['id'] : null;
                            $recipient_id = isset($data[0]['messaging'][0]['recipient']['id']) ? $data[0]['messaging'][0]['recipient']['id'] : null;

                            #### Get user fb page
                            if ($sender_id === $fb_page_id) {
                                $person_id = $recipient_id;
                            } else {
                                $person_id = $sender_id;
                            }
                            ## run process
                            ProcessDataMessaging::handle($data, $person_id, $sender_id, $recipient_id);
                        }
                    }
                }
                UpdateOrCreate::fbProcess(['_id' => $value->_id, 'status' => 0]);
            } catch (Exception $exception) {
                UpdateOrCreate::fbProcess(['_id' => $value->_id, 'status' => 3, 'code' => $exception->getCode(), 'message' => $exception->getMessage()]);
                $this->info(Notify::notify($exception->getCode() . ' ' . $exception->getMessage()));
            }
        }
        $this->info(Notify::notify('Final CommandMessaging'));
    }
}
