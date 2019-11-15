<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use App\Model\PersistentMenu;
use Illuminate\Http\Request;

class PersistentMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $menu_1 = PersistentMenu::where(['level_menu' => '1', 'page_id' => '$page_id'])->orderby('priority', 'ASC')->get();
        $menu_2 = PersistentMenu::where(['level_menu' => '2', 'page_id' => '$page_id'])->orderby('priority', 'ASC')->get();
        $menu_3 = PersistentMenu::where(['level_menu' => '3', 'page_id' => '$page_id'])->orderby('priority', 'ASC')->get();
        return view('pages.setting.index', compact('menu_1', 'menu_2', 'menu_3'));
    }

    public function store(Request $request)
    {
        dd($request->all());
    }
}
