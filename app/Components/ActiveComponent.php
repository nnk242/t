<?php

namespace App\Components;

class ActiveComponent
{
    public static function isActiveSide($query)
    {
        $url = request()->url();
        $http_scheme_and_host = request()->getSchemeAndHttpHost();
        $arr_query = explode($http_scheme_and_host, $url);
        if (gettype($query) === 'string') {
            return in_array($query, $arr_query);
        } elseif (gettype($query) === 'array') {
            foreach ($query as $item) {
                if (in_array($item, $arr_query)) {
                    return true;
                }
            }
        }
        return false;
    }
}
