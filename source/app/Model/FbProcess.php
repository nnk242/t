<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model;

class FbProcess extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'fb_process';

    public function __construct(array $attributes = [])
    {
        $this->attributes['status'] = 1;
        parent::__construct($attributes);
    }

    protected $attributes = ['status' => 1];

    protected $fillable = ['data', 'status', 'code', 'message'];
}
