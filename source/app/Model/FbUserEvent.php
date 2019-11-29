<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class FbUserEvent extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_user_events';

    protected $fillable = ['bot_message_head_id', 'fb_page_id', 'gift', 'message', 'user_fb_id'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }
}
