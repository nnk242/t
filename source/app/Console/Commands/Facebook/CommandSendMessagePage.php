<?php

namespace App\Console\Commands\Facebook;

use App\Components\Console\Notify;
use App\Components\Facebook\DataMessaging;
use App\Components\Process\ProcessMessageComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\BotMessageReply;
use App\Model\BroadcastMessenger;
use App\Model\BroadcastPage;
use App\Model\FbUserPage;
use Illuminate\Console\Command;
use App\Model\FbProcess;
use Mockery\Exception;

class CommandSendMessagePage extends Command
{
    protected $signature = 'command:SendMessagePage';

    protected $description = 'Run every';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info(Notify::notify('Start SendMessagePage'));

        $broadcast_messengers = BroadcastMessenger::wherestatus(1)->get();
        $time = time();

        foreach ($broadcast_messengers as $broadcast_messenger) {
            $this->info(Notify::notify('Run broadcast messengers with id ' . $broadcast_messenger->_id));
            $is_send = true;
            if ($broadcast_messenger->broadcastPages->count()) {
                if ($broadcast_messenger->begin_time_active) {
                    if ($broadcast_messenger->begin_time_active > $time || $broadcast_messenger->end_time_active < $time) {
                        $is_send = false;
                    }
                }
                if ($is_send) {
                    foreach ($broadcast_messenger->broadcastPages as $broadcast_page) {
                        $page = $broadcast_page->page;
                        if ($page->count()) {
                            $this->info(Notify::notify('Run broadcast messengers with fb page id ' . $page->fb_page_id));
                            $access_token = $page->access_token;
                            $fb_page_id = $broadcast_page->fb_page_id;
                            $bot_message_reply = $broadcast_messenger->botMessageReply;
                            if ($bot_message_reply->count()) {
                                $bot_message_replies = BotMessageReply::where_id($bot_message_reply->_id)->get();
                                $fb_user_pages = FbUserPage::wherefb_page_id($fb_page_id)->get();
                                foreach ($fb_user_pages as $fb_user_page) {
                                    $broadcast_messenger_ = BroadcastMessenger::find($broadcast_messenger->_id);
                                    if (isset($broadcast_messenger_)) {
                                        if ($broadcast_messenger_->status !== 1) {
                                            break;
                                        }
                                    } else {
                                        break;
                                    }
                                    $this->info(Notify::notify('Run broadcast messengers with user fb id ' . $fb_user_page->user_fb_id));
                                    ProcessMessageComponent::message($bot_message_replies, $fb_user_page->user_fb_id, $access_token);
                                }
                            }
                        }
                    }
                }
            }

            $broadcast_messenger_ = BroadcastMessenger::find($broadcast_messenger->_id);
            if ($broadcast_messenger_->count()) {
                $broadcast_messenger_->update(['status' => 0]);
            }
        }
        $this->info(Notify::notify('Final CommandMessaging'));
    }
}
