<?php

namespace App\Components\Process;

use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\FbMessage;

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

    public static function textMessage($bot_message_reply, $entry, $person_id, $user_fb_page, $access_token)
    {
        $mid = isset($entry[0]['messaging'][0]['message']['mid']) ? $entry[0]['messaging'][0]['message']['mid'] : null;

        $array_postback = [
            'payload' => isset($entry[0]['messaging'][0]['postback']['payload']) ? $entry[0]['messaging'][0]['postback']['payload'] : null,
            'timestamp' => isset($entry[0]['messaging'][0]['timestamp']) ? $entry[0]['messaging'][0]['timestamp'] : null,
            'conversation_id' => $user_fb_page->fbConversation->conversation_id,
            'status' => 0
        ];

        if ($mid !== null) {
            $fb_message = FbMessage::where(['mid' => $mid, 'status' => 0])->first();
            if (isset($fb_message)) {
                return;
            }
        } else {
            $fb_message = FbMessage::where(array_merge(['status' => 0], $array_postback))->first();
            if (isset($fb_message)) {
                return;
            }
        }

        $time = time();
        $data = [
            'id' => $person_id,
            'text' => $bot_message_reply->text
        ];
        $is_send = true;
        if ($bot_message_reply->type_notify === "timer") {
            if ($bot_message_reply->begin_time_active) {
                if ((int)$bot_message_reply->begin_time_active > $time) {
                    $is_send = false;
                }
            }
            if ($bot_message_reply->end_time_active) {
                if ((int)$bot_message_reply->end_time_active < $time) {
                    $is_send = false;
                }
            }
            if ($is_send) {
                $date_now = date('Y-m-d');
                $date_min = $date_now . ' 00:00:00';
                $str_to_time_min = strtotime($date_min);
                if (($str_to_time_min + (int)$bot_message_reply->begin_time_open) > $time) {
                    $is_send = false;
                }
                if (($str_to_time_min + (int)$bot_message_reply->end_time_open) < $time) {
                    $is_send = false;
                }
            }
        }

        if ($is_send) {
            if (isset($bot_message_reply->text)) {
                Facebook::post($access_token, 'me/messages', Message::textMessage($data));
            }
        }
        if ($mid !== null) {
            UpdateOrCreate::fbMessage(['mid' => $mid, 'status' => 0]);
        } else {
            UpdateOrCreate::fbMessage(array_merge(['status' => 0], $array_postback));
        }
    }
}
