<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;

use App\Model\BotElementButton;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\BotPayloadElement;
use App\Model\BotQuickReply;
use App\Model\FbMessage;
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
