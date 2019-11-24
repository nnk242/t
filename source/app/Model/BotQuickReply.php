<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BotQuickReply extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'bot_quick_replies';

    protected $fillable = ['bot_message_reply_id', 'title', 'image_url', 'content_type', 'payload'];

    public function botMessageReply()
    {
        return $this->belongsTo(BotMessageReply::class, 'bot_message_reply_id');
    }
}
