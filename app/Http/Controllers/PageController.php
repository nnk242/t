<?php

namespace App\Http\Controllers;

use App\Components\Facebook;
use App\Model\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Scottybo\LaravelFacebookSdk\LaravelFacebookSdk;

class PageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private function model()
    {
        return Page::class;
    }

    private function updateOrCreate($data)
    {
        return $this->model()::updateorcreate(['user_id_fb_page_id' => Auth::id() . '_' . $data['id']], [
            'picture' => $data['picture']['data']['url'],
            'access_token' => $data['access_token'],
            'name' => $data['name'],
            'fb_page_id' => $data['id'],
            'user_id' => Auth::id(),
            'category' => $data['category']
        ]);
    }

    private function type($data, $type = null, $arr_fb_page_id = [])
    {
        switch ((int)$type) {
            case 1:
                $arr_fb_page_id = $this->model()::pluck('fb_page_id')->toArray();
                if (!in_array($data['id'], $arr_fb_page_id)) {
                    return $this->updateOrCreate($data);
                } else {
                    return false;
                }
            case 2:
                return false;
            case 3:
                if (in_array($data['id'], $arr_fb_page_id)) {
                    return $this->updateOrCreate($data);
                }
                return false;
            default:
                return $this->updateOrCreate($data);
        }
    }

    private function subscribedFields($data, $type, $arr_fb_page_id = [])
    {
        $message = '';
        $is_message = true;
        foreach ($data as $key => $value) {
            $run = $this->type($value, $type, $arr_fb_page_id);
            if ($run) {
                try {
                    Facebook::post($run->access_token, $run->fb_page_id . '/subscribed_apps', [
                        'subscribed_fields' => 'feed,conversations,messages,messaging_postbacks,message_deliveries,message_reads,messaging_referrals,message_echoes'
                    ]);
                } catch (\Exception $exception) {
                    $is_message = false;
                    $message = $message . $exception->getMessage() . '\n';
                }
            }
        }

        return [
            'message' => $message,
            'is_message' => $is_message
        ];
    }

    public function index()
    {
        $data = Page::paginate(1);
        $headers = [
//            ['id' => 'check-i', 'label' => '###'],
            'STT', 'ID Page', 'Tên page', 'Hình ảnh', 'Thể loại', 'Ngày cập nhật', 'Ngày thêm', '###'];

        return view('pages.pages.index', compact('data', 'headers'));
    }

    public function store(Request $request)
    {
        $type = (int)$request->type;

        $access_token = Auth::user()->access_token;
        if (!$access_token) {
            return redirect()->back()->with('warning', 'Cần cập nhật access token');
        }

        try {
            $data = Facebook::get($access_token, 'me/accounts?fields=picture,access_token,name,id,category&limit=150');
            $subscribed_fields = $this->subscribedFields($data, $type);
            $message = $subscribed_fields['message'];
            $is_message = $subscribed_fields['is_message'];
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return $is_message ? redirect()->back()->with('success', 'Cập nhật page thành công!') : redirect()->back()->with('warning', $message);
    }

    public function show(Page $page)
    {
        try {
            $user = Auth::user();
            $data = Facebook::get($user->access_token, 'me/accounts?fields=picture,access_token,name,id,category&limit=150&page_id=' . $page->fb_page_id);
            $subscribed_fields = $this->subscribedFields($data, 3, [$page->fb_page_id]);
            $message = $subscribed_fields['message'];
            $is_message = $subscribed_fields['is_message'];
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return $is_message ? redirect()->back()->with('success', 'Cập nhật page thành công!') : redirect()->back()->with('warning', $message);
    }

    public function destroy(Page $page)
    {
        if (Auth::id() === $page->user_id) {
            $page->delete();
            return redirect()->back()->with('success', 'Xoá page thành công!');
        }
        return abort(404);
    }
}
