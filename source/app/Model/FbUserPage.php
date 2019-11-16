<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class FbUserPage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_user_pages';

    protected $fillable = ['m_page_user_id', 'fb_page_id', 'user_fb_id', 'first_name', 'last_name', 'name', 'profile_pic', 'gender', 'locale', 'timezone'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }

    public function fbConversation()
    {
        return $this->belongsTo(FbConversation::class, '_id', 'user_fb_page_id');
    }
}
