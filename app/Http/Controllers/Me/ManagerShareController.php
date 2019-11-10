<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Jobs\ServiceSharePage;
use App\Model\Page;
use App\Model\UserAndPage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

class ManagerShareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = UserAndPage::whereuser_parent(Auth::id())->paginate(1);
        $headers = ['STT', 'ID Page', 'Tên page', 'Hình ảnh', 'Người nhận', 'Thể loại', 'status', 'Ngày thêm', '###'];
        return view('pages.me.manager-share', compact('data', 'headers'));
    }

    public function store(Request $request)
    {
        try {
            $id = $request->id;
            UserAndPage::findorfail($id)->update(['status' => $request->is_checked === 'true' ? 0 : 1]);
            $user_and_page = UserAndPage::findorfail($id);
            return $user_and_page->status;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }
}
