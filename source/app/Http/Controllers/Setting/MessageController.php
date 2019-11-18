<?php

namespace App\Http\Controllers\Setting;

use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Http\Controllers\Controller;
use App\Model\BotMessageHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        return view('pages.setting.message.index');
    }

    public function messageHead(Request $request)
    {
        return BotMessageHead::wherefb_page_id(Auth::user()->page_selected)->where('text', 'LIKE', "%$request->text%")->limit(10)->get();
    }

    public function storeMessageHead(Request $request)
    {
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
        if (UpdateOrCreate::botMessageHead(['text' => $request->text])) {
            return redirect()->back()->with('success', 'Thêm tin nhắn thành công!');
        } else {
            return redirect()->back()->with('error', 'Thêm tin nhắn không thành công!');
        }
    }
}
