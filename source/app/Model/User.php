<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    protected $connection = 'mongodb';

    use Notifiable;
    use SoftDeletes;

    public function __construct(array $attributes = [])
    {
        $this->attributes['role'] = 'normal';
        parent::__construct($attributes);
    }

    protected $attributes = ['role' => 'normal'];

    protected $fillable = [
        'name', 'email', 'password', 'facebook_id', 'access_token', 'page_use', 'page_selected', 'role', 'social_id',
        'avatar', 'deleted_at'
    ];

    protected $hidden = [
        'password', 'remember_token', 'access_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    protected $dates = ['deleted_at'];
}
