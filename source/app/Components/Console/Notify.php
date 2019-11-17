<?php

namespace App\Components\Console;

use Illuminate\Support\Facades\App;

class Notify
{

    public static function notify($text)
    {
        return '[' . date('Y-m-d H:i:s') . '] ' . $text . PHP_EOL;
    }
}
