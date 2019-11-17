<?php

namespace App\Console\Commands\Facebook;

use App\Components\Console\Notify;
use App\Components\Facebook\Facebook;
use App\Model\FbConversation;
use App\Model\Page;
use App\Model\FbUserPage;
use App\Model\UserPage;
use Illuminate\Console\Command;

class CommandAddUserPage extends Command
{
    protected $signature = 'command:AddUserPage {--page_user_id=} {--fb_page_id=} {--is_run=}';

    protected $description = 'Run every 5 mins';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $run_default = false;
        $fb_page_id = $this->option('fb_page_id');
        $page_user_id = $this->option('page_user_id');
        $is_run = $this->option('is_run');
        if ($fb_page_id) {
            $where = [
                'fb_page_id' => $fb_page_id,
            ];

            if ($is_run) {
                $where = array_merge($where, [
                    'run_conversations' => $$this->option('is_run') === 'yes' ? 1 : 0
                ]);
            }
        } else {
            $run_default = true;
            $run_conversations = 1;
            if ($this->option('is_run')) {
                $run_conversations = $this->option('is_run') === 'yes' ? 1 : 0;
            }

            $where = ['run_conversations' => $run_conversations];
        }

        $this->info(Notify::notify('Start run add user page' . $fb_page_id));
        $pages = Page::where($where)->get();
        foreach ($pages as $page) {
            $fb_page_id = $page->fb_page_id;
            $this->info(Notify::notify('Start with page_fb_id ' . $fb_page_id));

            $access_token = $page->access_token;
            $conversations = Facebook::get($access_token, 'me/conversations?fields=id,updated_time,senders,snippet');
            foreach ($conversations as $conversation) {
                $conversation_id = $conversation['id'];
                $data_conversation = [
                    'conversation_id' => $conversation_id,
                    'snippet' => $conversation['snippet']
                ];
                foreach ($conversation['senders']['data'] as $sender) {
                    if ($page->fb_page_id !== $sender['id']) {
                        $get_user_page = Facebook::get($access_token, $sender['id'] . '?fields=gender,first_name,last_name,name,id,locale,timezone');
                        if (isset($get_user_page['id'])) {
                            $is_break = false;
                            if ($page_user_id) {
                                if ($get_user_page['id'] === $page_user_id) {
                                    $is_break = true;
                                } else {
                                    continue;
                                }
                            }
                            $this->info('[' . date('Y-m-d H:i:s') . ']' . ' Page_fb_id ' . $fb_page_id . ' User_id ' . $sender['id'] . PHP_EOL);
                            $m_page_user_id = $fb_page_id . '_' . $get_user_page['id'];
                            $data_user_fb_page = [
                                'm_page_user_id' => $m_page_user_id,
                                'fb_page_id' => $fb_page_id,
                                'user_fb_id' => $sender['id'],
                                'name' => $sender['name'],
                                'gender' => isset($get_user_page['gender']) ? $get_user_page['gender'] : '',
                                'first_name' => isset($get_user_page['first_name']) ? $get_user_page['first_name'] : '',
                                'last_name' => isset($get_user_page['last_name']) ? $get_user_page['last_name'] : '',
                                'locale' => isset($get_user_page['locale']) ? $get_user_page['locale'] : '',
                                'timezone' => isset($get_user_page['timezone']) ? $get_user_page['timezone'] : ''
                            ];
                            $user_fb_page = FbUserPage::updateorcreate(['m_page_user_id' => $m_page_user_id], $data_user_fb_page);
                            $data_conversation = array_merge($data_conversation, ['user_fb_page_id' => $user_fb_page->_id]);

                            FbConversation::updateorcreate(['conversation_id' => $conversation_id], $data_conversation);
                            if ($is_break) {
                                break;
                            }
                        }
                    }
                }
            }
            if ($run_default) {
                Page::find($page->_id)->update(['run_conversations' => 0]);
            }
            $this->info(Notify::notify('End with page_fb_id ' . $fb_page_id));
        }
        $this->info(Notify::notify('Final'));
    }
}
