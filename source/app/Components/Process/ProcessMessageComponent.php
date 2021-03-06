<?php

namespace App\Components\Process;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Model\BotElementButton;
use App\Model\BotPayloadElement;
use App\Model\BotQuickReply;
use App\Model\FbUserEvent;
use App\Model\FbUserPage;

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

    private static function elementButton($bot_payload_element_id, $buttons = [])
    {
        $bot_element_buttons = BotElementButton::wherebot_payload_element_id($bot_payload_element_id)->get();
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
        return $buttons;
    }

    private static function templateTypeMedia($_id, $media_type, $url, $person_id, $access_token)
    {
        try {
            $buttons = self::elementButton($_id);
            $message_ = [
                "message" => [
                    "attachment" => [
                        "type" => "template",
                        "payload" => [
                            "template_type" => "media",
                            "elements" => [
                                [
                                    "media_type" => $media_type,
                                    "url" => $url,
                                    "buttons" => count($buttons) ? $buttons : null
                                ]
                            ]
                        ]
                    ]
                ]
            ];
            $data = Message::template(['id' => $person_id, 'message' => $message_]);
            Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
            Facebook::post($access_token, 'me/messages', $data);
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    private static function getName($fb_page_id, $person)
    {
        $fb_user_page = FbUserPage::wherem_page_user_id($fb_page_id . '_' . $person)->first();
        if (isset($fb_user_page)) {
            if ($fb_user_page->name) {
                return $fb_user_page->name;
            } else {
                return $fb_user_page->first_name . ' ' . $fb_user_page->last_name;
            }
        }
        return ':name:';
    }

    public static function textMessage($bot_message_reply, $person_id, $access_token, $gift = false)
    {
        $data = [
            'id' => $person_id,
            'text' => TextComponent::replaceText($bot_message_reply->text, self::getName($bot_message_reply->fb_page_id, $person_id), $gift ? $gift : ':gift:')
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

    public static function messageTemplate($bot_message_reply, $person_id, $access_token, $gift = false)
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
                if ($value->template_type === 'generic') {
                    $default_action = null;
                    $is_send = false;
                    if ($i >= 10) {
                        $default_action = null;
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
                    $buttons = self::elementButton($value->_id);
                    if ($value->title && $value->image_url && $value->subtitle) {
                        $element = [
                            "title" => TextComponent::replaceText($value->title, self::getName($bot_message_reply->fb_page_id, $person_id), $gift ? $gift : ':gift:'),
                            "image_url" => $value->image_url,
                            "subtitle" => TextComponent::replaceText($value->subtitle, self::getName($bot_message_reply->fb_page_id, $person_id), $gift ? $gift : ':gift:'),
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
                                $data = Message::template(['id' => $person_id, 'message' => $message]);
                            }

                            if (isset($data)) {
                                $default_action = null;
                                Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                                Facebook::post($access_token, 'me/messages', $data);
                                $message = [];
                            }
                        }
                    }
                } elseif ($value->template_type === 'button') {
                    $buttons = self::elementButton($value->_id);
                    $message_ = [
                        "message" => [
                            "attachment" => [
                                "type" => "template",
                                "payload" => [
                                    "template_type" => "button",
                                    "text" => TextComponent::replaceText($value->text, self::getName($bot_message_reply->fb_page_id, $person_id), $gift ? $gift : ':gift:'),
                                    'buttons' => count($buttons) ? $buttons : null
                                ]
                            ]
                        ]
                    ];
                    $data = Message::template(['id' => $person_id, 'message' => $message_]);
                    Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                    Facebook::post($access_token, 'me/messages', $data);
                } elseif ($value->template_type === 'media') {
                    self::templateTypeMedia($value->_id, $value->media_type, $value->url, $person_id, $access_token);
                }
            }

            if (isset($message)) {
                $data = Message::template(['id' => $person_id, 'message' => $message]);
            }
            if (isset($data)) {
                if ($data) {
                    Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                    Facebook::post($access_token, 'me/messages', $data);
                }
            }

        }
    }

    public static function quickReply($bot_message_reply, $person_id, $access_token, $gift = false)
    {
        $is_send = true;
        if ($bot_message_reply->type_notify === "timer") {
            $is_send = self::timer($bot_message_reply->begin_time_active, $bot_message_reply->end_time_active,
                $bot_message_reply->begin_time_open, $bot_message_reply->end_time_open);
        }
        if ($is_send) {
            $quick_replies = [];
            $bot_quick_replies = BotQuickReply::wherebot_message_reply_id($bot_message_reply->_id)->get();
            foreach ($bot_quick_replies as $key => $value) {
                if ($key >= 8) {
                    break;
                }
                if ($value->content_type === 'text') {
                    if ($value->title) {
                        $quick_replies[] = [
                            'content_type' => 'text',
                            'title' => TextComponent::replaceText($value->title, self::getName($bot_message_reply->fb_page_id, $person_id), $gift ? $gift : ':gift:'),
                            'payload' => $value->payload,
                            'image_url' => $value->image_url
                        ];

                    }
                } else {
                    $quick_replies[] = [
                        'content_type' => $value->content_type,
                        'image_url' => $value->image_url
                    ];
                }
            }
            $message = [
                'messaging_type' => 'RESPONSE',
                "message" => [
                    "text" => TextComponent::replaceText($bot_message_reply->text, self::getName($bot_message_reply->fb_page_id, $person_id), $gift ? $gift : ':gift:'),
                    'quick_replies' => $quick_replies
                ]];
            $data = array_merge([
                'recipient' => [
                    'id' => $person_id
                ]
            ], $message);

            if (isset($data)) {
                Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                Facebook::post($access_token, 'me/messages', $data);
            }
        }
    }

    public static function message($bot_message_replies, $person_id, $access_token, $gift = false)
    {
        foreach ($bot_message_replies as $bot_message_reply) {
            $type_message = $bot_message_reply->type_message;
            if ($type_message === 'text_messages' && $bot_message_reply->text) {
                self::textMessage($bot_message_reply, $person_id, $access_token, $gift);
            } elseif ($type_message === 'assets_attachments') {
                self::assetAttachment($bot_message_reply, $person_id, $access_token);
            } elseif ($type_message === 'message_templates') {
                self::messageTemplate($bot_message_reply, $person_id, $access_token, $gift);
            } elseif ($type_message === "quick_replies" && $bot_message_reply->text) {
                self::quickReply($bot_message_reply, $person_id, $access_token, $gift);
            }
        }
    }
}
