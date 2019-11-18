<?php

namespace App\Jobs\Service;

use App\Model\UserRolePage;
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
        $arr_user_page_id = $this->data['arr_user_page_id'];
        $arr_email = $this->data['arr_email'];
        $user_id = $this->data['user_id'];
        $user_parent = User::findorfail($user_id);
        if (isset($user_parent)) {
            foreach ($arr_user_page_id as $key => $item) {
                $user_page_parent = UserRolePage::wherefb_page_parent($item . '_' . $user_id)->first();
                if (isset($user_page_parent)) {
                    foreach ($arr_email as $value) {
                        if ($value !== User::findorfail($user_id)->email) {
                            $user = User::whereemail($value)->first();
                            if (isset($user)) {
                                if ($user->_id != $user_id) {
                                    $user_role_page = UserRolePage::whereuser_parent($user_id)->whereuser_child($user->id)->wherefb_page_id($item)->first();
                                    if (isset($user_role_page)) {
                                        if ($user_role_page->status !== 1 && $user_role_page->type !== 1) {
                                            UserRolePage::updateorcreate(['_id' => $user_role_page->_id], [
                                                'user_parent' => $user_id, 'fb_page_id' => $item, 'user_child' => $user->_id, 'type' => 0, 'status' => 1
                                            ]);
                                        }
                                    } else {
                                        UserRolePage::create([
                                            'user_parent' => $user_id, 'fb_page_id' => $item, 'user_child' => $user->id, 'type' => 0, 'status' => 1
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}
