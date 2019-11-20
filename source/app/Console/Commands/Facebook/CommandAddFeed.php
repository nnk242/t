<?php

namespace App\Console\Commands\Facebook;

use App\Components\Console\Notify;
use App\Components\Facebook\Facebook;
use App\Components\UpdateOrCreateData\UpdateOrCreate;
use App\Model\FbConversation;
use App\Model\FbFeed;
use App\Model\Page;
use App\Model\FbUserPage;
use Illuminate\Console\Command;

class CommandAddFeed extends Command
{
    protected $signature = 'command:AddFeed {--fb_page_id=}';

    protected $description = 'Run every 5 mins';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $run_default = false;
        $fb_page_id = $this->option('fb_page_id');

        $this->info(Notify::notify('Start run add add feed' . $fb_page_id));
        $page = Page::where($fb_page_id)->firstorfail();
        $fb_page_id = $page->fb_page_id;
        $this->info(Notify::notify('Start with page_fb_id ' . $fb_page_id));

        $access_token = $page->access_token;
        $feeds = Facebook::get($access_token, 'me/feed?fields=created_time,message,id,is_eligible_for_promotion,picture,from,is_published');
        foreach ($feeds as $feed) {
            $data = [
                'fb_page_id' => $fb_page_id,
                'post_id' => $feed['id'],
                'message' => isset($feed['message']) ? $feed['message'] : null,
                'link' => isset($feed['picture']) ? $feed['picture'] : null,
                'from_id' => $feed['from']['id'],
                'created_time' => strtotime($feed['created_time'])
            ];
            UpdateOrCreate::fbFeed($data);
        }

        $this->info(Notify::notify('End with page_fb_id ' . $fb_page_id));
        $this->info(Notify::notify('Final'));
    }
}
