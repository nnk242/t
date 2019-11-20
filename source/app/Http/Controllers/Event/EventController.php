<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;

use App\Model\FbProcess;
use Illuminate\Http\Request;

class EventController extends Controller
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
