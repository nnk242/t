<?php

namespace App\Http\Controllers\Process;

use App\Http\Controllers\Controller;

use App\Model\FbProcess;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        dd(FbProcess::orderby('id', 'DESC')->first()->status);
        return view('pages.setting.index');
    }
}
