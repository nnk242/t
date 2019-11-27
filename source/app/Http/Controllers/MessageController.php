<?php

namespace App\Http\Controllers;

use App\Components\Facebook\Facebook;
use App\Components\Process\DateComponent;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Jobs\Console\AddUserPage;
use App\Model\BotMessageReply;
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
        $data = BroadcastMessenger::whereuser_id(Auth::id())->paginate(10);
//        $data = Page::WhereIn('fb_page_id', $arr_user_page_id)->orderby('create', 'DESC')->paginate(10);
        $pages = UserRolePage::whereuser_child(Auth::id())->wherestatus(1)->wheretype(1)->get();
        $headers = [
//            ['id' => 'check-i', 'label' => '###'],
            'STT', 'Tên', 'Email', 'Hình ảnh', 'Thể loại', 'Ngày cập nhật', '###'];

        return view('pages.message.index', compact('data', 'headers', 'pages'));
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
        $broadcast_messager = BroadcastMessenger::updateorcreate($data);
        foreach ($request->arr_user_page_id as $value) {
            $broadcast_page = BroadcastPage::updateorcreate([
                'broadcast_messenger_id' => $broadcast_messager->_id,
                'fb_page_id' => $value
            ]);
        }
        return redirect()->back()->with('success', 'Gửi thành công');
    }

    public function show($id)
    {
        $user_page = $this->model()::wherefb_page_id($id)->firstorfail();
        try {
            $user = Auth::user();
            $data = Facebook::get($user->access_token, 'me/accounts?fields=picture,access_token,name,id,category&limit=150&page_id=' . $user_page->page->fb_page_id);
            $subscribed_fields = $this->subscribedFields($data, 3, [$user_page->page->fb_page_id]);
            $message = $subscribed_fields['message'];
            $is_message = $subscribed_fields['is_message'];
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return $is_message ? redirect()->back()->with('success', 'Cập nhật page thành công!') : redirect()->back()->with('warning', $message);
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'admin') {
            if (Auth::id() !== $id) {
                User::findorfail($id)->delete();
            }
        }

        return abort(404);
    }

    public function searchData(Request $request)
    {
        $query = $request->input('query');
        $bot_message_replies = BotMessageReply::where('text', 'LIKE', "%$query%")->orwhere('title', 'LIKE', "%$query%")->limit(10)->get();
        $data = [];
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
        return '{"suggestions":' . json_encode($data) . '}';
    }
}
