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
                        <h4>Assets Attachments</h4>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-data-message-head"
                               data-type="search-data-message-head">
                        <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span> <span
                                class="red-text">{{ isset($bot_message_reply->botMessageHead->text) ? $bot_message_reply->botMessageHead->text . ' - ' . $bot_message_reply->created_at : '' }}</span></label>
                    </div>
                    <div class="input-field col s12">
                        <label>Nhập link <a href="{{ $bot_message_reply->attachment_payload_url }}" target="_blank"
                                            class="text_"
                                            title="{{ $bot_message_reply->attachment_payload_url }}">{{ $bot_message_reply->attachment_payload_url }}</a>
                        </label>
                        <input type="url" class="validate" placeholder="Nhập link" required
                               value="{{ $bot_message_reply->attachment_payload_url }}" name="attachment_payload_url">
                    </div>
                    <div class="input-field col s12">
                        <select name="attachment_type" class="attachment_type">
                            <option value="image"
                                {{ $bot_message_reply->attachment_type == 'image' ? 'selected' : '' }}>Image
                            </option>
                            <option
                                value="audio"
                                {{ $bot_message_reply->attachment_type == 'audio' ? 'selected' : '' }}>Audio
                            </option>
                            <option
                                value="video"
                                {{ $bot_message_reply->attachment_type == 'video' ? 'selected' : '' }}>Video
                            </option>
                            <option
                                value="file"
                                {{ $bot_message_reply->attachment_type == 'file' ? 'selected' : '' }}>File
                            </option>
                        </select>
                        <label>Kiểu tin nhắn <span
                                class="amber-text">Chọn sai xác kiểu chắc chắn không gửi</span> <span
                                class="red-text">{{ $bot_message_reply->attachment_type }}</span></label>
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
