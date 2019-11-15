@extends('layouts.app')

@section('content')
    <div class="section no-pad-bot">
        <div class="row">
            <div class="col l3 m4 s12">
                <ul class="collapsible">
                    <li class="collection-item">
                        <a class="collapsible-header" href="{{ route('setting.persistent-menu.index') }}"><i
                                class="material-icons">art_track</i>Persistent menu</a>
                    </li>
                    <li class="collection-item">
                        <a class="collapsible-header"><i class="material-icons">place</i>Second</a>
                    </li>
                    <li class="collection-item">
                        <a class="collapsible-header"><i class="material-icons">whatshot</i>Third</a>
                    </li>
                </ul>
            </div>
            <div class="col l9 m8 s12">
                <div class="scrollspy">
                    <ul class="tabs">
                        <li class="tab col s4"><a href="#menu1">Menu 1</a></li>
                        <li class="tab col s4"><a href="#menu2">Menu 2</a></li>
                        <li class="tab col s4"><a href="#menu3">Menu 3</a></li>
                    </ul>
                    <div id="menu1">
                        <h4>Menu 1</h4>
                        <ul class="collapsible">
                            <li>
                                <div class="input-field col s12">
                                    <input placeholder="Nhập menu level 1">
                                </div>
                                <div class="input-field col s12">
                                    <input placeholder="Vị trí hiển thị">
                                </div>
                                <div class="input-field col s12">
                                    <select>
                                        <option value="" disabled selected>Chọn Loại</option>
                                        <option value="block">Block</option>
                                        <option value="url">URL</option>
                                        <option value="submenu">Submenu</option>
                                    </select>
                                </div>
                                <div class="input-field col s12">
                                    <input placeholder="Vị trí hiển thị">
                                </div>
                                {{--                            </div>--}}
                                <div class="collapsible-header center-align"><i class="material-icons">arrow_downward</i>
                                </div>
                                <div class="collapsible-body">
                                    <div class="form-g">
                                        <input placeholder="Nhập menu level 1">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                            </li>
                            <li>
                                <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                            </li>
                        </ul>
                    </div>

                    <div id="menu2">
                        <h4>Menu 2</h4>
                        <ul class="collapsible">
                            <li>
                                <div class="input-field col s12">
                                    <input placeholder="Nhập menu level 1">
                                </div>
                                <div class="input-field col s12">
                                    <input placeholder="Vị trí hiển thị">
                                </div>
                                <div class="input-field col s12">
                                    <select>
                                        <option value="" disabled selected>Chọn Loại</option>
                                        <option value="block">Block</option>
                                        <option value="url">URL</option>
                                        <option value="submenu">Submenu</option>
                                    </select>
                                </div>
                                {{--                            </div>--}}
                                <div class="collapsible-header center-align"><i class="material-icons">arrow_downward</i>
                                </div>
                                <div class="collapsible-body">
                                    <div class="form-g">
                                        <input placeholder="Nhập menu level 1">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                            </li>
                            <li>
                                <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                            </li>
                        </ul>
                    </div>
                    <div id="menu3">
                        <h4>Menu 3</h4>
                        <ul class="collapsible">
                            <li>
                                <div class="input-field col s12">
                                    <input placeholder="Nhập menu level 1">
                                </div>
                                <div class="input-field col s12">
                                    <input placeholder="Vị trí hiển thị">
                                </div>
                                <div class="input-field col s12">
                                    <select>
                                        <option value="" disabled selected>Chọn Loại</option>
                                        <option value="block">Block</option>
                                        <option value="url">URL</option>
                                        <option value="submenu">Submenu</option>
                                    </select>
                                </div>
                                {{--                            </div>--}}
                                <div class="collapsible-header center-align"><i class="material-icons">arrow_downward</i>
                                </div>
                                <div class="collapsible-body">
                                    <div class="form-g">
                                        <input placeholder="Nhập menu level 1">
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
                                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                            </li>
                            <li>
                                <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
                                <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.collapsible').collapsible()
            $('.tabs').tabs()
        })
    </script>
@endsection
