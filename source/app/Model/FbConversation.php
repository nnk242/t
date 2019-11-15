<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class FbConversation extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_conversations';

    protected $fillable = ['user_fb_page_id', 'conversation_id', 'snippet', 'updated_time'];


    public function userFbPage()
    {
        return $this->belongsTo(UserFbPage::class, 'user_fb_page_id');
    }
}
