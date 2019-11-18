<?php

namespace App\Components\Facebook;

use Illuminate\Support\Facades\App;

class Message
{

    private static function recipient($id)
    {
        return [
            'recipient' => [
                'id' => $id
            ]
        ];
    }

    public static function post($access_token, $url, $data)
    {

    }
}
