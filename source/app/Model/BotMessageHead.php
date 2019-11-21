<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BotMessageHead extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'bot_message_heads';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        $this->attributes['type'] = 'normal';
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1, 'type' => 'normal'];

    protected $fillable = ['fb_page_id', 'text', 'text_success_id', 'text_error_begin_time_active_id',
        'text_error_end_time_active_id', 'text_error_time_open_id', 'text_error_gift_id', 'type', 'status',
        'begin_time_open', 'end_time_open', 'begin_time_active', 'end_time_active'
    ];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }

    public function botMessageReplies()
    {
        return $this->hasMany(BotMessageReply::class, '_id', 'bot_message_head_id');
    }

    public function textSuccess()
    {
        return $this->belongsTo(BotMessageReply::class, 'text_success_id', 'bot_message_head_id');
    }

    public function textErrorBeginTimeActive()
    {
        return $this->belongsTo(BotMessageReply::class, 'text_error_begin_time_active_id', 'bot_message_head_id');
    }

    public function textErrorEndTimeActive()
    {
        return $this->belongsTo(BotMessageReply::class, 'text_error_end_time_active_id', 'bot_message_head_id');
    }

    public function textErrorTimeOpen()
    {
        return $this->belongsTo(BotMessageReply::class, 'text_error_time_open_id', 'bot_message_head_id');
    }

    public function textErrorGift()
    {
        return $this->belongsTo(BotMessageReply::class, 'text_error_gift_id', 'bot_message_head_id');
    }
}
