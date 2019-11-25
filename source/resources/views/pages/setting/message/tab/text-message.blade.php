<form class="container" method="POST">
    @csrf
    <input name="type_message" value="text_messages" hidden>
    <input id="bot_message_head_id_text_messages" name="bot_message_head_id" hidden>
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Text Messages</h4>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">search</i>
                    <input type="text" class="autocomplete search-data-message-head"
                           data-type="search-data-message-head">
                    <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                </div>
                <div class="input-field col s12">
                    <label>Nhập tin nhắn trả lời người dùng</label>
                    <textarea class="validate materialize-textarea"
                              placeholder="Nhập tin nhắn gửi đến người dùng" name="text" data-length="630"></textarea>
                </div>
                <div class="input-field col s12">
                    <select name="type_notify" class="type_notify">
                        <option value="normal" disabled selected>Chọn loại tin nhắn chạy</option>
                        <option value="normal">Normal</option>
                        <option value="timer">Timer</option>
                    </select>
                    <label>Kiểu tin nhắn</label>
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
        @if($text_messages->count())
            @component('components.table.index', ['headers' => $header_text_messages])
                @slot('body')
                    @foreach($text_messages as $key=>$text_message)
                        <tr>
                            <td>{{ $key +  1 }}</td>
                            <td class="amber-text">{{$text_message->page->name . ' - ' . $text_message->fb_page_id}}</td>
                            <td class="cyan-text">{{$text_message->botMessageHead ? $text_message->botMessageHead->text : ''}}</td>
                            <td class="red-text">
                                <pre>{{$text_message->text}}</pre>
                            </td>
                            <td><span class="new badge"
                                      data-badge-caption="{{$text_message->type_notify}}"></span></td>
                            <td>{{$text_message->created_at}}</td>
                            <td>
                                <span>
                                    <a href="{{ route('setting.edit-message-head', ['id' => $text_message->_id]) }}"
                                       class="amber-text"><span class="material-icons">mode_edit</span></a>
                                </span>
                                <span title="Update page"><a
                                        href="{{ route('page.show', ['page' => $text_message->fb_page_id]) }}"><span
                                            class="material-icons">remove_red_eye</span></a></span>
                                <span title="Delete page">
                                    <a data-id="{{ $text_message->_id }}"
                                       data-text="{{ $text_message->text }}"
                                       class="delete-message modal-trigger"
                                       href="#text-messages">
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


    @component('components.modal.index', ['modal_id' => 'text-messages', 'modal_title' => 'Xoá tin nhắn người dùng gửi', 'modal_form_action' => '', 'is_delete' => true])
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
