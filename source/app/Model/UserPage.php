<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class UserPage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'user_pages';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1];

    protected $fillable = ['user_page_id', 'page_id', 'access_token', 'user_id', 'status'];

    protected $hidden = [
        'access_token'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
