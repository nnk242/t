<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class PageUse extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'pages';

    protected $fillable = ['page_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }
}
