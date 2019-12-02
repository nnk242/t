<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;

use App\Model\PersistentMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Components\Common\TextComponent;

class PersistentMenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function persistentMenu($fb_page_id, $level_menu = null, $data = null, $remove_id = null, $type = 0, $priorities = null)
    {
        if ($remove_id) {
            $menu_1 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '1'])->where('id', '<>', $remove_id)->orderby('priority', 'ASC')->get();
        } else {
            $menu_1 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '1'])->orderby('priority', 'ASC')->get();
        }
        if ($type) {
            foreach ($menu_1 as $key => $value) {
                if ($key >= 3) {
                    break;
                }
                if ($value->type == 'nested') {
                    if ($remove_id) {
                        $persistent_menu_2 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '2', 'persistent_id' => $value->_id])->where('id', '<>', $remove_id)->orderby('priority', 'ASC')->get();
                    } else {
                        $persistent_menu_2 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '2', 'persistent_id' => $value->_id])->orderby('priority', 'ASC')->get();
                    }
                    foreach ($persistent_menu_2 as $k => $val) {
                        if ($k >= 5) {
                            break;
                        }

                        if ($remove_id) {
                            $persistent_menu_3 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '3', 'persistent_id' => $val->_id])->where('id', '<>', $remove_id)->orderby('priority', 'ASC')->get();
                        } else {
                            $persistent_menu_3 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '3', 'persistent_id' => $val->_id])->orderby('priority', 'ASC')->get();
                        }

                        foreach ($persistent_menu_3 as $l => $v) {
                            if ($l >= 5) {
                                break;
                            }
                            PersistentMenu::find($v->_id)->update(
                                [
                                    'priority' => isset($priorities[$key][$k][$l]) ? $priorities[$key][$k][$l] : null
                                ]
                            );
                        }

                        PersistentMenu::find($val->_id)->update(
                            [
                                'priority' => isset($priorities[$key][$k]['name']) ? $priorities[$key][$k]['name'] : null
                            ]
                        );
                    }
                }

                PersistentMenu::find($value->_id)->update(
                    [
                        'priority' => isset($priorities[$key]['name']) ? $priorities[$key]['name'] : null
                    ]
                );
            }

            if ($remove_id) {
                $menu_1 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '1'])->where('id', '<>', $remove_id)->orderby('priority', 'ASC')->get();
            } else {
                $menu_1 = PersistentMenu::where(['fb_page_id' => $fb_page_id, 'level_menu' => '1'])->orderby('priority', 'ASC')->get();
            }
        }

        if ($level_menu) {
            if ((int)$level_menu == 1) {
                $menu_1 = (object)json_decode(json_encode(array_merge(json_decode($menu_1), [$data])), FALSE);
            }
        }

        $menu = array();

        foreach ($menu_1 as $key => $value) {
            switch ($value->type) {
                case 'postback':
                    $menu[$key] = ['title' => $value->title, 'type' => $value->type, 'payload' => $value->payload];
                    break;
                case 'web_url':
                    $menu[$key] = ['title' => $value->title, 'type' => $value->type, 'url' => $value->url];
                    break;
                case 'nested':
                    if (isset($value->_id)) {
                        $data_ = ['page_id' => $fb_page_id, 'level_menu' => '2', 'persistent_id' => $value->_id];
                        if ($remove_id) {
                            $menu_2 = PersistentMenu::where($data_)->where('id', '<>', $remove_id)->get();
                            $count_menu_2 = PersistentMenu::where($data_)->where('id', '<>', $remove_id)->count();
                        } else {
                            $menu_2 = PersistentMenu::where($data_)->get();
                            $count_menu_2 = PersistentMenu::where($data_)->count();
                        }
                        if ($count_menu_2 <= 5 and $count_menu_2 != 0) {
                            $check = false;
                            if ($data) {
                                if ($count_menu_2 <= 4) {
                                    $check = true;
                                }
                            } else {
                                $check = true;
                            }
                            if ($check) {
                                $menu[$key] = ['title' => $value->title, 'type' => $value->type, 'call_to_actions' => array()];
                                if ($level_menu) {
                                    if ((int)$level_menu == 2) {
                                        (object)$menu_2 = json_decode(json_encode(array_merge(json_decode($menu_2), [$data])), FALSE);
                                    }
                                }
                                foreach ($menu_2 as $k => $item) {
                                    if ((int)$value->_id == (int)$item->persistent_id) {
                                        switch ($item->type) {
                                            case 'postback':
                                                $menu[$key]['call_to_actions'][$k] = ['title' => $item->title, 'type' => $item->type, 'payload' => $item->payload];
                                                break;
                                            case 'web_url':
                                                $menu[$key]['call_to_actions'][$k] = ['title' => $item->title, 'type' => $item->type, 'url' => $item->url];
                                                break;
                                            case 'nested':
                                                if (isset($item->_id)) {
                                                    if ($remove_id) {
                                                        $menu_3 = PersistentMenu::where(['page_id' => $fb_page_id, 'level_menu' => '3', 'persistent_id' => $item->_id])->where('id', '<>', $remove_id)->get();
                                                        $count_menu_3 = PersistentMenu::where(['page_id' => $fb_page_id, 'level_menu' => '3', 'persistent_id' => $item->_id])->where('id', '<>', $remove_id)->count();
                                                    } else {
                                                        $menu_3 = PersistentMenu::where(['page_id' => $fb_page_id, 'level_menu' => '3', 'persistent_id' => $item->_id])->get();
                                                        $count_menu_3 = PersistentMenu::where(['page_id' => $fb_page_id, 'level_menu' => '3', 'persistent_id' => $item->_id])->count();
                                                    }
                                                    if ($count_menu_3 <= 5 and $count_menu_3 != 0) {
                                                        $check = false;
                                                        if ($data) {
                                                            if ($count_menu_2 <= 4) {
                                                                $check = true;
                                                            }
                                                        } else {
                                                            $check = true;
                                                        }
                                                        if ($check) {
                                                            $menu[$key]['call_to_actions'][$k] = ['title' => $item->title, 'type' => $item->type, 'call_to_actions' => array()];
                                                            if ($level_menu) {
                                                                if ((int)$level_menu == 3) {
                                                                    (object)$menu_3 = json_decode(json_encode(array_merge(json_decode($menu_3), [$data])), FALSE);
                                                                }
                                                            }
                                                            foreach ($menu_3 as $val) {
                                                                if ($item->_id == $val->persistent_id) {
                                                                    switch ($val->type) {
                                                                        case 'postback':
                                                                            $menu[$key]['call_to_actions'][$k]['call_to_actions'][] = ['title' => $val->title, 'type' => $val->type, 'payload' => $val->payload];
                                                                            break;
                                                                        case 'web_url':
                                                                            $menu[$key]['call_to_actions'][$k]['call_to_actions'][] = ['title' => $val->title, 'type' => $val->type, 'url' => $val->payload];
                                                                            break;
                                                                        default:
                                                                    }
                                                                }
                                                            }
                                                        } else {
                                                            $menu[$key]['call_to_actions'][$k] = ['title' => $item->title, 'type' => 'postback', 'payload' => $item->payload];
                                                        }
                                                    } else {
                                                        $menu[$key]['call_to_actions'][$k] = ['title' => $item->title, 'type' => 'postback', 'payload' => $item->payload];
                                                    }
                                                } else {
                                                    $menu[$key]['call_to_actions'][$k] = ['title' => $item->title, 'type' => 'postback', 'payload' => $item->payload];
                                                }
                                                break;
                                            default:
                                                break;
                                        }
                                    }
                                }
                            } else {
                                $menu[$key] = ['title' => $value->title, 'type' => 'postback', 'payload' => $value->payload];
                            }
                        } else {
                            $menu[$key] = ['title' => $value->title, 'type' => 'postback', 'payload' => $value->payload];
                        }
                    } else {
                        $menu[$key] = ['title' => $value->title, 'type' => 'postback', 'payload' => $value->payload];
                    }
                    break;
                default:
                    break;
            }
        }
        return array(
            "persistent_menu" => [
                [
                    "locale" => "default",
                    "call_to_actions" => $menu
                ]
            ]
        );
    }

    public function index(Request $request)
    {
        $persistent_menus = PersistentMenu::where(['level_menu' => 1, 'fb_page_id' => Auth::user()->page_selected])->orderby('priority', 'ASC')->get();
        return view('pages.setting.persistent-menu.index', compact('persistent_menus'));
    }

    public function store(Request $request)
    {
        try {
            $validate = Validator::make(
                $request->all(),
                [
                    'title' => 'array|max:20',
                    'type' => 'array|max:20',
                    'url' => 'array|max:20',
                    'priority' => 'array|max:20',
                    'level_menu' => 'required'
                ], [
                'required' => ':attribute phải có dữ liệu'
            ]);

            if ($validate->fails()) {
                return redirect()->back()->with('error', $validate->errors()->first());
            }

            $data = [];
            $type = $request->type;
            $url = $request->url;
            $level_menu = (int)$request->level_menu;
            $priority = (int)$request->priority;
            $persistent_id = $request->persistent_id;
            $fb_page_id = Auth::user()->page_selected;
            if ($fb_page_id && in_array($level_menu, [1, 2, 3])) {
                $persistent_menus = PersistentMenu::wherefb_page_id($fb_page_id)->wherelevel_menu($level_menu)->get();
                foreach ($persistent_menus as $persistent_menu) {
                    $menu = PersistentMenu::find($persistent_menu->_id);
                    if (isset($menu)) {
                        $menu->delete();
                    }
                }
                foreach ($request->title as $key => $title) {
                    if ($title) {
                        $data['title'] = $title;
                        $data['type'] = isset($type[$key]) ? $type[$key] : null;
                        $data['url'] = isset($url[$key]) ? $url[$key] : null;
                        $data['payload'] = TextComponent::payload($title);
                        $data['level_menu'] = $level_menu;
                        $data['persistent_id'] = $persistent_id;
                        $data['priority'] = isset($priority[$key]) ? $priority[$key] : null;
                        $data['fb_page_id'] = $fb_page_id;
                    } else {
                        continue;
                    }
                    if ($level_menu === 1 && $key <= 2) {
                        PersistentMenu::create($data);
                    } else {
                        if ($key > 4) {
                            break;
                        }
                        PersistentMenu::create($data);
                    }
                }
            } else {
                return redirect()->back()->with('error', 'Cần phải sử dụng 1 page trước!!!');
            }

            return redirect()->back()->with('success', 'Tạo thành công!!!');
        } catch (\Exception $exception) {

        }
        return redirect()->back()->with('error', 'Tạo thất bại!!!');
    }

    public function show($id)
    {
        $parent = PersistentMenu::findorfail($id);
        if ($parent->type === 'submenu') {
            $persistent_menus = PersistentMenu::wherepersistent_id($parent->_id)->get();
        }
        return view('pages.setting.persistent-menu.child', compact('persistent_menus', 'parent'));
    }

    public function destroy($id)
    {
        $persistent_menu = PersistentMenu::findorfail($id);
        if (((int)$persistent_menu->level_menu) !== 1) {
            $menu = PersistentMenu::wherepersistent_id($persistent_menu->_id)->get();
            foreach ($menu as $value) {
                if ($value->type === 'submenu') {
                    $child_2 = PersistentMenu::wherepersistent_id($value->_id)->get();
                    foreach ($child_2 as $child) {
                        $m = PersistentMenu::find($child->_id);
                        if (isset($m)) {
                            $m->delete();
                        }
                    }
                    $value->delete();
                }
            }
        }
        $persistent_menu->delete();
        return redirect()->back()->with('success', 'Xóa thành công!!!');
    }

    public function update($id)
    {
        if($id === 'send-persistent-menu') {
            return redirect()->back()->with('success', 'Gửi thành công!!!');
        }
        return abort(404);
    }
}
