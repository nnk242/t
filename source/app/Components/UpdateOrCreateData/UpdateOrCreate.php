<?php

namespace App\Components\UpdateOrCreateData;

use App\Model\FbMessage;

class UpdateOrCreate
{
    public static function FbMessage($data)
    {
        return FbMessage::updateorcreate(['mid' => $data['mid']], $data);
    }

    public static function FbConversation($data)
    {
        return FbMessage::updateorcreate(['conversation_id' => $data['conversation_id']], $data);
    }
}
