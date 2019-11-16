<?php

namespace App\Http\Controllers\Facebook;

use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Jobs\Facebook\FacebookSaveData;
use App\Model\FbMessage;
use App\Model\Page;
use App\Model\FbUserPage;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class WebHookController extends Controller
{
    private function userFbPage($person_id, $fb_page_id, $k = 0)
    {
        $m_page_user_id = $fb_page_id . '_' . $person_id;
        $user_fb_page = FbUserPage::wherem_page_user_id($m_page_user_id)->first();
        if (isset($user_fb_page)) {
            return $user_fb_page;
        } else {
            Artisan::call('command:AddUserPage --page_user_id=' . $person_id . ' --fb_page_id=' . $fb_page_id);
            $k++;
            if ($k === 3) {
                return null;
            }
            return $this->userFbPage($person_id, $fb_page_id, $k);
        }
    }

    private function data($entry, $person_id, $sender_id, $recipient_id)
    {
        $fb_page_id = $entry[0]['id'];
        $timestamp = isset($entry[0]['messaging'][0]['timestamp']) ? $entry[0]['messaging'][0]['timestamp'] : null;

        $mid = null;
        ###Message
        $text = null;
        $attachments = null;
        $reply_to_mid = null;
        $sticker_id = null;
        ###Reaction
        $reaction = null;
        $reaction_action = null;
        $reaction_emoji = null;
        ###

        $fb_user_page = $this->userFbPage($person_id, $fb_page_id);

        $conversation_id = $fb_user_page->fbConversation->conversation_id;

        ###message
        if (isset($entry[0]['messaging'][0]['message'])) {
            $message_ = $entry[0]['messaging'][0]['message'];
            ###
            $mid = isset($message_['mid']) ? $message_['mid'] : null;
            $text = isset($message_['text']) ? $message_['text'] : null;
            $attachments = isset($message_['attachments']) ? $message_['attachments'] : null;
            $reply_to_mid = isset($message_['reply_to']['mid']) ? $message_['reply_to']['mid'] : null;
            $sticker_id = isset($message_['$sticker_id']) ? $message_['$sticker_id'] : null;

            $data = [
                'conversation_id' => $conversation_id,
                'mid' => $mid,
                'recipient_id' => $recipient_id,
                'sender_id' => $sender_id,
                'text' => $text,
                'attachments' => json_encode($attachments),
                'reply_to_mid' => $reply_to_mid,
                'sticker_id' => $sticker_id,
                'timestamp' => $timestamp
            ];
            UpdateOrCreate::FbMessage($data);
        }
        ###reaction
        if (isset($entry[0]['messaging'][0]['reaction'])) {
            $reaction_ = $entry[0]['messaging'][0]['reaction'];
            ###
            $mid = isset($reaction_['mid']) ? $reaction_['mid'] : null;
            $reaction = isset($reaction_['reaction']) ? $reaction_['reaction'] : null;
            $reaction_action = isset($reaction_['action']) ? $reaction_['action'] : null;
            $reaction_emoji = isset($reaction_['emoji']) ? $reaction_['emoji'] : null;
            $data = [
                'conversation_id' => $conversation_id,
                'mid' => $mid,
                'recipient_id' => $recipient_id,
                'sender_id' => $sender_id,
                'reaction' => $reaction,
                'reaction_action' => $reaction_action,
                'reaction_emoji' => $reaction_emoji,
                'timestamp' => $timestamp
            ];
            UpdateOrCreate::FbMessage($data);
        }
        ###delivery
        if (isset($entry[0]['messaging'][0]['delivery'])) {
            $delivery_ = $entry[0]['messaging'][0]['delivery'];
            ###
            foreach ($delivery_['mids'] as $mid) {
                $data = [
                    'conversation_id' => $conversation_id,
                    'mid' => $mid,
                    'recipient_id' => $recipient_id,
                    'sender_id' => $sender_id,
                    'delivery_watermark' => $delivery_['watermark'],
                    'timestamp' => $timestamp
                ];
                UpdateOrCreate::FbMessage($data);
            }
        }
        ###postback
        if (isset($entry[0]['messaging'][0]['postback'])) {
            $postback_ = $entry[0]['messaging'][0]['postback'];
            ###
            $data = [
                'payload' => $postback_['payload'],
                'timestamp' => $timestamp
            ];
            FbMessage::where($data)->firstorcreate(array_merge($data, ['text' => $postback_['title']]));
        }

        if (isset($entry[0]['messaging'][0]['read'])) {
            $read_ = $entry[0]['messaging'][0]['read'];
            ###
            if (isset($fb_user_page)) {
                UpdateOrCreate::FbConversation(['conversation_id' => $conversation_id, 'read_watermark' => $read_['watermark']]);
            }
        }

        return [
            'mid' => $mid,
            ###Message
            'attachments' => $attachments,
            'text' => $text,
            'reply_to_mid' => $reply_to_mid,
            'sticker_id' => $sticker_id,
            ###Reaction
            'reaction' => $reaction,
            'reaction_action' => $reaction_action,
            'reaction_emoji' => $reaction_emoji
        ];
    }

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
        $url = 'http://127.0.0.1:3000/';
        $client = new Client(new Version2X($url, [
            'headers' => [
                'Authorization: ' . env('KEY_CONNECTION')
            ]
        ]));
        try {
            $this->dispatch(new FacebookSaveData($request->all()));

            ###
            $entry = $request['entry'];
            if (isset($request['entry'][0]['id'])) {
                $fb_page_id = $request['entry'][0]['id'];
                $page = Page::wherefb_page_id($fb_page_id)->first();
//                $fb_page_id = $page->fb_page_id;
                if (isset($page)) {
                    if (isset($entry[0]['messaging'])) {
                        $sender_id = isset($entry[0]['messaging'][0]['sender']['id']) ? $entry[0]['messaging'][0]['sender']['id'] : null;
                        $recipient_id = isset($entry[0]['messaging'][0]['recipient']['id']) ? $entry[0]['messaging'][0]['recipient']['id'] : null;

                        #### Get user fb page
                        if ($sender_id === $fb_page_id) {
                            $person_id = $recipient_id;
                        } else {
                            $person_id = $sender_id;
                        }

                        $user_fb_page = $this->userFbPage($person_id, $fb_page_id);
                        ## run process

                        $client->initialize();
                        $client->emit('data', array($request->all(), '$user_fb_page' => $user_fb_page,
                            'data' => $this->data($entry, $person_id, $sender_id, $recipient_id)));
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
            $client->emit('data', array($request->all(), 'error' => [$exception->getMessage(), $exception]));
            $client->close();
        }
        return $request->all();
    }
}
