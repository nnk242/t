<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;

use App\Model\BotMessageReply;
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
        dd(BotMessageReply::all());
        return view('pages.event.index');
    }
}