<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class UserFbPage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'user_fb_pages';

    protected $fillable = ['m_page_user_id', 'fb_page_id', 'user_fb_id', 'first_name', 'last_name', 'name', 'profile_pic', 'gender', 'locale', 'timezone'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }
}
