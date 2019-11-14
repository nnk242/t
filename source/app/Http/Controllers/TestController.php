<?php

namespace App\Http\Controllers;

use App\Model\UserMessage;
use Illuminate\Http\Request;
use Mockery\Exception;

class TestController extends Controller
{
    #defined text
    #default
    ##  !'value, first, last, min, max
    ##  !'_____, *****, ****, ***, ***
    public function text()
    {
        $time_start = microtime(true);

        $text = "Suy ra một điều rằng Trâm. Anh thích 64 :)";
        $text_def = "!'S";

        $strlen_def = strlen($text_def);

        switch (true) {
            case $strlen_def === 2:
                if ($text_def === "!'") {
                    return 1;
                }
                break;
            case $strlen_def > 2:
                if ($text_def[0] === '!' and $text_def[1] === "'") {
                    $arr_text_def = explode("!'", $text_def);

                    try {
                        unset($arr_text_def[0]);
                    } catch (Exception $exception) {
                    }

                    $text_def = implode($arr_text_def, "!'");
                    //
                    $arr_text_def = explode(",", $text_def);
                    $count_arr_text_def = count($arr_text_def);
                    if ($count_arr_text_def <= 1) {
                        if (gettype(strpos($text, $text_def)) === 'integer') {
                            dd(1);
                        }
                    } else {

                    }
//                    dd($text_def);
                    break;
                }
            default:
                if ($text === $text_def) {
                    return 0;
                }
                break;
        }

        dd(microtime(true) - $time_start);
    }
}
