<?php

namespace App\Model;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserAndPage extends Model
{
    protected $table = 'user_and_page';

    protected $fillable = ['page_id', 'user_parent', 'user_child', 'status', 'type'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

    public function userParent()
    {
        return $this->belongsTo(User::class, 'user_parent', 'id');
    }

    public function userChild()
    {
        return $this->belongsTo(User::class, 'user_child', 'id');
    }

}
