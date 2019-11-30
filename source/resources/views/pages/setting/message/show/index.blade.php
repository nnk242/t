@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-panel">
            <div class="col row input-field s12">
                <h4>Xem tin nhắn: <span class="orange-text">{{ $bot_message_reply->type_message }}</span></h4>
            </div>
            @isset($bot_message_reply->botMessageHead->text)
                <div class="col row input-field s12">
                    <span>Nội dung người dùng gửi: <b>{{$bot_message_reply->botMessageHead->text}}</b></span>
                </div>
            @endisset
            @if($bot_message_reply->type_message === 'text_messages')
                <div class="col row input-field s12">
                    <span>Nội dung người dùng gửi: <b>{{$bot_message_reply->text}}</b></span>
                </div>
            @endif
            @if($bot_message_reply->type_message === 'assets_attachments')
                <div class="col row input-field s12">
                    <span>attachment type: <b>{{$bot_message_reply->attachment_type}}</b></span>
                </div>
                <div class="col row input-field s12">
                    <span>attachment url: <a title="{{$bot_message_reply->attachment_payload_url}}" target="_blank"
                            href="{{$bot_message_reply->attachment_payload_url}}"><b>{{$bot_message_reply->attachment_payload_url}}</b></a></span>
                </div>
            @endif
            @if($bot_message_reply->type_message === 'assets_attachments')
                <div class="col row input-field s12">
                    <span>attachment type: <b>{{$bot_message_reply->attachment_type}}</b></span>
                </div>
                <div class="col row input-field s12">
                    <span>attachment url: <a title="{{$bot_message_reply->attachment_payload_url}}" target="_blank"
                            href="{{$bot_message_reply->attachment_payload_url}}"><b>{{$bot_message_reply->attachment_payload_url}}</b></a></span>
                </div>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/setting-message.js') }}"></script>
@endsection
