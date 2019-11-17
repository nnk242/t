<?php

namespace App\Http\Controllers\Me;

use App\Http\Controllers\Controller;
use App\Jobs\Service\ServiceSharePage;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShareController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $pages = Page::whereuser_id(Auth::id())->get();
        return view('pages.me.share', compact('pages'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'arr_user_page_id' => 'required|array',
                'arr_email' => 'required|array'
            ], [
            'required' => ':attribute phải có dữ liệu',
            'array' => ':attribute phải là 1 array',
        ], [
                'arr_user_page_id' => 'Page',
                'arr_email' => 'Email'
            ]
        );

        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }
        $arr_user_page_id = $request->arr_user_page_id;

        $arr_email = $request->arr_email;

        $this->dispatch(new ServiceSharePage(['arr_user_page_id' => $arr_user_page_id, 'arr_email' => $arr_email, 'user_id' => Auth::id()]));

        return redirect()->back()->with('success', 'Gửi thành công');
    }
}
