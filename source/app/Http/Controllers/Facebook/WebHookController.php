<?php

namespace App\Http\Controllers\Facebook;

use App\Components\Common\TextComponent;
use App\Components\Facebook\Facebook;
use App\Components\Facebook\Message;
use App\Components\Facebook\DataMessaging;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Jobs\Facebook\FacebookMessaging;
use App\Jobs\Facebook\FacebookSaveData;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\FbPostAction;
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
                        if ($sender_id === $fb_page_id) {
                            $person_id = $recipient_id;
                        } else {
                            $person_id = $sender_id;
                        }

                        $user_fb_page = DataMessaging::userFbPage($person_id, $fb_page_id);
                        ## run process
                        $access_token = null;
//
                        if ($text === 'Attachment') {
//                            $bot_message_reply = BotMessageReply::orderby('_id', 'DESC')->first();
                            //                                Message::assetAttachment([
//                                'id' => $person_id,
//                                'attachment_type' => $bot_message_reply->attachment_type,
//                                'attachment_payload_url' => $bot_message_reply->attachment_payload_url
//                            ]);
                            if (isset($user_fb_page)) {
                                $access_token = $user_fb_page->page->access_token;
                            }

//                            Message::senderActionTypingOn(['id' => $person_id]);
                            $send = Facebook::post($access_token, 'me/messages', Message::senderActionTypingOn(['id' => $person_id]));
                            $data = [
                                'recipient' => [
                                    'id' => $person_id
                                ],
                                "message" => [
                                    "attachment" => [
                                        "type" => "template",
                                        "payload" => [
                                            "template_type" => "generic",
                                            "elements" => [
                                                [
                                                    "title" => "Welcome!",
                                                    "image_url" => "https://photo2.tinhte.vn/data/attachment-files/2018/01/4232581_13458709_1754337424837799_8896947914071725008_o.jpg",
                                                    "subtitle" => "We have the right hat for everyone.",
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
                            ];
                            if (isset($data)) {
                                $send = Facebook::post($access_token, 'me/messages', $data);
                            }
                        }
//
                        $client->initialize();
                        $client->emit('data', array($request->all(), '$user_fb_page' => $user_fb_page,
                            '$send' => isset($send) ? $send : 'No response'));
                        $client->close();
                    } else {
                        if (isset($entry[0]['changes'][0]['field'])) {
                            $changes = $entry[0]['changes'];
                            if ($changes[0]['field'] === "feed") {
                                if (isset($changes[0]['value'])) {
                                    $value = $changes[0]['value'];
                                    $parent_id = isset($value['parent_id']) ? $value['parent_id'] : null;
                                    if ($parent_id === null) {
                                        $data = [
                                            'fb_page_id' => $fb_page_id,
                                            'post_id' => $value['post_id'],
                                            'message' => isset($value['message']) ? $value['message'] : null,
                                            'link' => isset($value['link']) ? $value['link'] : null,
                                            'from_id' => $value['from']['id'],
                                            'created_time' => $value['created_time'],
                                            'verb' => $value['verb'],
                                            'item' => $value['item']
                                        ];
                                        UpdateOrCreate::fbFeed($data);
                                        $client->initialize();
                                        $client->emit('data', array($request->all(), 'post'));
                                        $client->close();
                                    } else {
                                        $data = ['from_id' => $value['from']['id']];
                                        if ($value['item'] === "comment") {
                                            $data = array_merge($data, [
                                                'comment_id' => $value['comment_id'],
                                                'created_time' => $value['created_time'],
                                                'item' => $value['item'],
                                                'parent_id' => $value['parent_id'],
                                                'post' => isset($value['post']) ? json_encode($value['post']) : null,
                                                'post_id' => $value['post_id'],
                                                'verb' => $value['verb'],
                                                'photo' => isset($value['photo']) ? $value['photo'] : null
                                            ]);

                                            FbPostAction::updateorcreate(['comment_id' => $data['comment_id']], $data);
                                        } elseif ($value['item'] === "like" || $value['item'] === "reaction") {
                                            $data = array_merge($data, [
                                                'comment_id' => $value['comment_id'],
                                                'created_time' => $value['created_time'],
                                                'item' => 'reaction',
                                                'parent_id' => $value['parent_id'],
                                                'post_id' => $value['post_id'],
                                                'reaction_type' => $value['item'] === "like" ? 'like' : $value['reaction_type'],
                                                'verb' => $value['verb']
                                            ]);

                                            FbPostAction::updateorcreate(['parent_id' => $data['parent_id'], 'item' => 'reaction'], $data);
                                        }
                                        $client->initialize();
                                        $client->emit('data', array($request->all(), 'action post'));
                                        $client->close();
                                    }
                                }

                            }
                        }
                    }
                }
            } else {
                $client->initialize();
                $client->emit('data', array($request->all(), 'Data id not found'));
                $client->close();
            }

        } catch
        (\Exception $exception) {
            $client->initialize();
            $client->emit('data', array($request->all(), 'error' => [$exception->getMessage(), $exception->getCode()]));
            $client->close();
        }
        return $request->all();
    }
}
