<?php

namespace App\Jobs\Facebook;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Process\ProcessEventComponent;
use App\Components\Process\ProcessMessageComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\FbMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FacebookSendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public $timeout = 300;

    public function retryUntil()
    {
        return now()->addSeconds(1);
    }

    public function handle()
    {
        $person_id = $this->data['person_id'];
        $entry = $this->data['entry'];
        $user_fb_page = $this->data['user_fb_page'];

        if (isset($user_fb_page)) {
            ###
            $messaging = isset($entry[0]['messaging']) ? $entry[0]['messaging'] : null;
            $mid = isset($messaging[0]['message']['mid']) ? $messaging[0]['message']['mid'] : null;
            $text = isset($messaging[0]['message']['text']) ? $messaging[0]['message']['text'] :
                (isset($messaging[0]['postback']['title']) ? $messaging[0]['postback']['title'] : null);
            $quick_reply_payload = isset($messaging[0]['message']['quick_reply']['payload']) ? $messaging[0]['message']['quick_reply']['payload'] : null;
            $array_postback = [
                'payload' => isset($messaging[0]['postback']['payload']) ? $messaging[0]['postback']['payload'] : null,
                'timestamp' => isset($messaging[0]['timestamp']) ? $messaging[0]['timestamp'] : null,
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
            ###
            $access_token = $user_fb_page->page->access_token;

            $bot_message_heads = BotMessageHead::wherefb_page_id($user_fb_page->fb_page_id)->get();
            foreach ($bot_message_heads as $bot_message_head) {
                if (!TextComponent::passMessage($text, $bot_message_head->text)) {
                    continue;
                }
                $bot_message_replies = BotMessageReply::wherebot_message_head_id($bot_message_head->id)->get();
                ProcessMessageComponent::message($bot_message_replies, $person_id, $access_token);

                if (isset($data)) {
                    Facebook::post($access_token, 'me/messages', $data);
                }

                if ($bot_message_head->type === 'event' && $text !== null) {
                    $is_send = false;
                    if ($bot_message_head->type_event === 'phone' || $bot_message_head->type_event === 'email') {
                        if ($text === $quick_reply_payload) {
                            $is_send = true;
                        }
                    } else {
                        $is_send = true;
                    }
                    if ($is_send) {
                        ProcessEventComponent::event($bot_message_head, $person_id, $access_token);
                    }
                }
            }

            if ($mid !== null) {
                UpdateOrCreate::fbMessage(['mid' => $mid, 'status' => 0]);
            } else {
                UpdateOrCreate::fbMessage(array_merge(['status' => 0], $array_postback));
            }
        }
    }
}
