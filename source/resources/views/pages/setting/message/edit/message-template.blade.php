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
                        <h4>Message Templates</h4>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete search-data-message-head"
                               data-type="bot_message_head_id_template">
                        <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span> <span
                                class="red-text">{{ isset($bot_message_reply->botMessageHead->text) ? $bot_message_reply->botMessageHead->text . ' - ' . $bot_message_reply->created_at : '' }}</span></label>
                    </div>
                    <div class="input-field col s12">
                        <select name="type_notify" class="type_notify">
                            <option value="normal" {{$bot_message_reply->type_notify === 'normal' ? 'selected' : ''}}>Normal</option>
                            <option value="timer" {{$bot_message_reply->type_notify === 'timer' ? 'selected' : ''}}>Timer</option>
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
    <div class="container">
        <div class="card-panel">
            @if($bot_message_reply->count())
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
                                    <a href="{{ route('setting.message.edit', ['message' => $text_message->_id]) }}"
                                       class="amber-text"><span class="material-icons">mode_edit</span></a>
                                </span>
                                    <span title="Show"><a
                                            href="{{ route('setting.message.show', ['message' => $text_message->_id]) }}"><span
                                                class="material-icons">remove_red_eye</span></a></span>
                                    <span title="Delete page">
                                    <a data-id="{{ $text_message->_id }}"
                                       data-text="{{ $text_message->text }}"
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
                            <a href="{{ route('setting.message.show', ['message' => 'text-message']) }}">
                                <button class="btn">More...</button>
                            </a>
                        </div>
                    @endslot
                @endcomponent
            @else
                <p class="text-center text-dark h3">Bạn chưa setup page hoặc chưa có nội dung nào...</p>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/setting-message.js') }}"></script>
@endsection
