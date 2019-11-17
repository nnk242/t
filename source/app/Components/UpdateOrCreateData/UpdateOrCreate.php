<?php

namespace App\Components\UpdateOrCreateData;

use App\Model\FbConversation;
use App\Model\FbMessage;
use App\Model\FbProcess;
use App\Model\Page;

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
        return Page::updateorcreate(['fb_page_id' => $data['id']], [
            'fb_page_id' => $data['id'],
            'name' => $data['name'],
            'picture' => $data['picture']['data']['url'],
            'category' => $data['category'],
            'access_token' => $data['access_token'],
            'user_id' => $data['user_id'],
            'run_conversations' => 1
        ]);
    }
}
