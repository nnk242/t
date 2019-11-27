<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BroadcastMessenger extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'broadcast_messengers';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1];

    protected $fillable = ['bot_message_reply_id', 'time_interactive', 'begin_time_active', 'end_time_active', 'status', 'user_id'];

    public function botMessageReply()
    {
        return $this->belongsTo(BotMessageReply::class, 'bot_message_reply_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
