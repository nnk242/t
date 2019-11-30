<form class="container" method="POST">
    @csrf
    <input name="type_message" value="message_templates" hidden>
    <input id="bot_message_head_id_template" name="bot_message_head_id" hidden>
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Message Templates</h4>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">search</i>
                    <input type="text" class="autocomplete search-data-message-head"
                           data-type="bot_message_head_id_template">
                    <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                </div>
                <div class="input-field col s12">
                    <select name="template_type" class="template_type">
                        <option value="generic" disabled selected>Chọn kiểu tin nhắn...</option>
                        <option value="generic">Generic</option>
                        <option value="button">Button</option>
                        <option value="media">Media</option>
                    </select>
                    <label>Kiểu tin nhắn</label>
                </div>
                <div class="element-generic display-none">
                    <div class="input-field col s12">
                        <label>Nhập title</label>
                        <input type="text" class="validate" placeholder="Nhập title" name="title" maxlength="80"
                               data-length="80" autocomplete="off">
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập link ảnh</label>
                        <input type="url" class="validate" placeholder="Nhập link ảnh" name="image_url">
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập subtitle</label>
                        <input type="text" class="validate" placeholder="Nhập subtitle" name="subtitle" maxlength="80"
                               autocomplete="off">
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập group message template</label>
                        <input type="number" class="validate" placeholder="Nhập group message template"
                               name="group">
                    </div>
                    <div class="input-field col s12">
                        <h5>Nội dung mặc định khi ấn vào ảnh</h5>
                    </div>
                    <div class="input-field col s12">
                        <label>Redirect khi chạm vào action</label>
                        <input type="url" class="validate" placeholder="Redirect khi chạm vào action"
                               name="default_action_url">
                    </div>
                    <div class="input-field col s12">
                        <select name="messenger_webview_height_ratio">
                            <option value="tall">Màn hình nhỏ</option>
                            <option value="full" selected>Full màn hình</option>
                        </select>
                        <label>Kiểu màn hình trên messager</label>
                    </div>
                </div>
                <div class="element-button display-none">
                    <div class="input-field col s12">
                        <label>Redirect khi chạm vào action</label>
                        <textarea class="validate materialize-textarea" data-length="630"
                                  placeholder="Nội dung gửi người dùng" name="text"></textarea>
                    </div>
                </div>
                <div class="element-media display-none">
                    <div class="input-field col s12 row">
                        <h5>Lấy URL Facebook</h5>
                        <p>Để lấy URL Facebook của hình ảnh hoặc video, hãy làm như sau:</p>
                        <ol>
                            <li>Nhấp vào hình nhỏ của ảnh hoặc video để mở chế độ xem toàn kích thước.</li>
                            <li>Sao chép URL từ thanh địa chỉ trên trình duyệt của bạn.</li>
                        </ol>
                        <h5>URL Facebook sẽ ở định dạng cơ bản sau:</h5>
                        <table class="responsive-table">
                            <thead>
                            <tr style="width: 100px">
                                <th>Loại phương tiện</th>
                                <th>Nguồn phương tiện</th>
                                <th>Định dạng URL</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Video</td>
                                <td>Trang Facebook</td>
                                <td class="orange-text">https://business.facebook.com/&lt;PAGE_NAME&gt;/videos/&lt;NUMERIC_ID&gt;</td>
                            </tr>
                            <tr>
                                <td>Video</td>
                                <td>Tài khoản Facebook</td>
                                <td class="orange-text">https://www.facebook.com/&lt;USERNAME&gt;/videos/&lt;NUMERIC_ID&gt;/
                                </td>
                            </tr>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>Trang Facebook</td>
                                <td class="orange-text">https://business.facebook.com/&lt;PAGE_NAME&gt;/photos/&lt;NUMERIC_ID&gt;</td>
                            </tr>
                            <tr>
                                <td>Hình ảnh</td>
                                <td>Tài khoản Facebook</td>
                                <td class="orange-text">https://www.facebook.com/photo.php?fbid=&lt;NUMERIC_ID&gt;</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="input-field col s12">
                        <label>URL media</label>
                        <input type="url" class="validate" placeholder="URL media" name="url">
                    </div>
                    <div class="input-field col s12">
                        <select name="media_type">
                            <option value="image" selected>Hình ảnh</option>
                            <option value="video">Video</option>
                        </select>
                        <label>Kiểu media</label>
                    </div>
                </div>
                <div class="button">
                    <div class="row">
                        <div class="col s4 button_">
                            <div class="input-field">
                                <h4 class="center">Button 1</h4>
                            </div>
                            <div class="input-field col s12">
                                <select name="button_type[]" class="button_type">
                                    <option value="postback" disabled selected>Chọn loại button...</option>
                                    <option value="phone_number">Phone number</option>
                                    <option value="web_url">Web url</option>
                                    <option value="postback">Postback</option>
                                </select>
                                <label>Chọn loại button</label>
                            </div>
                            <div class="input-field col s12 button_title display-none">
                                <label>Nhập title của button</label>
                                <input type="text" class="validate" placeholder="Nhập title của button"
                                       name="button_title[]" data-length="20" maxlength="20">
                            </div>
                            <div class="input-field col s12 web_url display-none">
                                <label>Nhập url</label>
                                <input type="url" class="validate" placeholder="Nhập link"
                                       name="button_url[]">
                            </div>
                            <div class="input-field col s12 button_phone display-none">
                                <label>Số điện thoại</label>
                                <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                       name="payload[]">
                            </div>
                        </div>
                        <div class="col s4 button_">
                            <div class="input-field">
                                <h4 class="center">Button 2</h4>
                            </div>
                            <div class="input-field col s12">
                                <select name="button_type[]" class="button_type">
                                    <option value="postback" disabled selected>Chọn loại button...</option>
                                    <option value="phone_number">Phone number</option>
                                    <option value="web_url">Web url</option>
                                    <option value="postback">Postback</option>
                                </select>
                                <label>Chọn loại button</label>
                            </div>
                            <div class="input-field col s12 button_title display-none">
                                <label>Nhập title của button</label>
                                <input type="text" class="validate" placeholder="Nhập title của button"
                                       name="button_title[]" data-length="20" maxlength="20">
                            </div>
                            <div class="input-field col s12 web_url display-none">
                                <label>Nhập url</label>
                                <input type="url" class="validate" placeholder="Nhập link"
                                       name="button_url[]">
                            </div>
                            <div class="input-field col s12 button_phone display-none">
                                <label>Số điện thoại</label>
                                <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                       name="payload[]">
                            </div>
                        </div>
                        <div class="col s4 button_">
                            <div class="input-field">
                                <h4 class="center">Button 3</h4>
                            </div>
                            <div class="input-field col s12">
                                <select name="button_type[]" class="button_type">
                                    <option value="postback" disabled selected>Chọn loại button...</option>
                                    <option value="phone_number">Phone number</option>
                                    <option value="web_url">Web url</option>
                                    <option value="postback">Postback</option>
                                </select>
                                <label>Chọn loại button</label>
                            </div>
                            <div class="input-field col s12 button_title display-none">
                                <label>Nhập title của button</label>
                                <input type="text" class="validate" placeholder="Nhập title của button"
                                       name="button_title[]" data-length="20" maxlength="20">
                            </div>
                            <div class="input-field col s12 web_url display-none">
                                <label>Nhập url</label>
                                <input type="url" class="validate" placeholder="Nhập link"
                                       name="button_url[]">
                            </div>
                            <div class="input-field col s12 button_phone display-none">
                                <label>Số điện thoại</label>
                                <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                       name="payload[]">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="input-field col s12">
                    <select name="type_notify" class="type_notify">
                        <option value="normal" disabled selected>Chọn loại tin nhắn chạy</option>
                        <option value="normal">Normal</option>
                        <option value="timer">Timer</option>
                    </select>
                    <label>Loại tin nhắn</label>
                </div>
                <div class="run_ display-none">
                    @include('components.common.form-date')
                </div>
                <div class="center-align">
                    <button class="btn">Gửi</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="container">
    <div class=" card-panel">
        <div class="input-field">
            <h5>Tin nhắn gửi cho user</h5>
        </div>
        @if($message_templates->count())
            @component('components.table.index', ['headers' => $header_message_templates])
                @slot('body')
                    @foreach($message_templates as $key=>$message_template)
                        <tr>
                            <td>{{ $key +  1 }}</td>
                            <td>{{$message_template->fb_page_id}}</td>
                            <td>{{$message_template->page->name}}</td>
                            <td class="red-text center">{{$message_template->text}}</td>
                            <td class="center amber-text">{{$message_template->botPayloadElements->count()}}</td>
                            <td>{{$message_template->created_at}}</td>
                            <td>
                                <span>
                                    <a href="{{ route('setting.message.edit', ['message' => $message_template->_id]) }}"
                                       class="amber-text"><span class="material-icons">mode_edit</span></a>
                                </span>
                                <span title="Show"><a
                                        href="{{ route('setting.message.show', ['message' => $message_template->_id]) }}"><span
                                            class="material-icons">remove_red_eye</span></a></span>
                                <span title="Delete page">
                                    <a data-id="{{ $message_template->_id }}"
                                       data-text="{{ $message_template->type }}"
                                       class="delete-text-message modal-trigger"
                                       href="#text-messages">
                                        <span class="material-icons red-text">delete</span>
                                    </a>
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endslot
                @slot('more')
                    <div class="input-field center">
                        <a href="{{ route('setting.message.show', ['message' => 'call-bot-message']) }}">
                            <button class="btn">More...</button>
                        </a>
                    </div>
                @endslot
            @endcomponent
        @else
            <p class="text-center text-dark h3">Bạn chưa setup page hoặc chưa có nội dung nào...</p>
        @endif
    </div>


    @component('components.modal.index', ['modal_id' => 'call-bot-message-modal', 'modal_title' => 'Xoá tin nhắn người dùng gửi', 'modal_form_action' => '', 'is_delete' => true])
        @slot('modal_content')
            <div class="modal-body">
                <div id="modal-body-notify">
                    Bạn chắc chắn muốn xóa?
                </div>
            </div>
        @endslot
        @slot('modal_button')
            <button class="waves-effect waves-green btn">Gửi</button>
        @endslot
    @endcomponent
</div>
