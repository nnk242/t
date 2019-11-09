<?php

namespace App\Http\Controllers;

use App\Jobs\ServiceSharePage;
use App\Model\Page;
use App\Model\UserAndPage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class MeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
//        $log = new Logger(date('Y-m-d H:i:s ') . 'log');
//        $log->pushHandler(new StreamHandler(storage_path('fb/' . date('Y-m-d') . '-fb.log')), Logger::INFO);
//        $log->info('OrderLog', ['$arr_log']);
        $pages = Page::whereuser_id(Auth::id())->get();
        return view('pages.me.index', compact('pages'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'arr_page_id' => 'required|array',
                'email' => 'required'
            ], [
            'required' => ':attribute phải có dữ liệu',
            'array' => ':attribute phải là 1 array',
        ], [
                'arr_page_id' => 'Page',
                'email' => 'Email'
            ]
        );

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        $arr_page_id = $request->arr_page_id;
        $email = $request->email;

        $arr_email = explode(',', $email);
        $this->dispatch(new ServiceSharePage(['arr_page_id' => $arr_page_id, 'arr_email' => $arr_email]));

        return redirect()->back()->with('success', 'Gửi thành công');
    }

    public function managerShare() {
        $user_and_page = UserAndPage::whereuser_parent(Auth::id())->get();
        return view('pages.me.manager-share', compact('user_and_page'));
        foreach ($user_and_page as $value) {
            dd($value->page);
        }
    }

    public function getAccessToken()
    {
        $facebookScope = [
            'email',
            'manage_pages',
            'user_videos',
            'user_posts',
            'publish_video',
            'publish_pages',
            'groups_access_member_info',
            'pages_manage_instant_articles',
            'pages_show_list',
            'publish_to_groups',
            'read_page_mailboxes',
            'pages_messaging'
        ];

        try {
            return Socialite::driver('facebook')->scopes($facebookScope)->redirect();
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'Chưa thể cập nhật hãy thông báo cho quản trị viên!');
        }
    }

    public function setAccessToken()
    {
        try {
            $data = Socialite::driver('facebook')->user();

            $user = User::findorfail(Auth::id());
            $user->update([
                'access_token' => $data->token,
                'facebook_id' => $data->id
            ]);
            return redirect()->route('me.index')->with('success', 'Lấy access token thành công!');
        } catch (\Exception $exception) {
            return redirect()->route('me.index')->with('error', 'Code: ' . $exception->getCode() . '. Message:' . $exception->getMessage());
        }

    }
}
