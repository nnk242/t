<?php

namespace App\Http\Controllers\Setting;

use App\Components\Common\TextComponent;
use App\Components\Page\PageComponent;
use App\Components\Process\DateComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Model\BotElementButton;
use App\Model\BotMessageHead;
use App\Model\BotMessageReply;
use App\Model\BotPayloadElement;
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

    private function elementButton($button_title, $button_url, $button_type, $request_payload, $bot_payload_element_id, $bot_element_button_id = null)
    {
        $title = isset($button_title) ? $button_title : null;
        $url = isset($button_url) ? $button_url : null;
        $type = isset($button_type) ? $button_type : null;
        $payload = $title !== null ? TextComponent::payload($title) : null;
        if ($button_type === 'web_url') {
            $payload = null;
        } elseif ($button_type === 'postback') {
            $url = null;
        } elseif ($button_type === 'phone_number') {
            $url = null;
            $payload = isset($payload) ? $request_payload : $payload;
        }

        $data_bot_element_button = [
            'bot_payload_element_id' => $bot_payload_element_id,
            'type' => $type,
            'url' => $url,
            'title' => $title,
            'payload' => $payload
        ];
        if ($bot_element_button_id !== null) {
            $data_bot_element_button = array_merge($data_bot_element_button, ['_id' => $bot_element_button_id]);
        }
//        return $data_bot_element_button;
        return UpdateOrCreate::botElementButton($data_bot_element_button);
    }

    public function index(Request $request)
    {
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
            case 'assets_attachments':
                $validate = Validator::make(
                    $request->all(),
                    [
                        'attachment_payload_url' => 'required',
                        'attachment_type' => 'required'
                    ], [
                    'required' => ':attribute phải có dữ liệu'
                ]);

                if ($validate->fails()) {
                    return redirect()->back()->with('error', $validate->errors()->first());
                }
                $data = array_merge($data, [
                    'attachment_payload_url' => $request->attachment_payload_url,
                    'attachment_type' => $request->attachment_type,
                    'type_message' => 'assets_attachments',
                    'bot_message_head_id' => $request->bot_message_head_id
                ]);
                UpdateOrCreate::botMessageReply($data);
                return redirect()->back()->with('success', 'Thêm tin nhắn trả lời thành công!');
            case 'message_templates':
                $validate = Validator::make(
                    $request->all(),
                    [
                        'attachment_type' => 'required'
                    ], [
                    'required' => ':attribute phải có dữ liệu'
                ]);

                if ($validate->fails()) {
                    return redirect()->back()->with('error', $validate->errors()->first());
                }
                $data = array_merge($data, [
                    'type_message' => 'message_templates',
                    'attachment_type' => $request->attachment_type,
                    'bot_message_head_id' => $request->bot_message_head_id
                ]);
                dd($request->all());
                $bot_message_reply = UpdateOrCreate::botMessageReply($data);

                $data_bot_payload_element = [
                    'bot_message_reply_id' => $bot_message_reply->_id,
                    'title' => $request->title,
                    'image_url' => $request->image_url,
                    'subtitle' => $request->subtitle,
                    'default_action_type' => $request->default_action_type,
                    'default_action_url' => $request->default_action_url,
                    'default_action_messenger_extensions' => $request->default_action_messenger_extensions,
                    'default_action_messenger_webview_height_ratio' => $request->default_action_messenger_webview_height_ratio,
                    'group' => $request->group
                ];

                $bot_payload_element = UpdateOrCreate::botPayloadElement($data_bot_payload_element);

                $bot_element_buttons = BotElementButton::wherebot_payload_element_id($bot_payload_element->_id)->get();

                $button_type = isset($request->button_type) ? $request->button_type : null;
                $button_url = isset($request->button_url) ? $request->button_url : null;
                $button_title = isset($request->button_title) ? $request->button_title : null;
                if ($bot_element_buttons->count()) {
                    foreach ($bot_element_buttons as $k => $bot_element_button) {
                        if ($k >= 3) {
                            $button = BotElementButton::find($bot_element_button->_id);
                            if (isset($button)) {
                                $button->delete();
                            }
                            continue;
                        }

                        $this->elementButton(isset($button_title[$k]) ? $button_title[$k] : null,
                            isset($button_url[$k]) ? $button_url[$k] : null,
                            isset($button_type[$k]) ? $button_type[$k] : null,
                            isset($request->payload[$k]) ? $request->payload[$k] : null,
                            $bot_payload_element->_id, $bot_element_button->_id);
                    }
                    if ($k <= 2) {
                        for ($i = $k; $i <= 2; $i++) {
                            $this->elementButton(isset($button_title[$i]) ? $button_title[$i] : null,
                                isset($button_url[$i]) ? $button_url[$i] : null,
                                isset($button_type[$i]) ? $button_type[$i] : null,
                                isset($request->payload[$i]) ? $request->payload[$i] : null,
                                $bot_payload_element->_id, $bot_element_button->_id);
                        }
                    }
                } elseif (gettype($request->button_type) === 'array') {
                    foreach ($request->button_type as $k => $button_type) {
                        $this->elementButton(isset($button_title[$k]) ? $button_title[$k] : null,
                            isset($button_url[$k]) ? $button_url[$k] : null,
                            isset($button_type) ? $button_type : null,
                            isset($request->payload[$k]) ? $request->payload[$k] : null,
                            $bot_payload_element->_id);
                    }
                }
                return redirect()->back()->with('success', 'Thêm tin nhắn trả lời thành công!');

        }

        dd($request->all());
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
        return BotMessageHead::wherefb_page_id(Auth::user()->page_selected)->where('text', 'LIKE', "%$request->text%")->wheretype('normal')->limit(10)->get();
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
