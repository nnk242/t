<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class Page extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'pages';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        $this->attributes['run_conversations'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1, 'run_conversations' => 1];

    protected $fillable = ['fb_page_id', 'name', 'picture', 'category', 'access_token', 'user_id', 'status', 'access_token'];

    protected $hidden = [
        'access_token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
