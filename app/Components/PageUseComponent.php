<?php

namespace App\Components;

use Illuminate\Support\Facades\App;

class Facebook
{

    public static function get($access_token, $url, $after = '', $data = [])
    {
        $fb = App::make('Scottybo\LaravelFacebookSdk\LaravelFacebookSdk');
        try {
            $after = strlen($after) ? '&after=' . $after : $after;
            $response = $fb->get($url . $after, $access_token)->getDecodedBody();
            $data = array_merge($response['data'], $data);
            if (isset($response['paging']['next'])) {
                return self::getData($access_token, $url, $response['paging']['cursors']['after'], $data);
            }
            return $data;
        } catch (\Exception $exception) {
            return $data;
        }
    }

    public static function post($access_token, $url, $data)
    {
        $fb = App::make('Scottybo\LaravelFacebookSdk\LaravelFacebookSdk');
        try {
            return $fb->post($url, $data, $access_token);
        } catch (\Exception $exception) {
            return false;
        }
    }
}
