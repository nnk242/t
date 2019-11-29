<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Gift extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'gifts';

    public function __construct(array $attributes = [])
    {
        $this->attributes['amount'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['amount' => 1];

    protected $fillable = ['bot_message_head_id', 'code', 'amount'];

    public function botMessageHead()
    {
        return $this->belongsTo(BotMessageHead::class, 'bot_message_head_id');
    }
}
