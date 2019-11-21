<?php

namespace App\Components\UpdateOrCreateData;

use App\Components\Page\PageComponent;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\FbConversation;
use App\Model\FbFeed;
use App\Model\FbMessage;
use App\Model\FbProcess;
use App\Model\Page;
use App\Model\UserRolePage;

class UpdateOrCreate
{
    public static function fbMessage($data)
    {
        try {

            if (isset($data['mid'])) {
                return FbMessage::updateorcreate(['mid' => $data['mid']], $data);
            } else {
                return FbMessage::updateorcreate([
                    'payload' => $data['payload'],
                    'timestamp' => $data['timestamp'],
                    'conversation_id' => $data['conversation_id']
                ], $data);
            }
        } catch (\Exception $exception) {
            return false;
        }
    }

    public static function fbConversation($data)
    {
        return FbConversation::updateorcreate(['conversation_id' => $data['conversation_id']], $data);
    }

    public static function fbProcess($data)
    {
        return FbProcess::updateorcreate(['_id' => isset($data['_id']) ? $data['_id'] : null], $data);
    }

    public static function page($data)
    {
        return Page::updateorcreate(['fb_page_id' => $data['fb_page_id']], $data);
    }

    public static function userRolePage($data)
    {
        return UserRolePage::updateorcreate(['fb_page_parent' => $data['fb_page_parent']], $data);
    }

    public static function botMessageHead($data)
    {
        $page_selected = PageComponent::pageSelected();
        if (isset($page_selected)) {
            $data = array_merge(['fb_page_id' => $page_selected->fb_page_id], $data);
            if ($page_selected->fb_page_id) {
                switch ($data['type']) {
                    case 'event':
                        $bot_message_head = BotMessageHead::create($data);
                        break;
                    default:
                        $bot_message_head = BotMessageHead::firstorcreate($data);
                        break;
                }
                return $bot_message_head;
            }
        }
        return false;
    }

    public static function botMessageReply($data)
    {
        $page_selected = PageComponent::pageSelected();
        if (isset($page_selected)) {
            if ($page_selected->fb_page_id) {
                $data = array_merge(['fb_page_id' => $page_selected->fb_page_id], $data);
                if (isset($data['_id'])) {
                    $bot_message_head = BotMessageReply::updateorcreate(['_id' => $data['_id']], $data);
                } else {
                    $bot_message_head = BotMessageReply::create($data);
                }
                return $bot_message_head;
            }
        }
        return false;
    }

    public static function fbFeed($data)
    {
        return FbFeed::updateorcreate(['post_id' => $data['post_id']], $data);
    }
}
