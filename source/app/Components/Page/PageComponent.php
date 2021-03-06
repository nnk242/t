<?php

namespace App\Components\Page;

use App\Model\UserRolePage;
use Illuminate\Support\Facades\Auth;

class PageComponent
{
    public static function pageUse()
    {
        $page_use = gettype(json_decode(Auth::user()->page_use)) === 'array' ? json_decode(Auth::user()->page_use) : [];
        $user_role_pages = UserRolePage::whereuser_child(Auth::id())->whereIn('fb_page_id', $page_use)->wherestatus(1)->wheretype(1)->get();
        return $user_role_pages;
    }

    public static function arrPageUse()
    {
        $page_use = gettype(json_decode(Auth::user()->page_use)) === 'array' ? json_decode(Auth::user()->page_use) : [];
        $user_role_pages = UserRolePage::whereuser_child(Auth::id())->whereIn('fb_page_id', $page_use)->wherestatus(1)->wheretype(1)->pluck('fb_page_id')->toArray();
        return $user_role_pages;
    }

    public static function pageSelected()
    {
        return UserRolePage::whereuser_child(Auth::id())->wherefb_page_id(Auth::user()->page_selected)->wherestatus(1)->wheretype(1)->first();
    }

    public static function getPages()
    {
        return UserRolePage::whereuser_child(Auth::id())->wherestatus(1)->wheretype(1)->get();
    }

    public static function passUserRole($auth_id)
    {
        return UserRolePage::whereuser_child($auth_id)->wherestatus(1)->wheretype(1)->pluck('fb_page_id')->toArray();
    }
}
