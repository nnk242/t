<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class FbFeed extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_feeds';

    public function __construct(array $attributes = [])
    {
        $this->attributes['verd'] = 'add';
        $this->attributes['item'] = 'status';
        parent::__construct($attributes);
    }

    protected $attributes = ['verd' => 'add', 'item' => 'status'];

    protected $fillable = ['fb_page_id', 'post_id', 'message', 'from_id', 'message', 'link', 'created_time', 'verb', 'item'];
}
