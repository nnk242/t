@extends('layouts.app')

@section('content')
    <div class="container">
        <div class=" card-panel">
            <a href="{{ route('setting.message.index') }}" class="waves-effect waves-light btn"
               title="Setting/Tin nhắn"><i
                    class="material-icons">chevron_left</i></a>
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
                    @slot('paginate')
                        {{ $bot_message_heads->appends(request()->input())->links() }}
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
        $(document).ready(function () {
            $('.modal').modal()
            $('.delete-call-bot-message').on('click', function () {
                let id = $(this).attr('data-id')
                let text = $(this).attr('data-text')
                $('#modal-body-notify').empty()
                $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + text + '</span>?')
                $('#call-bot-message-modal').find('form').attr('action', '/setting/message-head' + '/' + id)
            })
        })
    </script>
@endsection
