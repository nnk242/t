<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class FbMessage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_messages';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1];

    protected $fillable = ['conversation_id', 'mid', 'recipient_id', 'sender_id', 'text', 'attachments', 'reply_to_mid',
        'sticker_id', 'reaction', 'reaction_action', 'reaction_emoji', 'delivery_watermark', 'payload', 'timestamp',
        'quick_reply_payload', 'status'];

    public function fbConversation()
    {
        return $this->belongsTo(FbConversation::class, 'conversation_id', 'conversation_id');
    }
}
