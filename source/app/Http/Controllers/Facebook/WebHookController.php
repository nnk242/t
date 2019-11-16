<?php

namespace App\Http\Controllers\Facebook;

use App\Components\Facebook;
use App\Http\Controllers\Controller;
use App\Jobs\Facebook\FacebookSaveData;
use App\Jobs\Service\ServiceSharePage;
use App\Model\Page;
use App\Model\UserFbPage;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class WebHookController extends Controller
{
    private function userFbPage($m_page_user_id, $sender_id, $fb_page_id, $k = 0)
    {
        $user_fb_page = UserFbPage::wherem_page_user_id($m_page_user_id)->first();
        if (isset($user_fb_page)) {
            return $user_fb_page;
        } else {
            Artisan::call('command:AddUserPage --page_user_id=' . $sender_id . ' --fb_page_id=' . $fb_page_id);
            $k++;
            if ($k === 10) {
                return $user_fb_page;
            }
            return $this->userFbPage($m_page_user_id, $sender_id, $fb_page_id, $k);
        }
    }

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
//                $fb_page_id = $page->fb_page_id;
                if (isset($page)) {
                    try {
                        if (isset($entry[0]['messaging'])) {
                            $sender_id = $entry[0]['messaging'][0]['sender']['id'];
//                            $sender_id = $entry[0]['messaging'][0]['sender']['id'];

//                            if ($sender_id === $fb_page_id) {
//
//                            } else {
//                                $person = $sender_id;
//                            }

                            $m_page_user_id = $fb_page_id . '_' . $sender_id;

                            $user_fb_page = $this->userFbPage($m_page_user_id, $sender_id, $fb_page_id);
                            ##
                            ## run process
                            $message_attachments = isset($entry[0]['messaging'][0]['message']['attachments']) ? $entry[0]['messaging'][0]['message']['attachments'] : null;
                            if (gettype($message_attachments) === 'array') {
                                $attachments = [];
                                foreach ($message_attachments as $key => $message_attachment) {
                                    $attachments[$key]['type'] = isset($message_attachment['type']) ? $message_attachment['type'] : '__none';
                                    if ($attachments[$key]['type'] === "template") {
                                        $attachments[$key]['data'] = $message_attachment;
                                    } else {
                                        $attachments[$key]['url'] = isset($message_attachment['payload']['url']) ? $message_attachment['payload']['url'] : '__none';
                                    }
                                }
                            }
//                                array(
//
//                                    'attachments' => isset() ? $entry[0]['messaging'][0]['message']['attachments'] : null
//                                );
                            $client->initialize();
                            $client->emit('data', array($request->all(), '$user_fb_page' => $user_fb_page,
                                'data' => [
                                    'attachments' => isset($attachments) ? $attachments : null,
                                    'mid' => isset($entry[0]['messaging'][0]['message']['mid']) ? $entry[0]['messaging'][0]['message']['mid'] : null,
                                    'text' => isset($entry[0]['messaging'][0]['message']['text']) ? $entry[0]['messaging'][0]['message']['text'] : null,
                                    'timestamp' => isset($entry[0]['messaging'][0]['timestamp']) ? $entry[0]['messaging'][0]['timestamp'] : null,
                                ]));
                            $client->close();
//                            }

                        }
                    } catch (Exception $exception) {
                        $client->initialize();
                        $client->emit('data', array($exception->getMessage()));
                        $client->close();
                    }

//                    $m_user_fb_id = $page_id . '_' . $get_user_page['id'];
                }
            }

//            $client->initialize();
//            $client->emit('data', array($request->all()));
//            $client->close();
        } catch (\Exception $exception) {
            return $exception->getMessage();
        }
        return $request->all();
    }
}
