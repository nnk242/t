<?php

namespace App\Http\Controllers\Facebook;

use App\Http\Controllers\Controller;
use App\Jobs\Facebook\FacebookSaveData;
use App\Jobs\Service\ServiceSharePage;
use App\Model\Page;
use App\Model\UserFbPage;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

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
        $url = 'http://127.0.0.1:3000/';
        try {
            $client = new Client(new Version2X($url, [
                'headers' => [
                    'Authorization: ' . env('KEY_CONNECTION')
                ]
            ]));

            $this->dispatch(new FacebookSaveData($request->all()));

            ###
            $entry = $request['entry'];
            if (isset($request['entry'][0]['id'])) {
                $fb_page_id = $request['entry'][0]['id'];
                $page = Page::wherefb_page_id($fb_page_id)->first();
                $page_id = $page->_id;
                if (isset($page)) {
                    try {
                        if (isset($entry[0]['messaging'])) {
                            $is_sender = false;
                            $sender_id = $entry[0]['messaging'][0]['sender']['id'];
                            if ($fb_page_id === $sender_id) {

                            } else {
                                $is_sender = true;
                                $m_user_fb_id = $page_id . '_' . $sender_id;
                                $user_fb_page = UserFbPage::wherem_user_fb_id($m_user_fb_id)->first();
                                if (!isset($user_fb_page)) {

                                }
                            }

                        }
                    } catch (Exception $exception) {
                        $client->initialize();
                        $client->emit('data', array($exception->getMessage()));
                        $client->close();
                    }

//                    $m_user_fb_id = $page_id . '_' . $get_user_page['id'];
                }
            }

            $client->initialize();
            $client->emit('data', array($request->all()));
            $client->close();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        return $request->all();
    }
}
