<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;

use App\Model\BotElementButton;
use App\Model\BotMessageReply;
use App\Model\BotPayloadElement;
use App\Model\FbProcess;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bot_message_reply = BotMessageReply::first();
        if ($bot_message_reply->attachment_type === "generic") {
            $bot_payload_elements = BotPayloadElement::wherebot_message_reply_id($bot_message_reply->_id)->get();
            foreach ($bot_payload_elements as $value) {
                $bot_element_buttons = BotElementButton::wherebot_payload_element_id($value->_id)->get();
                $buttons = [];
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
                            'title' => 'postback',
                            'payload' => $button->payload
                        ]]);
                    }
                }
                $element = [
                    "title" => $value->title,
                    "image_url" => $value->image_url,
                    "subtitle" => $value->subtitle,
                ];
                dd($element = array_merge($element, ['button' => $buttons]));
                dd([
                    "message" => [
                        "attachment" => [
                            "type" => "template",
                            "payload" => [
                                "template_type" => "generic",
                                "elements" => [
                                    [
                                        "title" => $value->title,
                                        "image_url" => $value->image_url,
                                        "subtitle" => $value->subtitle,
//                                                    "default_action" => [
//                                                        "type" => "web_url",
//                                                        "url" => "https://gamota.com/games",
//                                                        "messenger_extensions" => false,
//                                                        "webview_height_ratio" => "tall"
//                                                    ],
                                        "buttons" => [
                                            [
                                                "type" => "web_url",
                                                "url" => "https://gamota.com/",
                                                "title" => "View Website"
                                            ],
                                            [
                                                "type" => "postback",
                                                "title" => "Start Chatting",
                                                "payload" => "DEVELOPER_DEFINED_PAYLOAD"
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]);
            }
        }


        dd($bot_message_reply);

        return view('pages.event.index');
    }
}
