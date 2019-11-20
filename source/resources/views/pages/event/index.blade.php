@extends('layouts.app')

@section('content')
    <div id="text-message" class="col s12">
        <form class="container" method="POST">
            @csrf
            <input name="type_message" value="text_messages" hidden>
            <input id="error_begin_time_active" name="error_time_active" hidden>
            <input id="error_end_time_active" name="error_time_active" hidden>
            <input id="error_time_open" name="error_time_open" hidden>
            <input id="error_giftcode" name="error_giftcode" hidden>
            <input id="success" name="success" hidden>
            <div class="card-panel">
                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                            <h4>TẠO SỰ KIỆN</h4>
                        </div>
                        <div class="input-field col s12">
                            <label>Nhập nội dung người dùng gửi</label>
                            <textarea placeholder="Nhập nội dung người dùng gửi..." class="materialize-textarea"
                                      name="text" data-length="630"></textarea>
                        </div>
                        <div class="input-field col s12">
                            <select name="type_notify" class="type_notify">
                                <option value="message" disabled selected>Chọn loại</option>
                                <option value="message">Message</option>
                                <option value="post">Post</option>
                            </select>
                            <label>Kiểu nhận nội dung</label>
                        </div>
                        <div class="center-align">
                            <button class="btn">Gửi</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(function () {
            $('textarea.materialize-textarea').characterCounter()

        })
    </script>
@endsection
