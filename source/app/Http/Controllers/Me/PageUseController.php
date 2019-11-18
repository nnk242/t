<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Jobs\Service\ServiceSharePage;
use App\Model\UserRolePage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PageUseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $pages = UserRolePage::whereuser_child(Auth::id())->wherestatus(1)->wheretype(1)->get();
        $page_use = gettype(json_decode(Auth::user()->page_use)) === 'array' ? json_decode(Auth::user()->page_use) : [];
        return view('pages.me.page-use', compact('pages', 'page_use'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'arr_user_page_id' => 'required|array'
            ], [
            'required' => ':attribute phải có dữ liệu'
        ], [
                'arr_user_page_id' => 'Page'
            ]
        );

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        $arr_user_page_id = $request->arr_user_page_id;

        $data = [
            'page_use' => json_encode($arr_user_page_id)
        ];

        if (count($arr_user_page_id) == 1) {
            $data = array_merge(['page_selected' => $arr_user_page_id[0]], $data);
        }

        User::updateorcreate(['_id' => Auth::id()], $data);

        return redirect()->back()->with('success', 'Gửi thành công');
    }
}
