<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class BroadcastPage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'broadcast_pages';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1];

    protected $fillable = ['broadcast_messenger_id', 'fb_page_id', 'status'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'fb_page_id', 'fb_page_id');
    }

    public function messengerBroadcast()
    {
        return $this->belongsTo(BroadcastMessenger::class, 'broadcast_messenger_id');
    }
}
