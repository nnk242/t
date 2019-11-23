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
        dd($bot_message_reply);
        if ($bot_message_reply->attachment_type === "template") {
            $bot_payload_elements = BotPayloadElement::wherebot_message_reply_id($bot_message_reply->_id)->orderby('group', 'DESC')->get();
            $elements = [];
            $i = 0;
            foreach ($bot_payload_elements as $value) {
                $default_action = null;
                if (isset($value->default_action_url)) {
                    $default_action = [
                        "type" => "web_url",
                        "url" => $value->default_action_url,
                        "messenger_extensions" => false,
                        "webview_height_ratio" => $value->default_action_messenger_webview_height_ratio,
                    ];
                }
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
                            'title' => $button->title,
                            'type' => 'postback',
                            'payload' => $button->payload
                        ]]);
                    }
                }

                if (count($buttons)) {
                    $element = [
                        "title" => $value->title,
                        "image_url" => $value->image_url,
                        "subtitle" => $value->subtitle,
                    ];
                    $elements = array_merge($elements, [array_merge($element, ['buttons' => $buttons],
                        ['default_action' => $default_action])]);
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
                    if (isset($default_action)) {
                    }
                }

            }
        }
        if (isset($message)) {
            dd($message);
            $data = array_merge([
                'recipient' => [
                    'id' => '123'
                ]
            ], $message);
        }
        if (isset($data)) {
            dd($data);
        }


//        dd($bot_message_reply);

        return view('pages.event.index');
    }
}
