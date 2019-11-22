<?php

namespace App\Components\Common;

class TextComponent
{
    public static function stripUnicode($str)
    {
        if (!$str) return '';
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ|å|ä|æ|ā|ą|ǻ|ǎ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ|Å|Ä|Æ|Ā|Ą|Ǻ|Ǎ',
            'ae' => 'ǽ',
            'AE' => 'Ǽ',
            'c' => 'ć|ç|ĉ|ċ|č',
            'C' => 'Ć|Ĉ|Ĉ|Ċ|Č',
            'd' => 'đ|ď',
            'D' => 'Đ|Ď',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|ë|ē|ĕ|ę|ė',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ|Ë|Ē|Ĕ|Ę|Ė',
            'f' => 'ƒ',
            'F' => '',
            'g' => 'ĝ|ğ|ġ|ģ',
            'G' => 'Ĝ|Ğ|Ġ|Ģ',
            'h' => 'ĥ|ħ',
            'H' => 'Ĥ|Ħ',
            'i' => 'í|ì|ỉ|ĩ|ị|î|ï|ī|ĭ|ǐ|į|ı',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị|Î|Ï|Ī|Ĭ|Ǐ|Į|İ',
            'ij' => 'ĳ',
            'IJ' => 'Ĳ',
            'j' => 'ĵ',
            'J' => 'Ĵ',
            'k' => 'ķ',
            'K' => 'Ķ',
            'l' => 'ĺ|ļ|ľ|ŀ|ł',
            'L' => 'Ĺ|Ļ|Ľ|Ŀ|Ł',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|ö|ø|ǿ|ǒ|ō|ŏ|ő',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ|Ö|Ø|Ǿ|Ǒ|Ō|Ŏ|Ő',
            'Oe' => 'œ',
            'OE' => 'Œ',
            'n' => 'ñ|ń|ņ|ň|ŉ',
            'N' => 'Ñ|Ń|Ņ|Ň',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|û|ū|ŭ|ü|ů|ű|ų|ǔ|ǖ|ǘ|ǚ|ǜ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự|Û|Ū|Ŭ|Ü|Ů|Ű|Ų|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ',
            'r' => 'ŕ|ŗ|ř',
            'R' => 'Ŕ|Ŗ|Ř',
            's' => 'ß|ſ|ś|ŝ|ş|š',
            'S' => 'Ś|Ŝ|Ş|Š',
            't' => 'ţ|ť|ŧ',
            'T' => 'Ţ|Ť|Ŧ',
            'w' => 'ŵ',
            'W' => 'Ŵ',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ|ÿ|ŷ',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ|Ÿ|Ŷ',
            'z' => 'ź|ż|ž',
            'Z' => 'Ź|Ż|Ž'
        );
        foreach ($unicode as $khongdau => $codau) {
            $arr = explode("|", $codau);
            $str = str_replace($arr, $khongdau, $str);
        }
        return $str;
    }

    public static function passMessage($text, $def)
    {
        try {
            $is_pass = false;
            $text = strtolower(self::stripUnicode($text));
            $def = strtolower(self::stripUnicode($def));
            $strlen_def = strlen($def);
            if ($def[0] === '!' && $strlen_def >= 2) {
                if ($strlen_def === 2) {
                    if ($def === "!'") {
                        $is_pass = true;
                    }
                } elseif ($strlen_def > 2) {
                    if ($def[0] === '!' and $def[1] === "'") {
                        $arr_text_def = explode("!'", $def);

                        try {
                            unset($arr_text_def[0]);
                        } catch (\Exception $exception) {
                        }

                        $def = implode($arr_text_def, "!'");
                        $arr_text_def = explode(",", $def);

                        $count_arr_text_def = count($arr_text_def);
                        if ($count_arr_text_def <= 1) {
                            if (gettype(strpos($text, $def)) === 'integer') {
                                $is_pass = true;
                            }
                        } else {
                            if ($count_arr_text_def === 3) {
                                if (gettype(strpos($text, $arr_text_def[0])) === 'integer') {
                                    $strlen_text = strlen($text);
                                    if ($arr_text_def[1] === '*') {
                                        $strlen_min = 0;
                                    } else {
                                        $strlen_min = (int)$arr_text_def[1];
                                    }
                                    if ($arr_text_def[2] === '*') {
                                        $strlen_max = 999999;
                                    } else {
                                        $strlen_max = (int)$arr_text_def[2];
                                    }

                                    if ($strlen_text >= $strlen_min && $strlen_text <= $strlen_max) {
                                        $is_pass = true;
                                    }
                                }
                            }
                        }
                    }
                }
            } else {
                if ($text === $def) {
                    $is_pass = true;
                }
            }
            return $is_pass;
        } catch (\Exception $exception) {
            return false;
        }
    }

    public static function payload($str)
    {
        return str_replace(' ', '_', strtoupper(self::stripUnicode($str))) . '_PAYLOAD';
    }
}
