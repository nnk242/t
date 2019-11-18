<?php

namespace App\Http\Controllers;

use App\Components\Facebook;
use App\Model\FbConversation;
use App\Model\Page;
use App\Model\UserFbPage;
use App\Model\UserPage;
use App\Model\UserRolePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mockery\Exception;

class TestController extends Controller
{
    #defined text
    #default
    ##  !'value, first, last, min, max
    ##  !'_____, *****, ****, ***, ***
    public function text()
    {
        $user_pages = UserPage::whererun_conversations(1)->get();
        foreach ($user_pages as $user_page) {
            $access_token = $user_page->access_token;
            $page_id = $user_page->page_id;
            $conversations = Facebook::get($access_token, 'me/conversations?fields=id,updated_time,senders,snippet');
            foreach ($conversations as $conversation) {
                $conversation_id = $conversation['id'];
                $data_conversation = [
                    'conversation_id' => $conversation_id,
                    'snippet' => $conversation['snippet'],
                    'updated_time' => $conversation['updated_time']
                ];
                foreach ($conversation['senders']['data'] as $sender) {
                    if ($user_page->page->fb_page_id !== $sender['id']) {
                        $get_user_page = Facebook::get($access_token, $sender['id'] . '?fields=gender,first_name,last_name,name,id,locale,timezone');
                        if (isset($get_user_page['id'])) {
                            $m_user_fb_id = $page_id . '_' . $get_user_page['id'];
                            $data_user_fb_page = [
                                'm_user_fb_id' => $m_user_fb_id,
                                'name' => $sender['name'],
                                'user_fb_id' => $get_user_page['id'],
                                'gender' => isset($get_user_page['gender']) ? $get_user_page['gender'] : '',
                                'first_name' => isset($get_user_page['first_name']) ? $get_user_page['first_name'] : '',
                                'last_name' => isset($get_user_page['last_name']) ? $get_user_page['last_name'] : '',
                                'locale' => isset($get_user_page['locale']) ? $get_user_page['locale'] : '',
                                'timezone' => isset($get_user_page['timezone']) ? $get_user_page['timezone'] : '',
                                'page_id' => $page_id
                            ];
                            $user_fb_page = UserFbPage::updateorcreate(['m_user_fb_id' => $m_user_fb_id], $data_user_fb_page);
                            $data_conversation = array_merge($data_conversation, ['user_fb_page_id' => $user_fb_page->_id]);

                            FbConversation::updateorcreate(['conversation_id' => $conversation_id], $data_conversation);
                        }
                        break;
                    }
                }
            }
            UserPage::find($user_page->_id)->update(['run_conversations' => 0]);
            dd(UserPage::find($user_page->_id));
        }
        dd(UserFbPage::first());
//        foreach ()
        dd(Page::all());

        ###
        $text = "Suy ra một điều rằng Trâm. Anh thích 64 :)";
        $text_def = "!'S";

        $strlen_def = strlen($text_def);

        $check_text = false;

        switch (true) {
            case $strlen_def === 2:
                if ($text_def === "!'") {
                    $check_text = true;
                }
                break;
            case $strlen_def > 2:
                if ($text_def[0] === '!' and $text_def[1] === "'") {
                    $arr_text_def = explode("!'", $text_def);

                    try {
                        unset($arr_text_def[0]);
                    } catch (Exception $exception) {
                    }

                    $text_def = implode($arr_text_def, "!'");
                    //
                    $arr_text_def = explode(",", $text_def);
                    $count_arr_text_def = count($arr_text_def);
                    if ($count_arr_text_def <= 1) {
                        if (gettype(strpos($text, $text_def)) === 'integer') {
                            $check_text = true;
                        }
                    } else {

                    }
//                    dd($text_def);
                    break;
                }
            default:
                if ($text === $text_def) {
                    $check_text = true;
                }
                break;
        }
        return $check_text;
    }

    public function index()
    {
        return view('pages.test');
    }
}
