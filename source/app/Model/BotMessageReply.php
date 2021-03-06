<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BotMessageReply extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'bot_message_replies';

    public function __construct(array $attributes = [])
    {
        $this->attributes['type_message'] = 'text_messages';
        $this->attributes['type_notify'] = 'normal';
        parent::__construct($attributes);
    }

    protected $attributes = ['type_message' => 'text_messages', 'type_notify' => 'normal'];

    protected $fillable = ['type_message', 'type_notify', 'bot_message_head_id', 'fb_page_id', 'text',
        'begin_time_open', 'end_time_open', 'begin_time_active', 'end_time_active',
        ###
        'attachment_type', 'attachment_payload_url'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }

    public function botMessageHead()
    {
        return $this->belongsTo(BotMessageHead::class, 'bot_message_head_id');
    }

    public function botPayloadElements()
    {
        return $this->belongsToMany(BotPayloadElement::class, '_id', 'bot_message_reply_id');
    }
}
