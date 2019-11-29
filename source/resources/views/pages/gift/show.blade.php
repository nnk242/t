@extends('layouts.app')

@section('content')
    <form class="container" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="card-panel">
            <div class="row">
                <div class="col s12">
                    <div class="input-field">
                        <h4>TẠO GIFT</h4>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">search</i>
                        <input type="text" class="autocomplete bot-message-head-id">
                        <input type="text" class="autocomplete" id="bot-message-head-id" name="bot_message_head_id"
                               hidden>
                        <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                    </div>
                    <div class="input-field col s12">
                        <select class="type" name="type">
                            <option value="normal">Normal</option>
                            <option value="file">File</option>
                        </select>
                        <label>Kiểu up giftcode</label>
                    </div>
                    <div class="input-field col s12 display-none" id="file">
                        <div class="file-field input-field">
                            <div class="btn">
                                <span>File</span>
                                <input type="file" name="file">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="file phải là excel">
                            </div>
                        </div>
                    </div>
                    <div class="input-field col s12" id="hand">
                        <label>Nhập nội dung giftcode</label>
                        <input type="text" placeholder="Nhập nội dung giftcode..." class="validate" name="code"
                               data-length="100">
                    </div>
                    <div class="input-field col s12">
                        <label>Sô lượng giftcode. Lưu ý: <span class="red-text">-1</span> đối với code dùng nhiều
                            lần</label>
                        <input type="number" placeholder="Nhập số lượng giftcode..." class="validate" name="amount"
                               min="-1">
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
                                      data-badge-caption="{{$bot_message_head->gifts->count()}}"></span>
                                </td>
                                <td>{{$bot_message_head->created_at}}</td>
                                <td>
                                <span>
                                    <a href="{{ route('gift.show', ['gift' => $bot_message_head->_id]) }}"
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
@endsection

@section('js')
    <script>
        $(function () {
            $('textarea.materialize-textarea').characterCounter()

            $('.bot-message-head-id').devbridgeAutocomplete({
                serviceUrl: "/message/search/data/head-event",
                type: 'GET',
                onSelect: function (suggestion) {
                    $('#bot-message-head-id').attr('value', suggestion.data)
                },
                showNoSuggestionNotice: true,
                noSuggestionNotice: 'Không tìm thấy dữ liệu nào...',
            })

            $('.type').on('change', function () {
                if ($(this).val() === 'file') {
                    $('#hand').addClass('display-none')
                    $('#file').removeClass('display-none')
                } else {
                    $('#hand').removeClass('display-none')
                    $('#file').addClass('display-none')
                }
            })
        })
    </script>
@endsection
