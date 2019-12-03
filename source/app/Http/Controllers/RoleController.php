<?php

namespace App\Http\Controllers;

use App\Components\Facebook\Facebook;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Jobs\Console\AddUserPage;
use App\Model\FbProcess;
use App\Model\Page;
use App\Model\User;
use App\Model\UserPage;
use App\Model\UserRolePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Scottybo\LaravelFacebookSdk\LaravelFacebookSdk;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = User::paginate(10);
        $headers = [
            'STT', 'Tên', 'Email', 'Hình ảnh', 'Thể loại', 'Ngày cập nhật', '###'];

        return view('pages.role.index', compact('data', 'headers'));
    }

    public function store(Request $request)
    {
        User::updateorcreate(['email' => $request->email], [
            'email' => $request->email,
            'role' => $request->role
        ]);
        return redirect()->back()->with('success', 'Thêm tài khoản thành công!');;
    }

    public function destroy($id)
    {
        if (Auth::user()->role === 'admin') {
            if (Auth::id() !== $id) {
                User::findorfail($id)->delete();
                return redirect()->back()->with('success', 'Xóa tài khoản thành công!');
            }
        }

        return abort(404);
    }
}
