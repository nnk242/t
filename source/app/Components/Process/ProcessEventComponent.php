<?php

namespace App\Components\Process;

use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Jobs\Facebook\FacebookSendMessage;
use App\Model\BotElementButton;
use App\Model\BotMessageReply;
use App\Model\BotPayloadElement;
use App\Model\BotQuickReply;

class ProcessEventComponent
{
    private static function timeBeginActive($begin_time_active)
    {
        $is_send = true;
        $time = time();

        if ($begin_time_active) {
            if ((int)$begin_time_active > $time) {
                $is_send = false;
            }
        }
        return $is_send;
    }

    private static function timeEndActive($end_time_active)
    {
        $is_send = true;
        $time = time();
        if ($end_time_active) {
            if ((int)$end_time_active < $time) {
                $is_send = false;
            }
        }
//        if ($is_send) {
//            $date_now = date('Y-m-d');
//            $date_min = $date_now . ' 00:00:00';
//            $str_to_time_min = strtotime($date_min);
//            if (($str_to_time_min + (int)$begin_time_open) > $time) {
//                $is_send = false;
//            }
//            if (($str_to_time_min + (int)$end_time_open) < $time) {
//                $is_send = false;
//            }
//        }
        return $is_send;
    }

    private static function timeOpen($begin_time_open, $end_time_open)
    {
        $is_send = true;
        $time = time();
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

    public static function event($bot_message_head, $person_id, $access_token)
    {
        $is_time_begin_active = self::timeBeginActive($bot_message_head->begin_time_active);
        $is_end_time_active = self::timeEndActive($bot_message_head->end_time_active);
        if (!$is_time_begin_active && $bot_message_head->text_error_begin_time_active_id) {
            ProcessMessageComponent::message(BotMessageReply::where_id($bot_message_head->text_error_begin_time_active_id)->get(), $person_id, $access_token);
            return;
        }

        if (!$is_end_time_active && $bot_message_head->text_error_end_time_active_id) {
            ProcessMessageComponent::message(BotMessageReply::where_id($bot_message_head->text_error_end_time_active_id)->get(), $person_id, $access_token);
            return;
        }

        if (!self::timeOpen($bot_message_head->begin_time_active, $bot_message_head->end_time_active) && $bot_message_head->text_error_time_open_id) {
            ProcessMessageComponent::message(BotMessageReply::where_id($bot_message_head->text_error_time_open_id)->get(), $person_id, $access_token);
            return;
        }

        if ($bot_message_head->text_success_id) {
            ProcessMessageComponent::message(BotMessageReply::where_id($bot_message_head->text_success_id)->get(), $person_id, $access_token);
            return;
        }
    }
}
