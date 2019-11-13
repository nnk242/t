<?php

namespace App\Model;

use App\User;
use Jenssegers\Mongodb\Eloquent\Model;

class UserRolePage extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'user_role_pages';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 0;
        $this->attributes['type'] = 0;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 0, 'type' => 0];

    protected $fillable = ['user_page_id', 'user_parent', 'user_child', 'status', 'type'];

    public function userPage()
    {
        return $this->belongsTo(UserPage::class, 'user_page_id');
    }

    public function userParent()
    {
        return $this->belongsTo(User::class, 'user_parent');
    }

    public function userChild()
    {
        return $this->belongsTo(User::class, 'user_child');
    }

}
