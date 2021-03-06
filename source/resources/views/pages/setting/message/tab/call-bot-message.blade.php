<form class="container" action="{{ route('setting.store-message-head') }}" method="POST">
    @csrf
    <input id="input-error-begin-time-active" name="text_error_begin_time_active_id" hidden>
    <input id="input-error-end-time-active" name="text_error_end_time_active_id" hidden>
    <input id="input-error-time-open" name="text_error_time_open_id" hidden>
    <input id="input-error-gift" name="text_error_gift_id" hidden>
    <input id="input-success-id" name="text_success_id" hidden>
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field row col s12">
                    <h4>Ví dụ:</h4>
                    <p>User gửi tin nhắn đến với cú pháp cố định: <span class="green-text">ABC</span></p>
                    <p>User gửi tin nhắn đến với cú pháp linh hoạt hơn: <span
                            class="green-text">!'{value}</span></p>
                    <p>User gửi tin nhắn số điện thoại lấy từ "Quick replies": <span class="green-text">$phone</span>
                    </p>
                    <p>User gửi tin nhắn email lấy từ "Quick replies": <span class="green-text">$email</span></p>
                    <p>Lấy tên người dùng, giftcode: '<span class="green-text">:name:</span>' '<span class="green-text">:gift:</span>'
                    </p>
                </div>
                <div class="input-field row col s12">
                    <textarea placeholder="Nhập tin nhắn nhận từ người dùng" type="text"
                              class="validate materialize-textarea" data-length="20" name="text"></textarea>
                </div>
                <div class="input-field row col s12">
                    <select class="type" id="type-head" name="type">
                        <option value="normal" disabled selected>Chọn loại tin nhắn chạy</option>
                        <option value="normal">Normal</option>
                        <option value="event">Event</option>
                    </select>
                    <label>Kiểu tin nhắn</label>
                </div>

                <div class="run-event display-none" id="run-event">
                    <div class="col s12 row input-field">
                        <select name="type_event">
                            <option value="normal" disabled selected>Chọn loại event</option>
                            <option value="normal">Normal</option>
                            <option value="phone">Payload số điện thoại</option>
                            <option value="email">Payload email</option>
                        </select>
                        <label>Kiểu sự kiện</label>
                    </div>
                    @include('components.common.form-date')
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-success" data-type="search-success">
                        <label>Tìm kiếm tin nhắn <span
                                class="amber-text">khi thành công</span></label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-error-begin-time-active"
                               data-type="search-error-begin-time-active">
                        <label>Tìm kiếm tin nhắn <span
                                class="amber-text">khi chưa đến thời gian sự kiện</span></label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-error-end-time-active"
                               data-type="search-error-end-time-active">
                        <label>Tìm kiếm tin nhắn <span
                                class="amber-text">khi quá đến thời gian sự kiện</span></label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-error-time-open"
                               data-type="search-error-time-open">
                        <label>Tìm kiếm tin nhắn <span
                                class="amber-text">khi chưa đến thời gian chat</span></label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-error-giftcode" data-type="search-error-giftcode">
                        <label>Tìm kiếm tin nhắn <span
                                class="amber-text">khi hết giftcode</span></label>
                    </div>
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
            <h5>Tin nhắn nhận từ user</h5>
        </div>
        @if($bot_message_heads->count())
            @component('components.table.index', ['headers' => $header_bot_heads])
                @slot('body')
                    @foreach($bot_message_heads as $key=>$bot_message_head)
                        <tr>
                            <td>{{ $key +  1 }}</td>
                            <td>{{$bot_message_head->fb_page_id . ' - ' . $bot_message_head->page->name}}</td>
                            <td class="red-text center">{{$bot_message_head->text}}</td>
                            <td class="center-align">
                                <span class="new badge {{ $bot_message_head->type === 'event' ? 'green':'amber' }}"
                                      data-badge-caption="{{$bot_message_head->type}}"></span>
                            </td>
                            <td>{{$bot_message_head->created_at}}</td>
                            <td>
                                <span>
                                    <a href="{{ route('setting.edit-message-head', ['id' => $bot_message_head->_id]) }}"
                                       class="amber-text"><span class="material-icons">mode_edit</span></a>
                                </span>
                                <span title="Update page"><a
                                        href="{{ route('setting.show-message-head', ['id' => $bot_message_head->_id]) }}"><span
                                            class="material-icons">remove_red_eye</span></a></span>
                                <span title="Delete page">
                                    <a data-id="{{ $bot_message_head->_id }}"
                                       data-text="{{ $bot_message_head->text }}"
                                       class="delete-call-bot-message modal-trigger"
                                       href="#call-bot-message-modal">
                                        <span class="material-icons red-text">delete</span>
                                    </a></span>
                            </td>
                        </tr>
                    @endforeach
                @endslot
                @slot('more')
                    {{--                    @if($bot_message_head->count() > 5)--}}
                    @if($bot_message_head->count())
                        <div class="input-field center row">
                            <a href="{{ route('setting.show-message-head', ['id' => 'call-bot-message']) }}">
                                <button class="btn">More...</button>
                            </a>
                        </div>
                    @endif
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
