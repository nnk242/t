<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Model\UserRolePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerShareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $data = UserRolePage::whereuser_parent(Auth::id())->where('user_child', '<>', Auth::id())->paginate(10);
        $headers = ['STT', 'ID Page', 'Tên page', 'Hình ảnh', 'Người nhận', 'Thể loại', 'status', 'Ngày thêm', '###'];
        return view('pages.me.manager-share', compact('data', 'headers'));
    }

    public function store(Request $request)
    {
        try {
            $id = $request->id;
            UserRolePage::findorfail($id)->update(['status' => $request->is_checked === 'true' ? 0 : 1]);
            $user_role_page = UserRolePage::findorfail($id);
            return $user_role_page->status;
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
    }

    public function destroy($id)
    {
        UserRolePage::whereuser_parent(Auth::id())->where_id($id)->firstorfail()->delete();
        return redirect()->back()->with('success', 'Xoá page thành công!');
    }
}
