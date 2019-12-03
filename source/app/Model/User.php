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

    public function getPageUse()
    {
        if ($this->getAttribute('page_use')) {
            return json_decode($this->getAttribute('page_use'));
        }
        return [];
    }

    public function getPageSelected()
    {
        $page = new Page();
        $arr_page_use = $this->getPageUse();
        $page_selected = $this->getAttribute('page_selected');
        if ($page_selected) {
            if (in_array($page_selected, $arr_page_use) && in_array($page_selected, $page->listFbPageId())) {
                return $page_selected;
            }
        }
        return '';
    }

    public function getRole()
    {
        return $this->getAttribute('role');
    }
}
