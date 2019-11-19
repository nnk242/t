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

    public static function textMessage($data)
    {
        return array_merge(self::recipient($data['id']), [
            'message' => [
                'text' => $data['text']
            ]
        ]);
    }
}
