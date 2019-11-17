<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class FbConversation extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_conversations';

    protected $fillable = ['user_fb_page_id', 'conversation_id', 'snippet', 'read_watermark', 'quick_reply_phone'];

    public function fbUserPage()
    {
        return $this->belongsTo(FbUserPage::class, 'user_fb_page_id');
    }
}
