<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BotElementButton extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'bot_element_buttons';

    protected $fillable = ['bot_payload_element_id', 'type', 'url', 'title', 'payload'];

    public function botPayloadElement()
    {
        return $this->belongsTo(BotPayloadElement::class, 'bot_payload_element_id');
    }
}
