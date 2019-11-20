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

    public function index()
    {
        return view('pages.event.index');
    }
}
