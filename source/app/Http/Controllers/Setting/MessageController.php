<?php

namespace App\Http\Controllers\Setting;

use App\Components\Page\PageComponent;
use App\Components\Process\DateComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\UserRolePage;
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
//        dd(1574228872, time());
        $bot_message_heads = BotMessageHead::wherefb_page_id(Auth::user()->page_selected)->orderby('created_at', 'DESC')->limit(5)->get();
        $header_bot_heads = ['STT', 'ID Page', 'Tên page', ['label' => 'Nội dung người dùng', 'class' => 'center'], 'Ngày cập nhật', 'Ngày thêm', '###'];
        return view('pages.setting.message.index', compact('bot_message_heads', 'header_bot_heads'));
    }

    public function store(Request $request)
    {
        $type_message = $request->type_message;
        $type_notify = $request->type_notify;
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
                    'bot_message_head_id' => $request->bot_message_head_id
                ]);
                UpdateOrCreate::botMessageReply($data);
                return redirect()->back()->with('success', 'Thêm tin nhắn trả lời thành công!');
        }
        return $request->all();
    }

    public function show($id)
    {
        switch ($id) {
            case 'call-bot-message':
                $headers = ['STT', 'ID Page', 'Tên page', ['label' => 'Nội dung người dùng', 'class' => 'center'], 'Ngày cập nhật', 'Ngày thêm', '###'];
                $bot_message_heads = BotMessageHead::wherefb_page_id(Auth::user()->page_selected)->orderby('created_at', 'DESC')->paginate(10);
                return view('pages.setting.message.show.call-bot-message', compact('bot_message_heads', 'headers'));
                break;
        }
    }

    public function messageReply(Request $request)
    {
        return BotMessageReply::wherefb_page_id(Auth::user()->page_selected)->where('text', 'LIKE', "%$request->text%")->limit(10)->get();
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
                'text' => 'required|max:20'
            ], [
            'required' => ':attribute phải có dữ liệu',
            'max' => 'Tin gửi người dùng không được quá 20 ký tự'
        ]);
        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }

        if ($request->type === 'event') {
            $data = ['type' => 'event'];
            $time_open = $request->time_open;
            $data = array_merge($data, DateComponent::timeOpen($time_open));
            ###
            $date_active = $request->date_active;
            $time_active = $request->time_active;
            $data = array_merge($data, DateComponent::date($date_active, $time_active));
        } else {
            $data = ['type' => 'normal'];
        }
        $data = array_merge($data, [
            'text_error_begin_time_active_id' => $request->text_error_begin_time_active_id,
            'text_error_end_time_active_id' => $request->text_error_end_time_active_id,
            'text_error_time_open_id' => $request->text_error_time_open_id,
            'text_error_gift_id' => $request->text_error_gift_id,
            'text_success_id' => $request->text_success_id,
            'text' => $request->text
        ]);

        if (UpdateOrCreate::botMessageHead($data)) {
            return redirect()->back()->with('success', 'Thêm tin nhắn thành công!');
        } else {
            return redirect()->back()->with('error', 'Thêm tin nhắn không thành công!');
        }
    }

    public function destroyMessageHead($id)
    {
        if (in_array(Auth::user()->page_selected, PageComponent::passUserRole(Auth::id()))) {
            BotMessageHead::findorfail($id)->delete();
            return redirect()->back()->with('success', 'Xóa tin nhắn thành công!');
        } else {
            abort(404);
        }
    }
}
