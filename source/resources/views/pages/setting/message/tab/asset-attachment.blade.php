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
