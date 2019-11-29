<?php

namespace App\Http\Controllers;

use App\Components\Facebook\Facebook;
use App\Components\Page\PageComponent;
use App\Components\Process\DateComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Jobs\Console\AddUserPage;
use App\Model\BotMessageReply;
use App\Model\BotPayloadElement;
use App\Model\BotQuickReply;
use App\Model\BroadcastMessenger;
use App\Model\BroadcastPage;
use App\Model\FbProcess;
use App\Model\Page;
use App\Model\User;
use App\Model\UserPage;
use App\Model\UserRolePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Scottybo\LaravelFacebookSdk\LaravelFacebookSdk;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
//        dd(FbProcess::wherestatus(1)->limit(2)->get());
//        Artisan::call('command:AddUserPage --page_user_id=' . "2016433678466136" . ' --fb_page_id=' . "1086408651532297");
        $data = BroadcastMessenger::whereuser_id(Auth::id())->orderby('created_at', 'DESC')->paginate(10);
        $pages = PageComponent::getPages();
        $headers = ['STT', 'Tên', 'Page', ['class' => 'center', 'label' => 'T/g tương tác'],
            ['class' => 'center', 'label' => 'Thời gian gửi'], ['class' => 'center', 'label' => 'Status'], 'T/g tạo', '###'];

        return view('pages.message.index', compact('data', 'headers', 'pages'));
    }

    public function edit($id)
    {
        $pages = PageComponent::getPages();
        $broadcast_messenger = BroadcastMessenger::findorfail($id);
        return view('pages.message.edit', compact('broadcast_messenger', 'pages'));;
    }

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'bot_message_reply_id' => 'required',
                'arr_user_page_id' => 'required|array'
            ], [
            'required' => ':attribute phải có dữ liệu'
        ], [
            'arr_user_page_id' => 'Page',
            'bot_message_reply_id' => 'Bot reply'
        ]);

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        $data = [
            'bot_message_reply_id' => $request->bot_message_reply_id,
            'time_interactive' => (int)$request->time_interactive,
            'status' => (int)$request->status != 0 ? 1 : 0,
            'user_id' => Auth::id()
        ];
        $date_active = $request->date_active;
        $time_active = $request->time_active;
        $data = array_merge($data, DateComponent::date($date_active, $time_active));
        $id = $request->_id;
        if ($id) {
            BroadcastMessenger::findorfail($id)->update($data);
            $arr_broadcast_fb_page_id = BroadcastPage::wherebroadcast_messenger_id($id)->pluck('fb_page_id')->toArray();
            foreach ($request->arr_user_page_id as $value) {
                if (in_array($value, $arr_broadcast_fb_page_id)) {
                    BroadcastPage::updateorcreate([
                        'broadcast_messenger_id' => $id,
                        'fb_page_id' => $value
                    ]);
                } else {
                    $broadcast_page = BroadcastPage::where([
                        'broadcast_messenger_id' => $id,
                        'fb_page_id' => $value
                    ])->first();
                    if ($broadcast_page) {
                        $broadcast_page->delete();
                    }
                }
            }
        } else {
            $broadcast_messager = BroadcastMessenger::updateorcreate($data);
            foreach ($request->arr_user_page_id as $value) {
                BroadcastPage::updateorcreate([
                    'broadcast_messenger_id' => $broadcast_messager->_id,
                    'fb_page_id' => $value
                ]);
            }
        }

        return redirect()->back()->with('success', 'Gửi thành công');
    }

    public function show($id)
    {

        return $is_message ? redirect()->back()->with('success', 'Cập nhật page thành công!') : redirect()->back()->with('warning', $message);
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'admin') {
            BroadcastMessenger::where_id($id)->firstorfail()->delete();
        } else {
            BroadcastMessenger::whereuser_id(Auth::id())->where_id($id)->firstorfail()->delete();
        }

        return redirect()->back()->with('success', 'Xóa thành công!');
    }

    public function searchData(Request $request)
    {
        $query = $request->input('query');
        $bot_message_replies = BotMessageReply::where('text', 'LIKE', "%$query%")->limit(10)->get();
        $payload_elements = BotPayloadElement::where('title', 'LIKE', "%$query%")->orwhere('subtitle', 'LIKE', "%$query%")->limit(10)->get();
        $quick_replies = BotQuickReply::where('title', 'LIKE', "%$query%")->limit(10)->get();
        $data = [];
        $key = 0;
        foreach ($bot_message_replies as $key => $value) {
            $text = '';
            if ($value->text) {
                $text = $value->text;
            }

            if ($text) {
                $text = $text . ' - ' . $value->title;
            } else {
                $text = $value->title;
            }

            if ($text) {
                $text = $text . ' - ' . $value->type_message;
            } else {
                $text = $value->type_message;
            }

            if ($text) {
                $text = $text . ' - ' . $value->created_at;
            } else {
                $text = $value->created_at;
            }

            $data[$key]['value'] = $text;
            $data[$key]['data'] = $value->_id;
        }

        foreach ($payload_elements as $k => $value) {
            $text = '';
            if ($value->text) {
                $text = $value->text;
            }

            if ($text) {
                $text = $text . ' - ' . $value->title;
            } else {
                $text = $value->title;
            }

            if ($text) {
                $text = $text . ' - ' . $value->botMessageReply->type_message;
            } else {
                $text = $value->type_message;
            }

            if ($text) {
                $text = $text . ' - ' . $value->created_at;
            } else {
                $text = $value->created_at;
            }

            $data[$k + $key + 1]['value'] = $text;
            $data[$k + $key + 1]['data'] = $value->botMessageReply->_id;
        }

        foreach ($quick_replies as $kh => $value) {
            $text = $value->title;

            if ($text) {
                $text = $text . ' - ' . $value->botMessageReply->type_message;
            } else {
                $text = $value->type_message;
            }

            if ($text) {
                $text = $text . ' - ' . $value->created_at;
            } else {
                $text = $value->created_at;
            }

            $data[$k + $key + 1 + $kh]['value'] = $text;
            $data[$k + $key + 1 + $kh]['data'] = $value->botMessageReply->_id;
        }
        return '{"suggestions":' . json_encode($data) . '}';
    }

    public function updateStatus($id, Request $request)
    {
        BroadcastMessenger::where_id($id)->firstorfail()->update(['status' => (int)$request->is_checked ? 1 : 0]);
        return (int)$request->is_checked ? 0 : 1;
    }
}
