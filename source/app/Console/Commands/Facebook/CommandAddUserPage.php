<?php

namespace App\Console\Commands\Facebook;

use App\Components\Facebook;
use App\Model\FbConversation;
use App\Model\UserFbPage;
use App\Model\UserPage;
use Illuminate\Console\Command;

class CommandAddUserPage extends Command
{
    protected $signature = 'command:AddUserPage';

    protected $description = 'Run every 5 mins';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('[' . date('Y-m-d H:i:s') . ']' . 'Start run add user page' . PHP_EOL);
//        $this->info('Start run add user page' . PHP_EOL);
        $user_pages = UserPage::whererun_conversations(1)->get();
        foreach ($user_pages as $user_page) {
            $page_id = $user_page->page_id;
            $this->info('[' . date('Y-m-d H:i:s') . ']' . ' Start with page_fb_id ' . $page_id . PHP_EOL);
//            dd($user_page->page->fb_page_id);
            $access_token = $user_page->access_token;
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
            $this->info('[' . date('Y-m-d H:i:s') . ']' . 'End with page_fb_id ' . $page_id . PHP_EOL);
        }
        $this->info('Final');
    }
}
