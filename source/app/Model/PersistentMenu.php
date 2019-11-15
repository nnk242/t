<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class PersistentMenu extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'persistent_menus';

    protected $fillable = ['fb_page_id', 'name', 'picture', 'category', 'status', 'run_conversations'];
}
