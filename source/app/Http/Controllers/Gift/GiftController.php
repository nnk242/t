<?php

namespace App\Http\Controllers\Gift;

use App\Components\Page\PageComponent;
use App\Http\Controllers\Controller;
use App\Model\BotMessageHead;
use App\Model\Gift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class GiftController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $page_selected = PageComponent::pageSelected();
        if (isset($page_selected)) {
            $page_selected = $page_selected->fb_page_id;
        } else {
            return abort(404);
        }

        $bot_message_heads = BotMessageHead::wherefb_page_id($page_selected)->wheretype('event')
            ->orderby('created_at', 'DESC')->paginate(5);

//        foreach ($bot_message_heads as $value) {
//            dd($value->gifts->where('amount', '<>', 0)->first());
//        }

        $header_bot_heads = ['STT', 'Page', ['label' => 'Nội dung', 'class' => 'center'], ['label' => 'Số lượng gift', 'class' => 'center'], 'Ngày thêm', '###'];
        return view('pages.gift.index', compact('gifts', 'bot_message_heads', 'header_bot_heads'));
    }

    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'bot_message_head_id' => 'required'
            ], [
            'required' => ':attribute phải có dữ liệu'
        ], [
            'bot_message_head_id' => 'Bot head'
        ]);
        $amount = (int)$request->amount ? (int)$request->amount : 0;
        $bot_message_head_id = $request->bot_message_head_id;
        if ($validate->fails()) {
            return redirect()->back()->with('error', $validate->errors()->first());
        }

        if ($request->type == 'file') {
            $file = $request->file('file');
            if (isset($file)) {
                try {
                    $data = Excel::toArray('', $file)[0];
                    $giftcodes = [];
                    $i = 0;
                    foreach ($data as $key => $excel) {
                        if ($excel[0]) {
                            if ($key % 200 == 0) {
                                $i += 1;
                            }
                            if (isset($excel[0])) {
                                $giftcodes[$i][] = [
                                    'code' => $excel[0],
                                    'amount' => $amount,
                                    'bot_message_head_id' => $bot_message_head_id
                                ];
                            }
                        }
                    }

                    foreach ($giftcodes as $giftcode) {
                        if (count($giftcode)) {
                            Gift::insert($giftcode);
                        }
                    }

                    return redirect()->back()->with('success', 'Thêm gift thành công!!!');
                } catch (\Exception $exception) {
                }
            }
        } else {
            $validate = Validator::make(
                $request->all(),
                [
                    'code' => 'required'
                ], [
                'required' => ':attribute phải có dữ liệu'
            ]);

            if ($validate->fails()) {
                return redirect()->back()->with('error', $validate->errors()->first());
            }
            $data = [
                'code' => $request->code,
                'amount' => $amount,
                'bot_message_head_id' => $bot_message_head_id
            ];

            Gift::create($data);

            return redirect()->back()->with('success', 'Thêm gift thành công!!!');
        }

        return redirect()->back()->with('error', 'Thêm gift không thành công!!!');
    }

    public function show($id)
    {
        return view('pages.gift.show');
    }
}
