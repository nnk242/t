<?php

namespace App\Components\Process;

use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\BotElementButton;
use App\Model\BotPayloadElement;
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

    public static function textMessage($bot_message_reply, $person_id, $access_token)
    {
        $data = [
            'id' => $person_id,
            'text' => $bot_message_reply->text
        ];
        $is_send = true;
        if ($bot_message_reply->type_notify === "timer") {
            $is_send = self::timer($bot_message_reply->begin_time_active, $bot_message_reply->end_time_active,
                $bot_message_reply->begin_time_open, $bot_message_reply->end_time_open);
        }

        if ($is_send) {
            Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
            Facebook::post($access_token, 'me/messages', Message::textMessage($data));
        }
    }

    public static function assetAttachment($bot_message_reply, $person_id, $access_token)
    {
        $data = [
            'id' => $person_id,
            'attachment_type' => $bot_message_reply->attachment_type,
            'attachment_payload_url' => $bot_message_reply->attachment_payload_url
        ];
        $is_send = true;
        if ($bot_message_reply->type_notify === "timer") {
            $is_send = self::timer($bot_message_reply->begin_time_active, $bot_message_reply->end_time_active,
                $bot_message_reply->begin_time_open, $bot_message_reply->end_time_open);
        }

        if ($is_send) {
            Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
            Facebook::post($access_token, 'me/messages', Message::assetAttachment($data));
        }
    }

    public static function messageTemplate($bot_message_reply, $person_id, $access_token)
    {

        $is_send = true;
        if ($bot_message_reply->type_notify === "timer") {
            $is_send = self::timer($bot_message_reply->begin_time_active, $bot_message_reply->end_time_active,
                $bot_message_reply->begin_time_open, $bot_message_reply->end_time_open);
        }

        if ($is_send) {
            $bot_payload_elements = BotPayloadElement::wherebot_message_reply_id($bot_message_reply->_id)->orderby('group', 'DESC')->get();
            $elements = [];
            $i = 0;
            foreach ($bot_payload_elements as $value) {
                $bot_element_buttons = BotElementButton::wherebot_payload_element_id($value->_id)->get();
                $default_action = null;
                $buttons = [];
                $is_send = false;
                if ($i >= 10) {
                    $default_action = null;
                    $buttons = [];
                    $i = 0;
                    $is_send = true;
                }
                if (isset($value->default_action_url)) {
                    $default_action = [
                        "type" => "web_url",
                        "url" => $value->default_action_url,
                        "messenger_extensions" => false,
                        "webview_height_ratio" => $value->default_action_messenger_webview_height_ratio,
                    ];
                }
                foreach ($bot_element_buttons as $button) {
                    if ($button->type === 'web_url') {
                        $buttons = array_merge($buttons, [[
                            'title' => $button->title,
                            'type' => 'web_url',
                            'url' => $button->url
                        ]]);
                    } elseif ($button->type === 'phone_number') {
                        $buttons = array_merge($buttons, [[
                            'title' => $button->title,
                            'type' => 'phone_number',
                            'payload' => $button->payload
                        ]]);
                    } elseif ('postback') {
                        $buttons = array_merge($buttons, [[
                            'title' => $button->title,
                            'type' => 'postback',
                            'payload' => $button->payload
                        ]]);
                    }
                }
                $element = [
                    "title" => $value->title,
                    "image_url" => $value->image_url,
                    "subtitle" => $value->subtitle,
                ];

                $elements = array_merge($elements, [array_merge($element, ['buttons' => count($buttons) ? $buttons : null], ['default_action' => $default_action])]);
                $message = [
                    "message" => [
                        "attachment" => [
                            "type" => "template",
                            "payload" => [
                                "template_type" => "generic",
                                "elements" => $elements
                            ]
                        ]
                    ]
                ];
                $i++;
                if ($is_send) {
                    if (isset($message)) {
                        $data = dd(Message::templateGeneric(['id' => $person_id, 'message' => $message]));
                    }

                    if (isset($data)) {
                        $default_action = null;
                        Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                        Facebook::post($access_token, 'me/messages', $data);
                        $message = [];
                    }
                }
            }


            if (isset($message)) {
                $data = Message::templateGeneric(['id' => $person_id, 'message' => $message]);
            }

            if (isset($data)) {
                if ($data) {
                    $default_action = null;
                    Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                    Facebook::post($access_token, 'me/messages', $data);
                }
            }

        }
    }

    public static function message($bot_message_replies, $person_id, $access_token)
    {
        foreach ($bot_message_replies as $bot_message_reply) {
            $type_message = $bot_message_reply->type_message;
            if ($type_message === 'text_messages') {
                if ($bot_message_reply->text) {
                    self::textMessage($bot_message_reply, $person_id, $access_token);
                }
            } elseif ($type_message === 'assets_attachments') {
                self::assetAttachment($bot_message_reply, $person_id, $access_token);
            } elseif ($type_message === 'message_templates') {
                self::messageTemplate($bot_message_reply, $person_id, $access_token);
            }
        }
    }
}
