<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class FbProcess extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_process';

    protected $fillable = ['data', 'status'];

    protected $attributes = ['status' => 1];
}
