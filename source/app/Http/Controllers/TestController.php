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
        $text = "Suy ra một điều rằng Trâm. Anh thích 64 :)";
        $text_def = "!'S";

        $strlen_def = strlen($text_def);

        $check_text = false;

        switch (true) {
            case $strlen_def === 2:
                if ($text_def === "!'") {
                    $check_text = true;
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
                            $check_text = true;
                        }
                    } else {

                    }
//                    dd($text_def);
                    break;
                }
            default:
                if ($text === $text_def) {
                    $check_text = true;
                }
                break;
        }
        return $check_text;
    }
}
