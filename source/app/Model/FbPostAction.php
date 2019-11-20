<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class FbPostAction extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_post_actions';

    public function __construct(array $attributes = [])
    {
        $this->attributes['verd'] = 'add';
        $this->attributes['item'] = 'status';
        parent::__construct($attributes);
    }

    protected $attributes = ['verd' => 'add', 'item' => 'status'];

    protected $fillable = ['fb_page_id', 'post_id', 'comment_id', 'from_id', 'message', 'parent_id', 'post',
        'created_time', 'verb', 'item', 'reaction_type', 'photo'];
}
