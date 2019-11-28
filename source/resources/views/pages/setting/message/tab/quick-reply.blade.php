<form class="container" method="POST">
    @csrf
    <input name="type_message" value="quick_replies" hidden>
    <input id="bot_message_head_id_quick_reply" name="bot_message_head_id" hidden>
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Quick Replies</h4>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">search</i>
                    <input type="text" class="autocomplete search-data-message-head"
                           data-type="bot_message_head_id_quick_reply">
                    <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                </div>
                <div class="input-field col s12 content-text">
                    <label>Nhập tin nhắn trả lời</label>
                    <input type="text" class="validate " placeholder="Nhập tin nhắn trả lời" name="text">
                </div>
                <div class="input-field col s12">
                    <select name="number" class="number">
                        <option value="0" disabled selected>0</option>
                        @for($i = 1; $i <=9; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                    <label>Số lượng trả lời nhanh</label>
                </div>
                <div class="row col 12 content-quick-replies">
                    @for($i = 0; $i <9; $i ++)
                        <div class="row col s12 l3 m4 parent-content display-none">
                            <h5>Quick reply {{ $i+1 }}</h5>
                            <div class="input-field col s12">
                                <select name="content_type[]" class="content_type">
                                    <option value="text" disabled selected>Chọn kiểu tin nhắn...</option>
                                    <option value="text">Văn bản</option>
                                    <option value="user_phone_number">Lấy số điện thoại</option>
                                    <option value="user_email">Lấy email</option>
                                </select>
                                <label>Kiểu tin nhắn</label>
                            </div>
                            <div class="input-field col s12 content-text">
                                <label>Nhập title</label>
                                <input type="text" class="validate " placeholder="Nhập title"
                                       name="title[]" data-length="20" maxlength="20">
                            </div>
                            <div class="input-field col s12">
                                <label>Nhập link</label>
                                <input type="url" class="validate" placeholder="Nhập link ảnh"
                                       name="image_url[]">
                            </div>
                        </div>
                    @endfor
                </div>

                <div class="input-field row col s12">
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
                <div class="center-align input-field col s12">
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
        @if($quick_replies->count())
            @component('components.table.index', ['headers' => $header_quick_replies])
                @slot('body')
                    @foreach($quick_replies as $key=>$quick_reply)
                        <tr>
                            <td>{{ $key +  1 }}</td>
                            <td>{{$quick_reply->fb_page_id}}</td>
                            <td>{{$quick_reply->page->name}}</td>
                            <td class="red-text center">{{$quick_reply->text}}</td>
                            <td>{{$quick_reply->updated_at}}</td>
                            <td>{{$quick_reply->created_at}}</td>
                            <td>
                                <span title="Update page"><a
                                        href="{{ route('page.show', ['page' => $quick_reply->fb_page_id]) }}"><span
                                            class="material-icons">mode_edit</span></a></span>
                                <span title="Delete page">
                                    <a data-id="{{ $quick_reply->_id }}"
                                       data-text="{{ $quick_reply->text }}"
                                       class="delete-call-bot-message modal-trigger"
                                       href="#call-bot-message-modal">
                                        <span class="material-icons red-text">delete</span>
                                    </a></span>
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
