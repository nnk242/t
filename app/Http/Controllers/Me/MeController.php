<?php

namespace App\Http\Controllers\Me;

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
use App\Http\Controllers\Controller;

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

        $user_and_page = UserAndPage::whereuser_child(Auth::id())->wherestatus(1)->wheretype(0)->get();
        return view('pages.me.index', compact('pages', 'user_and_page'));
    }

    public function store(Request $request)
    {
        $user_and_page = UserAndPage::findorfail($request->id);
        if ($user_and_page->user_child === Auth::id()) {
            $user_and_page->update(
                [
                    'type' => $request->type
                ]
            );
        }
        return redirect()->back()->with('success', 'Đã set thành công');
    }

    public function managerShare()
    {
        $data = UserAndPage::whereuser_parent(Auth::id())->paginate(1);
        $headers = ['STT', 'ID Page', 'Tên page', 'Hình ảnh', 'Người nhận', 'Thể loại', 'status', 'Ngày chấp nhận', 'Ngày thêm', '###'];
        return view('pages.me.manager-share', compact('data', 'headers'));
    }

    public function share()
    {
        $pages = Page::whereuser_id(Auth::id())->get();
        return view('pages.me.share', compact('pages'));
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
