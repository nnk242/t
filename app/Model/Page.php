<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class Page extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'pages';

    protected $fillable = ['fb_page_id', 'name', 'picture', 'category', 'access_token', 'user_id', 'user_id_fb_page_id'];

    protected $hidden = [
        'access_token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
