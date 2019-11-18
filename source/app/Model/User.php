<?php

namespace App\Model;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $connection = 'mongodb';

    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'facebook_id', 'access_token', 'page_use', 'page_selected'
    ];

    protected $hidden = [
        'password', 'remember_token', 'access_token'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
