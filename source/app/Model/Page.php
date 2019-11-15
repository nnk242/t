<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class Page extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'pages';

    protected $fillable = ['fb_page_id', 'name', 'picture', 'category', 'status', 'run_conversations'];
}
