<?php

namespace App\Http\Controllers\Facebook;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Components\Facebook\ProcessDataMessaging;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Jobs\Facebook\FacebookMessaging;
use App\Jobs\Facebook\FacebookSaveData;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\Page;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Http\Request;

class WebHookController extends Controller
{
    public function webHook(Request $request)
    {
        $VERIFY_TOKEN = "test";
        // Parse the query params
        $mode = $request->hub_mode;
        $token = $request->hub_verify_token;
        $challenge = $request->hub_challenge;
        // Checks if a token and mode is in the query string of the request
        if ($mode && $token) {
            // Checks the mode and token sent is correct
            if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
                // Responds with the challenge token from the request
                return $challenge;
            } else {
                // Responds with '403 Forbidden' if verify tokens do not match
                return 1;
            }
        }
        return 2;
    }

    public function store(Request $request)
    {
        try {
            $url = 'http://127.0.0.1:3000/';
            $client = new Client(new Version2X($url, [
                'headers' => [
                    'Authorization: ' . env('KEY_CONNECTION')
                ]
            ]));
            $this->dispatch(new FacebookSaveData($request->all()));
            $this->dispatch(new FacebookMessaging($request->all()));

            ###
            $entry = $request['entry'] ? $request['entry'] : null;
            if (isset($entry[0]['id'])) {
                $fb_page_id = $entry[0]['id'];
                $page = Page::wherefb_page_id($fb_page_id)->first();
                if (isset($page)) {
                    if (isset($entry[0]['messaging'])) {
                        $sender_id = isset($entry[0]['messaging'][0]['sender']['id']) ? $entry[0]['messaging'][0]['sender']['id'] : null;
                        $recipient_id = isset($entry[0]['messaging'][0]['recipient']['id']) ? $entry[0]['messaging'][0]['recipient']['id'] : null;
                        $text = isset($entry[0]['messaging'][0]['message']['text']) ? $entry[0]['messaging'][0]['message']['text'] : null;
                        #### Get user fb page
                        $is_user = false;
                        if ($sender_id === $fb_page_id) {
                            $person_id = $recipient_id;
                        } else {
                            $is_user = true;
                            $person_id = $sender_id;
                        }

                        $user_fb_page = ProcessDataMessaging::userFbPage($person_id, $fb_page_id);
                        ## run process
                        $access_token = null;

//                        if (isset($user_fb_page) && $is_user) {
//                            $access_token = $user_fb_page->page->access_token;
//
//                            $bot_message_heads = BotMessageHead::wherefb_page_id($user_fb_page->fb_page_id)->get();
//                            foreach ($bot_message_heads as $bot_message_head) {
//                                if (!TextComponent::passMessage($text, $bot_message_head->text)) {
//                                    continue;
//                                }
//                                $bot_message_replies = BotMessageReply::wherebot_message_head_id($bot_message_head->id)->get();
//                                foreach ($bot_message_replies as $bot_message_reply) {
//                                    $is_send = true;
//                                    if ($bot_message_reply->type_message === 'text_messages') {
//                                        $time = time();
//                                        $data = [
//                                            'id' => $person_id,
//                                            'text' => $bot_message_reply->text
//                                        ];
//                                        if ($bot_message_reply->type_notify === "timer") {
//                                            if ($bot_message_reply->begin_time_active) {
//                                                if ((int)$bot_message_reply->begin_time_active > $time) {
//                                                    $is_send = false;
//                                                }
//                                            }
//                                            if ($bot_message_reply->end_time_active) {
//                                                if ((int)$bot_message_reply->end_time_active < $time) {
//                                                    $is_send = false;
//                                                }
//                                            }
//                                            if ($is_send) {
//                                                $date_now = date('Y-m-d');
//                                                $date_min = $date_now . ' 00:00:00';
//                                                $str_to_time_min = strtotime($date_min);
//                                                if (($str_to_time_min + (int)$bot_message_reply->begin_time_open) > $time) {
//                                                    $is_send = false;
//                                                }
//                                                if (($str_to_time_min + (int)$bot_message_reply->end_time_open) < $time) {
//                                                    $is_send = false;
//                                                }
//                                            }
//                                        }
//
//
//                                        $mid = isset($entry[0]['messaging'][0]['message']['mid']) ? $entry[0]['messaging'][0]['message']['mid'] : null;
//
//                                        $array_postback = [
//                                            'payload' => isset($entry[0]['messaging'][0]['postback']['payload']) ? $entry[0]['messaging'][0]['postback']['payload'] : null,
//                                            'timestamp' => isset($entry[0]['messaging'][0]['timestamp']) ? $entry[0]['messaging'][0]['timestamp'] : null,
//                                            'conversation_id' => $user_fb_page->fbConversation->conversation_id,
//                                            'status' => 0
//                                        ];
//
//                                        $client->initialize();
//                                        $client->emit('data', array($request->all(),
//                                                '$user_fb_page' => $user_fb_page,
//                                                '$data' => $data,
//                                                'message' => $text,
//                                                '$bot_message_reply' => $bot_message_reply,
//                                                '$bot_message_heads' => $bot_message_heads,
//                                                '$mid' => $mid,
//                                                '$array_postback' => $array_postback
//                                            )
//                                        );
//                                        $client->close();
//                                        if ($is_send) {
//                                            if (isset($bot_message_reply->text)) {
//                                                Facebook::post($access_token, 'me/messages', Message::textMessage($data));
//                                            }
//                                        }
//                                        if (isset($mid)) {
//                                            UpdateOrCreate::fbMessage(['mid' => $mid, 'status' => 0]);
//                                        } else {
//                                            UpdateOrCreate::fbMessage(array_merge(['status' => 0], $array_postback));
//                                        }
//                                    }
//                                }
//                            }
//
//                            if (isset($data)) {
//                                $send = Facebook::post($access_token, 'me/messages', $data);
//                            }
//                        }

                        $client->initialize();
                        $client->emit('data', array($request->all(), '$user_fb_page' => $user_fb_page, '$send' => isset($send) ? $send : 'No response'));
                        $client->close();
                    } else {
                        $client->initialize();
                        $client->emit('data', array($request->all(), 'Can not message'));
                        $client->close();
                    }
                } else {
                    $client->initialize();
                    $client->emit('data', array($request->all(), 'not found page'));
                    $client->close();
                }
            }

//            $client->initialize();
//            $client->emit('data', array($request->all()));
//            $client->close();
        } catch (\Exception $exception) {
            $client->initialize();
            $client->emit('data', array($request->all(), 'error' => [$exception->getMessage(), $exception->getCode()]));
            $client->close();
        }
        return $request->all();
    }
}
