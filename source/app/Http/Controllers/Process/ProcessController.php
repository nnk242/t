<?php

namespace App\Http\Controllers\Process;

use App\Components\Common\TextComponent;
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
//        '9029 + Khan Gamota';
//        dd(TextComponent::passMessage('9029 + Khan Gamota', "!'9029"));
//        dd(FbProcess::orderby('id', 'DESC')->first()->status);
        return view('pages.process.index');
    }
}
