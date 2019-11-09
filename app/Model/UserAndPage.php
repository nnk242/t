<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserAndPage extends Model
{
    protected $table = 'user_and_page';

    protected $fillable = ['page_id', 'user_parent', 'user_child', 'status'];

    public function page()
    {
        return $this->belongsTo(Page::class, 'page_id', 'id');
    }

}
