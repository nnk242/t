<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Event extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'events';

    public function __construct(array $attributes = [])
    {
        $this->attributes['type'] = 'feed';
        $this->attributes['status'] = 'normal';
        parent::__construct($attributes);
    }

    protected $attributes = ['type_message' => 'text_messages', 'type_notify' => 'normal'];

    protected $fillable = ['type_message', 'type_notify', 'bot_message_head_id', 'fb_page_id', 'text',
        'begin_time_open', 'end_time_open', 'begin_time_active', 'end_time_active'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }
}
