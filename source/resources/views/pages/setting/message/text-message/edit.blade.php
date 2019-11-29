@extends('layouts.app')

@section('content')
    <form class="container" method="POST" action="{{ route('setting.message.store') }}">
        @csrf
        <input value="{{$bot_message_reply->_id}}" name="_id" hidden>
        <input name="type_message" value="text_messages" hidden>
        <input id="bot_message_head_id_text_messages" name="bot_message_head_id" hidden
               value="{{ isset($bot_message_reply->botMessageHead->_id) ? $bot_message_reply->botMessageHead->_id : '' }}">
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
                        <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span> <span
                                class="red-text">{{ isset($bot_message_reply->botMessageHead->text) ? $bot_message_reply->botMessageHead->text . ' - ' . $bot_message_reply->created_at : '' }}</span></label>
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập tin nhắn trả lời người dùng <span
                                class="red-text">{{ isset($bot_message_reply->text) ? $bot_message_reply->text : '' }}</span></label>
                        <textarea class="validate materialize-textarea"
                                  placeholder="Nhập tin nhắn gửi đến người dùng" name="text" data-length="2000"
                                  maxlength="2000">{{ isset($bot_message_reply->text) ? $bot_message_reply->text : '' }}</textarea>
                    </div>
                    <div class="input-field col s12">
                        <select name="type_notify" class="type_notify">
                            <option value="normal"
                                {{ $bot_message_reply->type_notify === 'normal' ? 'selected' : '' }}>Normal
                            </option>
                            <option value="timer"
                                {{ $bot_message_reply->type_notify === 'timer' ? 'selected' : '' }}>Timer
                            </option>
                        </select>
                        <label>Kiểu tin nhắn <span class="red-text">{{ $bot_message_reply->type_notify }}</span></label>
                    </div>
                    <div class="run_ display-none">
                        <div class="col row s12 input-field orange-text">Bạn phải nhập lại thời gian hẹn giờ</div>
                        @include('components.common.form-date')
                    </div>
                    <div class="center-align">
                        <button class="btn">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('js')
    <script src="{{ asset('js/setting-message.js') }}"></script>
@endsection
