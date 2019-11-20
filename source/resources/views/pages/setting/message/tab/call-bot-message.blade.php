<form class="container" action="{{ route('setting.store-message-head') }}" method="POST">
    @csrf
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Ví dụ:</h4>
                    <p>User gửi tin nhắn đến với cú pháp cố định: <span class="green-text">ABC</span></p>
                    <p>User gửi tin nhắn đến với cú pháp linh hoạt hơn: <span
                            class="green-text">!'{value}'</span></p>
                </div>
                <div class="input-field">
                    <input placeholder="Nhập tin nhắn nhận từ người dùng" type="text" class="validate"
                           name="text">
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
        @if($bot_message_heads->count())
            @component('components.table.index', ['headers' => $header_bot_heads])
                @slot('body')
                    @foreach($bot_message_heads as $key=>$bot_message_head)
                        <tr>
                            <td>{{ $key +  1 }}</td>
                            <td>{{$bot_message_head->fb_page_id}}</td>
                            <td>{{$bot_message_head->page->name}}</td>
                            <td class="red-text center">{{$bot_message_head->text}}</td>
                            <td>{{$bot_message_head->updated_at}}</td>
                            <td>{{$bot_message_head->created_at}}</td>
                            <td>
                                <span title="Update page"><a
                                        href="{{ route('page.show', ['page' => $bot_message_head->fb_page_id]) }}"><span
                                            class="material-icons">mode_edit</span></a></span>
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
