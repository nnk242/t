@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card-panel">
            <div class="input-field">
                <a href="{{ route('setting.message.index') }}" class="waves-effect waves-light btn"
                   title="Setting/Tin nhắn"><i
                        class="material-icons">chevron_left</i></a>
                <h5>Bot Head</h5>
                <div>
                    <b>Page: <span class="amber-text">{{ $bot_message_head->page->fb_page_id }}</span> <span
                            class="amber-text">{{ $bot_message_head->page->name }}</span></b>
                    <span class="new badge {{ $bot_message_head->type === 'event' ? 'green':'amber' }}"
                          data-badge-caption="{{ $bot_message_head->type }}"></span>
                </div>
                <div class="row col s12 input-field">
                    <b>Nội dung tin nhắn</b> <span class="amber-text">{{ $bot_message_head->text }}</span>
                </div>
                @if($bot_message_head->type === 'event')
                    <div class="row col s12 input-field">
                        <span>Thời gian sự kiện <span class="new badge"
                                                      data-badge-caption="{{ date('Y-m-d h:m:i A', $bot_message_head->begin_time_active) }}"></span>
                            <span class="new badge"
                                  data-badge-caption="{{ date('Y-m-d h:m:i A', $bot_message_head->end_time_active) }}"></span></span>
                    </div>
                    <div class="row col s12 input-field">
                        <span>Thời gian mở <span class="new badge"
                                                 data-badge-caption="{{ date('h:m:i A', strtotime(date('Y-m-d')) + (int)$bot_message_head->begin_time_open) }}"></span>
                            <span class="new badge"
                                  data-badge-caption="{{ date('h:m:i A', strtotime(date('Y-m-d')) + (int)$bot_message_head->end_time_open) }}"></span></span>
                    </div>
                    <div class="row col s12 input-field">
                        @if($bot_message_head->textSuccess)
                            <div>
                                <span>Nội dung tin nhắn khi thành công</span>
                                <div class="card-panel">
                                    <span>Loại tin nhắn: <b>{{ $bot_message_head->textSuccess->type_message }}</b></span>
                                    <p>Nội dung:</p>
                                    <pre>{{ $bot_message_head->textSuccess->text }}</pre>
                                    <p>Title:</p>
                                    <span>{{ $bot_message_head->textSuccess->title }}</span>
                                </div>
                            </div>
                        @endif
                        @if($bot_message_head->textErrorBeginTimeActive)
                            <div>
                                <span>Nội dung tin nhắn khi chưa đến event</span>
                                <div class="card-panel">
                                    <span>Loại tin nhắn: <b>{{ $bot_message_head->textErrorBeginTimeActive->type_message }}</b></span>
                                    <p>Nội dung:</p>
                                    <pre>{{ $bot_message_head->textErrorBeginTimeActive->text }}</pre>
                                    <p>Title:</p>
                                    <span>{{ $bot_message_head->textErrorBeginTimeActive->title }}</span>
                                </div>
                            </div>
                        @endif
                        @if($bot_message_head->textErrorEndTimeActive)
                            <div>
                                <span>Nội dung tin nhắn khi kết thúc event</span>
                                <div class="card-panel">
                                    <span>Loại tin nhắn: <b>{{ $bot_message_head->textErrorEndTimeActive->type_message }}</b></span>
                                    <p>Nội dung:</p>
                                    <pre>{{ $bot_message_head->textErrorEndTimeActive->text }}</pre>
                                    <p>Title:</p>
                                    <span>{{ $bot_message_head->textErrorEndTimeActive->title }}</span>
                                </div>
                            </div>
                        @endif
                        @if($bot_message_head->textErrorTimeOpen)
                            <div>
                                <span>Nội dung tin nhắn khi chưa mở event trong ngày</span>
                                <div class="card-panel">
                                    <span>Loại tin nhắn: <b>{{ $bot_message_head->textErrorTimeOpen->type_message }}</b></span>
                                    <p>Nội dung:</p>
                                    <pre>{{ $bot_message_head->textErrorTimeOpen->text }}</pre>
                                    <p>Title:</p>
                                    <span>{{ $bot_message_head->textErrorTimeOpen->title }}</span>
                                </div>
                            </div>
                        @endif
                        @if($bot_message_head->textErrorGift)
                            <div>
                                <span>Nội dung tin nhắn khi hết quà</span>
                                <div class="card-panel">
                                    <span>Loại tin nhắn: <b>{{ $bot_message_head->textErrorGift->type_message }}</b></span>
                                    <p>Nội dung:</p>
                                    <pre>{{ $bot_message_head->textErrorGift->text }}</pre>
                                    <p>Title:</p>
                                    <span>{{ $bot_message_head->textErrorGift->title }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="row col s12 input-field center-align">
                            <a href="{{ route('setting.edit-message-head', ['id' => $bot_message_head->_id]) }}"
                               class="btn">Sửa</a>
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection
