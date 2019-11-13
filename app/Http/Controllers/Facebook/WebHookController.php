<?php

namespace App\Http\Controllers;

use App\Components\Facebook;
use App\Model\Page;
use App\Model\UserAndPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Scottybo\LaravelFacebookSdk\LaravelFacebookSdk;

class WebHookController extends Controller
{
    public function webHook(Request $request)
    {
        $VERIFY_TOKEN = "test";
        // Parse the query params
        $mode = $request->hub_mode;
        $token = $request->hub_verify_token;
        $challenge = $request->hub_challenge;

        // Checks if a token and mode is in the query string of the request
        if ($mode && $token) {
            // Checks the mode and token sent is correct
            if ($mode === 'subscribe' && $token === $VERIFY_TOKEN) {
                // Responds with the challenge token from the request
                return $challenge;
            } else {
                // Responds with '403 Forbidden' if verify tokens do not match
                return 1;
            }
        }
        return 2;
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
        } else {
            $user_and_page = UserAndPage::wheretype(1)->wherestatus(1)->wherepage_id($page->id)
                ->whereuser_child(Auth::id())->firstorfail()->update(['type' => 4]);
            return redirect()->back()->with('success', 'Xoá page thành công!');
        }
        return abort(404);
    }
}
