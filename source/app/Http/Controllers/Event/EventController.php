<?php

namespace App\Http\Controllers\Event;

use App\Components\Facebook\Facebook;
use App\Http\Controllers\Controller;

use App\Model\BotElementButton;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\BotPayloadElement;
use App\Model\BotQuickReply;
use App\Model\FbMessage;
use App\Model\FbProcess;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        dd(BotMessageReply::where('text', 'LIKE', "%h%")->limit(10)->get());
        dd('error');
        $data = ["messages" => [
            [
                "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                        "elements" => [
                            [
                                "title" => "Welcome to Our Marketplace!",
                                "image_url" => "https://www.facebook.com/jaspers.png",
                                "subtitle" => "Fresh fruits and vegetables. Yum.",
                                "buttons" => [
                                    [
                                        "type" => "web_url",
                                        "url" => "https://www.jaspersmarket.com",
                                        "title" => "View Website"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
        ];
        $access_token = (Page::wherefb_page_id(Auth::user()->page_selected)->first()->access_token);
        $message_creatives = Facebook::post($access_token, 'me/message_creatives', $data);
        $message_creative_id = null;
        if ($message_creatives) {
            $message_creative_id = isset($message_creatives->getDecodedBody()['message_creative_id']) ? $message_creatives->getDecodedBody()['message_creative_id'] : null;
        }
        if ($message_creative_id !== null) {
            $data = [
                "message_creative_id" => $message_creative_id,
                "notification_type" => "SILENT_PUSH",
                "messaging_type" => "MESSAGE_TAG",
                "tag" => "NON_PROMOTIONAL_SUBSCRIPTION"
            ];
            dd(Facebook::post($access_token, 'me/broadcast_messages', $data));
        }

        dd(1);
        dd(FbMessage::orderby('created_at', 'DESC')->first());
        $bot_message_reply = BotMessageReply::orderby('created_at', 'DESC')->get();
        dd(BotMessageHead::all());
        if ($bot_message_reply->type_message === "quick_replies") {
            $bot_quick_replies = BotQuickReply::wherebot_message_reply_id($bot_message_reply->_id)->get();
            $elements = [];
            $quick_replies = [];
            foreach ($bot_quick_replies as $key => $value) {
                if ($key >= 8) {
                    break;
                }
                if ($value->content_type === 'text') {
                    if ($value->title) {
                        $quick_replies[] = [
                            'content_type' => 'text',
                            'title' => $value->title,
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
                    "text" => $bot_message_reply->text,
                    'quick_replies' => $quick_replies
                ]];

        }
        if (isset($message)) {
            $data = array_merge([
                'recipient' => [
                    'id' => '123'
                ]
            ], $message);
        }

        $data = [
            'messaging_type' => 'RESPONSE',
            "message" => [
                "text" => 'Pick a color',
                'quick_replies' => [
                    [
                        "content_type" => "text",
                        "title" => "Red",
                        "payload" => "<POSTBACK_PAYLOAD>",
                        "image_url" => "http://example.com/img/red.png"
                    ], [
                        "content_type" => "text",
                        "title" => "Green",
                        "payload" => "<POSTBACK_PAYLOAD>",
                        "image_url" => "http://example.com/img/green.png"
                    ]
                ]
            ]];
        $data = array_merge([
            'recipient' => [
                'id' => '$person_id'
            ]
        ], $data);
        if (isset($data)) {
            dd($data);
        }


//        dd($bot_message_reply);

        return view('pages.event.index');
    }
}
