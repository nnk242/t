<?php

namespace App\Components\UpdateOrCreateData;

use App\Components\Page\PageComponent;
use App\Model\BotMessageHead;
use App\Model\FbConversation;
use App\Model\FbMessage;
use App\Model\FbProcess;
use App\Model\Page;
use App\Model\UserRolePage;
use Illuminate\Support\Facades\Auth;

class UpdateOrCreate
{
    public static function fbMessage($data)
    {
        return FbMessage::updateorcreate(['mid' => $data['mid']], $data);
    }

    public static function fbConversation($data)
    {
        return FbConversation::updateorcreate(['conversation_id' => $data['conversation_id']], $data);
    }

    public static function fbProcess($data)
    {
        return FbProcess::updateorcreate(['_id' => $data['_id']], $data);
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
            if ($page_selected->fb_page_id) {
                $data = array_merge(['fb_page_id' => $page_selected->fb_page_id], $data);
                $bot_message_head = BotMessageHead::firstorcreate($data);
                return $bot_message_head;
            }
        }
        return false;
    }
}
