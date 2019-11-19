<?php

namespace App\Components\Process;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;

class ProcessMessageComponent
{
    private static function timer($begin_time_active, $end_time_active, $begin_time_open, $end_time_open)
    {
        $is_send = true;
        $time = time();

        if ($begin_time_active) {
            if ((int)$begin_time_active > $time) {
                $is_send = false;
            }
        }
        if ($end_time_active) {
            if ((int)$end_time_active < $time) {
                $is_send = false;
            }
        }
        if ($is_send) {
            $date_now = date('Y-m-d');
            $date_min = $date_now . ' 00:00:00';
            $str_to_time_min = strtotime($date_min);
            if (($str_to_time_min + (int)$begin_time_open) > $time) {
                $is_send = false;
            }
            if (($str_to_time_min + (int)$end_time_open) < $time) {
                $is_send = false;
            }
        }
        return $is_send;
    }

    public static function index($access_token, $fb_page_id, $text, $person_id)
    {
        $bot_message_heads = BotMessageHead::wherefb_page_id($fb_page_id)->get();
        foreach ($bot_message_heads as $bot_message_head) {
            if (!TextComponent::passMessage($text, $bot_message_head->text)) {
                continue;
            }
            $bot_message_replies = BotMessageReply::wherebot_message_head_id($bot_message_head->id)->get();
            foreach ($bot_message_replies as $bot_message_reply) {
                $is_send = true;
                if ($bot_message_reply->type_message === 'text_messages') {
                    $data = [
                        'id' => $person_id,
                        'text' => $bot_message_reply->text
                    ];
                    if ($bot_message_reply->type_notify === "timer") {
                        $is_send = self::timer($bot_message_reply->begin_time_active,
                            $bot_message_reply->end_time_active, $bot_message_reply->begin_time_open,
                            $bot_message_reply->end_time_open);
                    }

                    if ($is_send) {
                        if (isset($bot_message_reply->text)) {
                            Facebook::post($access_token, 'me/messages', Message::textMessage($data));
                        }
                    }
                }
            }
        }
    }
}
