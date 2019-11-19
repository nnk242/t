<?php

namespace App\Components\Process;

class DateComponent
{
    public static function timeOpen($time_open)
    {
        $data = [];
        $date_now = date('Y-m-d');
        if (gettype($time_open) === "array") {
            if (!isset($time_open[0]) && !isset($time_open[1])) {
                return $data;
            }
            $date_min = $date_now . ' 00:00:00';
            $str_to_time_min = strtotime($date_min);
            $date_max = $date_now . ' 23:59:59';
            $str_to_time_max = strtotime($date_max);

            $time_open_begin = isset($time_open[0]) ? $date_now . ' ' . $time_open[0] . ':00' : $date_min;
            $time_open_end = isset($time_open[1]) ? $date_now . ' ' . $time_open[1] . ':00' : $date_max;
            $str_to_time_begin = strtotime($time_open_begin);
            $str_to_time_end = strtotime($time_open_end);

            if ($str_to_time_begin < $str_to_time_min) {
                $str_to_time_begin = $str_to_time_min;
            }

            if ($str_to_time_end > $str_to_time_max) {
                $str_to_time_end = $str_to_time_max;
            }

            if ($str_to_time_begin > $str_to_time_end) {
                $tamp = $str_to_time_begin;
                $str_to_time_begin = $str_to_time_end;
                $str_to_time_end = $tamp;
            }
            $str_to_time_begin = $str_to_time_begin - $str_to_time_min;
            $str_to_time_end = $str_to_time_end - $str_to_time_min;
            $data = array_merge($data, ['begin_time_open' => $str_to_time_begin, 'end_time_open' => $str_to_time_end]);
        }

        return $data;
    }

    public static function date($date_active, $time_active)
    {
        $data = [];
        if (gettype($date_active) === "array" && gettype($date_active) === "array") {
            if (isset($date_active[0])) {
                $end_time_active = null;
                if (isset($time_active[0])) {
                    $time_active_begin = $time_active[0] . ':00';
                } else {
                    $time_active_begin = '00:00:00';
                }

                $begin_time_active = strtotime($date_active[0] . ' ' . $time_active_begin);

                if (isset($date_active[1])) {
                    $time_active_end = $time_active[1] . ':59';
                    $end_time_active = strtotime($date_active[1] . ' ' . $time_active_end);
                    if ($begin_time_active > $end_time_active) {
                        $tamp = $begin_time_active;
                        $begin_time_active = $end_time_active;
                        $end_time_active = $tamp;
                    }
                }

                $data = array_merge($data, [
                    'begin_time_active' => $begin_time_active,
                    'end_time_active' => $end_time_active
                ]);
            }

        }
        return $data;
    }
}
