<?php

namespace App\Components;

class ActiveComponent
{
    public static function isActiveSide($query)
    {
        $url = request()->url();
        $http_scheme_and_host = request()->getSchemeAndHttpHost();
        $arr_query = explode($http_scheme_and_host, $url);
        return in_array($query, $arr_query);
    }
}
