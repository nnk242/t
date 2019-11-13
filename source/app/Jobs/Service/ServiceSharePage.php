<?php

namespace App\Jobs\Service;

use App\Model\Page;
use App\Model\UserAndPage;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ServiceSharePage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {
        $arr_page_id = $this->data['arr_page_id'];
        $arr_email = $this->data['arr_email'];
        $user_id = $this->data['user_id'];
        foreach ($arr_page_id as $item) {
            $count_page = Page::whereid($item)->whereuser_id($user_id)->count();
            if ($count_page) {
                foreach ($arr_email as $value) {
                    if ($value !== User::findorfail($user_id)->email) {
                        $user = User::whereemail($value)->first();
                        if (isset($user)) {
                            UserAndPage::updateorcreate(['user_parent' => $user_id, 'page_id' => $item, 'user_child' => $user->id, 'type' => 0], [
                                'user_parent' => $user_id, 'page_id' => $item, 'user_child' => $user->id, 'type' => 0, 'status' => 1
                            ]);
                        }
                    }
                }
            }
        }
    }
}
