<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BotPayloadElement extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'bot_payload_elements';

    public function __construct(array $attributes = [])
    {
        $this->attributes['default_action_messenger_extensions'] = false;
        $this->attributes['default_action_messenger_webview_height_ratio'] = 'full';
        parent::__construct($attributes);
    }

    protected $attributes = ['default_action_messenger_extensions' => false, 'default_action_messenger_webview_height_ratio' => 'full'];

    protected $fillable = ['bot_message_reply_id', 'title', 'image_url', 'subtitle', 'default_action_type',
        'default_action_url', 'default_action_messenger_extensions', 'default_action_messenger_webview_height_ratio',
        'group', 'position'
    ];

    public function botMessageReply()
    {
        return $this->belongsTo(BotMessageReply::class, 'bot_message_reply_id');
    }
}
