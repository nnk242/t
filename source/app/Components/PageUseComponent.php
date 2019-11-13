<?php

namespace App\Components;

use App\Model\Page;
use App\Model\UserAndPage;
use Illuminate\Support\Facades\Auth;

class PageUseComponent
{
    public static function getPage()
    {
        return Page::whereuser_id(Auth::id())->get();
    }

    public static function getPageChild()
    {
        $arr_user_page_id = UserAndPage::wheretype(1)->wherestatus(1)->whereuser_child(Auth::id())->pluck('page_id')->toArray();
        return Page::whereIn('id', $arr_user_page_id)->get();
    }
}
