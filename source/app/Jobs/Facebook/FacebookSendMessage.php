<?php

namespace App\Jobs\Facebook;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\FbProcess;
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

    public function handle()
    {
        $text = $this->data['text'];
        $person_id = $this->data['person_id'];
        $entry = $this->data['entry'];
        $user_fb_page = $this->data['user_fb_page'];
//        dd($user_fb_page);
        if (isset($user_fb_page)) {
            $access_token = $user_fb_page->page->access_token;

            $bot_message_heads = BotMessageHead::wherefb_page_id($user_fb_page->fb_page_id)->get();
            foreach ($bot_message_heads as $bot_message_head) {
                if (!TextComponent::passMessage($text, $bot_message_head->text)) {
                    continue;
                }
                $bot_message_replies = BotMessageReply::wherebot_message_head_id($bot_message_head->id)->get();
                foreach ($bot_message_replies as $bot_message_reply) {
                    $is_send = true;
                    if ($bot_message_reply->type_message === 'text_messages') {
                        $time = time();
                        $data = [
                            'id' => $person_id,
                            'text' => $bot_message_reply->text
                        ];
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


                        $mid = isset($entry[0]['messaging'][0]['message']['mid']) ? $entry[0]['messaging'][0]['message']['mid'] : null;

                        $array_postback = [
                            'payload' => isset($entry[0]['messaging'][0]['postback']['payload']) ? $entry[0]['messaging'][0]['postback']['payload'] : null,
                            'timestamp' => isset($entry[0]['messaging'][0]['timestamp']) ? $entry[0]['messaging'][0]['timestamp'] : null,
                            'conversation_id' => $user_fb_page->fbConversation->conversation_id,
                            'status' => 0
                        ];

                        if ($is_send) {
                            if (isset($bot_message_reply->text)) {
                                Facebook::post($access_token, 'me/messages', Message::textMessage($data));
                            }
                        }
                        if (isset($mid)) {
                            UpdateOrCreate::fbMessage(['mid' => $mid, 'status' => 0]);
                        } else {
                            UpdateOrCreate::fbMessage(array_merge(['status' => 0], $array_postback));
                        }
                    }
                }
            }

            if (isset($data)) {
                Facebook::post($access_token, 'me/messages', $data);
            }
        }
    }
}
