<?php

namespace App\Http\Controllers\Setting;

use App\Components\Process\DateComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('pages.setting.message.index');
    }

    public function store(Request $request)
    {
        $type_message = $request->type_message;
        $type_notify = $request->type_notify;
        $data = [];
        if ($type_notify === 'timer') {
            $data = ['type_notify' => 'timer'];
            $time_open = $request->time_open;
            $data = array_merge($data, DateComponent::timeOpen($time_open));
            ###
            $date_active = $request->date_active;
            $time_active = $request->time_active;
            $data = array_merge($data, DateComponent::date($date_active, $time_active));
        } else {
            $data = ['type_notify' => 'normal'];
        }

        switch ($type_message) {
            case 'text_messages':
                $validate = Validator::make(
                    $request->all(),
                    [
                        'text' => 'required'
                    ], [
                    'required' => ':attribute phải có dữ liệu'
                ]);

                if ($validate->fails()) {
                    return redirect()->back()->with('error', $validate->errors()->first());
                }
                $data = array_merge($data, [
                    'text' => $request->text,
                    'bot_message_head_id' => $request->bot_message_head_id,
                ]);
                UpdateOrCreate::botMessageReply($data);
                return redirect()->back()->with('success', 'Thêm tin nhắn trả lời thành công!');
        }
        return $request->all();
    }

    public function messageHead(Request $request)
    {
        return BotMessageHead::wherefb_page_id(Auth::user()->page_selected)->where('text', 'LIKE', "%$request->text%")->limit(10)->get();
    }

    public function storeMessageHead(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'text' => 'required'
            ], [
            'required' => ':attribute phải có dữ liệu'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        if (UpdateOrCreate::botMessageHead(['text' => $request->text])) {
            return redirect()->back()->with('success', 'Thêm tin nhắn thành công!');
        } else {
            return redirect()->back()->with('error', 'Thêm tin nhắn không thành công!');
        }
    }
}
