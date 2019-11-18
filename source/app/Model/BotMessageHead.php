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
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1];

    protected $fillable = ['fb_page_id', 'text'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }

    public function botMessageReplies()
    {
        return $this->hasMany(BotMessageReply::class, '_id', 'bot_message_head_id');
    }
}
