@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12">
            <ul class="tabs z-depth-1">
                <li class="tab col"><a href="#call-bot-message">Call bot message</a></li>
                <li class="tab col"><a href="#text-message">Text messages</a></li>
                <li class="tab col"><a href="#test2">Assets & Attachments</a></li>
                <li class="tab col"><a href="#test3">Message Templates</a></li>
                <li class="tab col"><a href="#test4">Quick Replies</a></li>
            </ul>
        </div>
        <div id="call-bot-message" class="col s12">
            @include('pages.setting.message.tab.call-bot-message')
        </div>
        <div id="text-message" class="col s12">
            <form class="container" method="POST">
                @csrf
                <input name="type_message" value="text_messages" hidden>
                <input type="text" name="bot_message_head" hidden>
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <h4>Text Messages</h4>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-data-message-head">
                                <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                            </div>
                            <div class="input-field col s12">
                                <label>Nhập tin nhắn trả lời người dùng</label>
                                <input placeholder="Nhập tin nhắn gửi đến người dùng" type="text" class="validate"
                                       name="text">
                            </div>
                            <div class="input-field col s12">
                                <select name="type_notify" class="type_notify">
                                    <option value="normal" disabled selected>Chọn loại tin nhắn chạy</option>
                                    <option value="normal">Normal</option>
                                    <option value="timer">Timer</option>
                                </select>
                                <label>Kiểu tin nhắn</label>
                            </div>
                            <div class="run_">
                                <div class="input-field">
                                    <div class="col s12">
                                        <label>Thời gian chat trong ngày</label>
                                        <div class="row">
                                            <div class="col s6">
                                                <input type="time" name="time_open[]">
                                            </div>
                                            <div class="col s6">
                                                <input type="time" name="time_open[]">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="input-field">
                                    <div class="col s12">
                                        <label>Thời gian hoạt động</label>
                                        <div class="row">
                                            <div class="col s4 l2">
                                                <input type="time" name="time_active[]">
                                            </div>
                                            <div class="col s8 l4">
                                                <input class="datepicker" name="date_active[]"
                                                       placeholder="Chọn ngày hoạt động">
                                            </div>
                                            <div class="col s4 l2">
                                                <input type="time" name="time_active[]">
                                            </div>
                                            <div class="col s8 l4">
                                                <input class="datepicker" name="date_active[]"
                                                       placeholder="Chọn ngày hoạt động">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="center-align">
                                <button class="btn">Gửi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="test3" class="col s12">Test 3</div>
        <div id="test4" class="col s12">Test 4</div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.tabs').tabs()
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            })

            $('.type_notify').on('change', function () {
                console.log($(this).val())
            })

            $('input.search-data-message-head').on('keyup', delay(function (e) {
                let text = $(this).val()
                let data = {}
                $.ajax({
                    url: "{{ route('setting.message-head') }}" + "?text=" + text,
                    success: function (response) {
                        response.forEach(element => {
                            text_ = element.text
                            data[text_] = null
                            console.log(data)
                            $('input.search-data-message-head').autocomplete({
                                data,
                            })
                        })
                    }
                })
            }, 500))
        })
    </script>
@endsection
