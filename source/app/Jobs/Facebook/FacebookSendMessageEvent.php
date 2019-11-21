<?php

namespace App\Jobs\Facebook;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Process\ProcessMessageComponent;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FacebookSendMessageEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        $text = $this->data['text'];
        $person_id = $this->data['person_id'];
        $entry = $this->data['entry'];
        $user_fb_page = $this->data['user_fb_page'];
//        dd($user_fb_page);
        if (isset($user_fb_page)) {
            $access_token = $user_fb_page->page->access_token;

            $bot_message_heads = BotMessageHead::wherefb_page_id($user_fb_page->fb_page_id)->wheretype('event')->get();
//            foreach ($bot_message_heads as $bot_message_head) {
//                if (!TextComponent::passMessage($text, $bot_message_head->text)) {
//                    continue;
//                }
//                $bot_message_replies = BotMessageReply::wherebot_message_head_id($bot_message_head->id)->get();
//                foreach ($bot_message_replies as $bot_message_reply) {
//                    if ($bot_message_reply->type_message === 'text_messages') {
//                        ProcessMessageComponent::textMessage($bot_message_reply, $entry, $person_id, $user_fb_page, $access_token);
//                    }
//                }
//
//                if (isset($data)) {
//                    Facebook::post($access_token, 'me/messages', $data);
//                }
//            }
        }
    }
}
