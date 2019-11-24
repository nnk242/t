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

    public static function assetAttachment($data)
    {
        try {
            return array_merge(self::recipient($data['id']), [
                'message' => [
                    'attachment' => [
                        'type' => $data['attachment_type'],
                        'payload' => [
                            'url' => $data['attachment_payload_url']
                        ]
                    ]
                ]
            ]);
        } catch (\Exception $exception) {
            return false;
        }

    }

    public static function template($data)
    {
        try {
            return array_merge(self::recipient($data['id']), $data['message']);
        } catch (\Exception $exception) {
            return false;
        }
    }

    public static function senderActionTypingOn($data)
    {
        return array_merge(self::recipient($data['id']), [
            'sender_action' => 'typing_on'
        ]);
    }
}
