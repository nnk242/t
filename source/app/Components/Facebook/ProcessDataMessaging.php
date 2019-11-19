<?php

namespace App\Components\Facebook;

use App\Components\Common\TextComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Jobs\Facebook\FacebookSaveData;
use App\Jobs\Facebook\FacebookSendMessage;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\FbMessage;
use App\Model\FbUserPage;
use App\Model\Page;
use Illuminate\Support\Facades\Artisan;
use Mockery\Exception;

class ProcessDataMessaging
{
    public static function userFbPage($person_id, $fb_page_id, $k = 0)
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
            return self::userFbPage($person_id, $fb_page_id, $k);
        }
    }

    private static function message_($message_, $conversation_id, $recipient_id, $sender_id, $timestamp)
    {
        $mid = isset($message_['mid']) ? $message_['mid'] : null;
        $text = isset($message_['text']) ? $message_['text'] : null;
        $attachments = isset($message_['attachments']) ? $message_['attachments'] : null;
        $reply_to_mid = isset($message_['reply_to']['mid']) ? $message_['reply_to']['mid'] : null;
        $sticker_id = isset($message_['sticker_id']) ? $message_['sticker_id'] : null;
        $quick_reply_payload = isset($message_['quick_reply']['payload']) ? $message_['quick_reply']['payload'] : null;

        $data = [
            'conversation_id' => $conversation_id,
            'mid' => $mid,
            'recipient_id' => $recipient_id,
            'sender_id' => $sender_id,
            'text' => $text,
            'attachments' => json_encode($attachments),
            'reply_to_mid' => $reply_to_mid,
            'sticker_id' => $sticker_id,
            'timestamp' => $timestamp,
            'quick_reply_payload' => $quick_reply_payload
        ];
        UpdateOrCreate::fbConversation(['conversation_id' => $conversation_id, 'snippet' => $text ? $text : ($attachments ? 'You sent attachments.' : "[Error 0]Don't know.")]);
        UpdateOrCreate::fbMessage($data);
    }

    private static function reaction_($reaction_, $conversation_id, $recipient_id, $sender_id, $timestamp)
    {
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
        UpdateOrCreate::fbMessage($data);
    }

    private static function delivery_($delivery_, $conversation_id, $recipient_id, $sender_id, $timestamp)
    {
        foreach ($delivery_['mids'] as $mid) {
            $data = [
                'conversation_id' => $conversation_id,
                'mid' => $mid,
                'recipient_id' => $recipient_id,
                'sender_id' => $sender_id,
                'delivery_watermark' => $delivery_['watermark'],
                'timestamp' => $timestamp
            ];
            UpdateOrCreate::fbMessage($data);
        }
    }

    private static function postback_($postback_, $conversation_id, $timestamp)
    {
        $data = [
            'payload' => $postback_['payload'],
            'timestamp' => $timestamp,
            'conversation_id' => $conversation_id
        ];
        UpdateOrCreate::fbConversation(['conversation_id' => $conversation_id, 'snippet' => $postback_['title']]);
        FbMessage::where($data)->firstorcreate(array_merge($data, ['text' => $postback_['title']]));
    }

    private static function read_($read_, $conversation_id, $fb_user_page)
    {
        if (isset($fb_user_page)) {
            UpdateOrCreate::fbConversation(['conversation_id' => $conversation_id, 'read_watermark' => $read_['watermark']]);
        }
    }

    public static function handle($entry, $person_id, $sender_id, $recipient_id)
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

        $fb_user_page = self::userFbPage($person_id, $fb_page_id);

        $conversation_id = $fb_user_page->fbConversation->conversation_id;

        ###message
        if (isset($entry[0]['messaging'][0]['message'])) {
            $message_ = $entry[0]['messaging'][0]['message'];
            ###
            self::message_($message_, $conversation_id, $recipient_id, $sender_id, $timestamp);
        }
        ###reaction
        if (isset($entry[0]['messaging'][0]['reaction'])) {
            $reaction_ = $entry[0]['messaging'][0]['reaction'];
            ###
            self::reaction_($reaction_, $conversation_id, $recipient_id, $sender_id, $timestamp);
        }
        ###delivery
        if (isset($entry[0]['messaging'][0]['delivery'])) {
            $delivery_ = $entry[0]['messaging'][0]['delivery'];
            ###
            self::delivery_($delivery_, $conversation_id, $recipient_id, $sender_id, $timestamp);
        }
        ###postback
        if (isset($entry[0]['messaging'][0]['postback'])) {
            $postback_ = $entry[0]['messaging'][0]['postback'];
            ###
            self::postback_($postback_, $conversation_id, $timestamp);

        }

        if (isset($entry[0]['messaging'][0]['read'])) {
            $read_ = $entry[0]['messaging'][0]['read'];
            ###
            self::read_($read_, $conversation_id, $fb_user_page);
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

    public static function index($data)
    {
        try {
            $entry = isset($data['entry']) ? $data['entry'] : null;
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

                        $user_fb_page = self::userFbPage($person_id, $fb_page_id);
                        ## run process
                        self::handle($entry, $person_id, $sender_id, $recipient_id);

                        if ($is_user) {
                            dispatch(new FacebookSendMessage(['text' => $text, 'user_fb_page' => $user_fb_page, 'person_id' => $person_id, 'entry' => $entry]));
                        }
                    }
                }
            }
            return true;
        } catch (Exception $exception) {
            return false;
        }

    }
}
