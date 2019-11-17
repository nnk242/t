<?php

namespace App\Http\Controllers\Facebook;

use App\Components\Facebook\ProcessDataMessaging;
use App\Http\Controllers\Controller;
use App\Jobs\Facebook\FacebookSaveData;
use App\Model\Page;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Illuminate\Http\Request;

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
        $client = new Client(new Version2X($url, [
            'headers' => [
                'Authorization: ' . env('KEY_CONNECTION')
            ]
        ]));
        try {
            $this->dispatch(new FacebookSaveData($request->all()));

            ###
            $entry = $request['entry'];
            if (isset($request['entry'][0]['id'])) {
                $fb_page_id = $request['entry'][0]['id'];
                $page = Page::wherefb_page_id($fb_page_id)->first();
//                $fb_page_id = $page->fb_page_id;
                if (isset($page)) {
                    if (isset($entry[0]['messaging'])) {
                        $sender_id = isset($entry[0]['messaging'][0]['sender']['id']) ? $entry[0]['messaging'][0]['sender']['id'] : null;
                        $recipient_id = isset($entry[0]['messaging'][0]['recipient']['id']) ? $entry[0]['messaging'][0]['recipient']['id'] : null;

                        #### Get user fb page
                        if ($sender_id === $fb_page_id) {
                            $person_id = $recipient_id;
                        } else {
                            $person_id = $sender_id;
                        }

                        $user_fb_page = ProcessDataMessaging::userFbPage($person_id, $fb_page_id);
//                          $this->userFbPage($person_id, $fb_page_id);
                        ## run process

                        $client->initialize();
                        $client->emit('data', array($request->all(), '$user_fb_page' => $user_fb_page,
                            'data' => ProcessDataMessaging::handle($entry, $person_id, $sender_id, $recipient_id)));
                        $client->close();
                    } else {
                        $client->initialize();
                        $client->emit('data', array($request->all(), 'Can not message'));
                        $client->close();
                    }
                } else {
                    $client->initialize();
                    $client->emit('data', array($request->all(), 'not found page'));
                    $client->close();
                }
            }

//            $client->initialize();
//            $client->emit('data', array($request->all()));
//            $client->close();
        } catch (\Exception $exception) {
            $client->initialize();
            $client->emit('data', array($request->all(), 'error' => [$exception->getMessage(), $exception]));
            $client->close();
        }
        return $request->all();
    }
}
