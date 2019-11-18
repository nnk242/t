<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        dd(FbProcess::orderby('id', 'DESC')->first()->status);
        return view('pages.setting.message.index');
    }
}
