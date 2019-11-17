<?php

namespace App\Http\Controllers;

use App\Components\Facebook\Facebook;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Jobs\Console\AddUserPage;
use App\Model\FbProcess;
use App\Model\Page;
use App\Model\UserPage;
use App\Model\UserRolePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
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
        UpdateOrCreate::userRolePage([
            'fb_page_parent' => $data['id'] . '_' . Auth::id(),
            'fb_page_id' => $data['id'],
            'user_parent' => Auth::id(),
            'user_child' => Auth::id(),
            'type' => 1,
            'status' => 1
        ]);
        return UpdateOrCreate::page([
            'fb_page_id' => $data['id'],
            'name' => $data['name'],
            'picture' => $data['picture']['data']['url'],
            'category' => $data['category'],
            'access_token' => $data['access_token'],
            'run_conversations' => 1
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
                        'subscribed_fields' => 'messages,messaging_postbacks,messaging_optins,message_deliveries,message_reads,messaging_payments,messaging_pre_checkouts,messaging_checkout_updates,messaging_account_linking,messaging_referrals,message_echoes,messaging_game_plays,standby,messaging_handovers,messaging_policy_enforcement,message_reactions'
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
//        dd(FbProcess::wherestatus(1)->limit(2)->get());
//        Artisan::call('command:AddUserPage --page_user_id=' . "2016433678466136" . ' --fb_page_id=' . "1086408651532297");
        $data = UserRolePage::whereuser_child(Auth::id())->whereuser_parent(Auth::id())->paginate(10);
//        $data = Page::WhereIn('fb_page_id', $arr_user_page_id)->orderby('create', 'DESC')->paginate(10);
        $headers = [
//            ['id' => 'check-i', 'label' => '###'],
            'STT', 'ID Page', 'Tên page', 'Hình ảnh', 'Thể loại', 'Ngày cập nhật', 'Ngày thêm', '###'];

        return view('pages.page.index', compact('data', 'headers'));
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
        $this->dispatch(new AddUserPage());
        return $is_message ? redirect()->back()->with('success', 'Cập nhật page thành công!') : redirect()->back()->with('warning', $message);
    }

    public function show($id)
    {
        $user_page = $this->model()::wherefb_page_id($id)->firstorfail();
        try {
            $user = Auth::user();
            $data = Facebook::get($user->access_token, 'me/accounts?fields=picture,access_token,name,id,category&limit=150&page_id=' . $user_page->page->fb_page_id);
            $subscribed_fields = $this->subscribedFields($data, 3, [$user_page->page->fb_page_id]);
            $message = $subscribed_fields['message'];
            $is_message = $subscribed_fields['is_message'];
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }

        return $is_message ? redirect()->back()->with('success', 'Cập nhật page thành công!') : redirect()->back()->with('warning', $message);
    }

    public function destroy($id)
    {
        $page = $this->model()::wherefb_page_id($id)->firstorfail();
        if (Auth::id() === $page->user_id) {
            $page->delete();
            return redirect()->back()->with('success', 'Xoá page thành công!');
        } else {
            UserRolePage::wheretype(1)->wherestatus(1)->wherefb_page_id($page->fb_page_id)
                ->whereuser_child(Auth::id())->firstorfail()->update(['type' => 4]);
            return redirect()->back()->with('success', 'Xoá page thành công!');
        }
        return abort(404);
    }
}
