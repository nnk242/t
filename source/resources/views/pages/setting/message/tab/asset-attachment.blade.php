<form class="container" method="POST">
    @csrf
    <input name="type_message" value="assets_attachments" hidden>
    <input id="bot_message_head_id_attachment" name="bot_message_head_id" hidden>
    <div class="card-panel">
        <div class="row">
            <div class="col s12">
                <div class="input-field">
                    <h4>Assets & Attachments</h4>
                </div>
                <div class="input-field col s12">
                    <i class="material-icons prefix">search</i>
                    <input type="text" class="autocomplete search-data-message-head"
                           data-type="bot_message_head_id_attachment">
                    <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                </div>
                <div class="input-field col s12">
                    <label>Nhập link</label>
                    <input type="url" class="validate" placeholder="Nhập link" required
                           name="attachment_payload_url">
                </div>
                <div class="input-field col s12">
                    <select name="attachment_type" class="attachment_type">
                        <option value="image" disabled selected>Chọn kiểu tin nhắn...</option>
                        <option value="image">Image</option>
                        <option value="audio">Audio</option>
                        <option value="video">Video</option>
                        <option value="file">File</option>
                    </select>
                    <label>Kiểu tin nhắn <span
                            class="amber-text">Chọn sai xác kiểu chắc chắn không gửi</span></label>
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
        @if($assets_attachments->count())
            @component('components.table.index', ['headers' => $header_assets_attachments])
                @slot('body')
                    @foreach($assets_attachments as $key=>$assets_attachment)
                        <tr>
                            <td>{{ $key +  1 }}</td>
                            <td class="amber-text">{{$assets_attachment->page->name . ' - ' . $assets_attachment->fb_page_id}}</td>
                            <td class="cyan-text">{{$assets_attachment->botMessageHead ? $assets_attachment->botMessageHead->text : ''}}</td>
                            <td class="red-text center">{{$assets_attachment->attachment_type}}</td>
                            <td class="text_" style="max-width: 100px">
                                <a title="{{$assets_attachment->attachment_payload_url}}"
                                   href="{{$assets_attachment->attachment_payload_url}}"
                                   target="_blank">{{$assets_attachment->attachment_payload_url}}</a>
                            </td>
                            <td><span class="new badge"
                                      data-badge-caption="{{$assets_attachment->type_notify}}"></span></td>
                            <td>{{$assets_attachment->created_at}}</td>
                            <td>
                                <span>
                                    <a href="{{ route('setting.message.edit', ['message' => $assets_attachment->_id]) }}"
                                       class="amber-text"><span class="material-icons">mode_edit</span></a>
                                </span>
                                <span title="Show"><a
                                        href="{{ route('setting.message.show', ['message' => $assets_attachment->_id]) }}"><span
                                            class="material-icons">remove_red_eye</span></a></span>
                                <span title="Delete page">
                                    <a data-id="{{ $assets_attachment->_id }}"
                                       data-text="{{ $assets_attachment->type_notify }}"
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
                        <a href="{{ route('setting.message.show', ['message' => 'assets-attachments']) }}">
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
