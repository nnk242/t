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
                        <div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-success">
                                <label>Tìm kiếm tin nhắn <span
                                        class="amber-text">khi thành công</span></label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-error-begin-time-active" key="">
                                <label>Tìm kiếm tin nhắn <span
                                        class="amber-text">khi chưa đến thời gian sự kiện</span></label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-error-end-time-active">
                                <label>Tìm kiếm tin nhắn <span
                                        class="amber-text">khi quá đến thời gian sự kiện</span></label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-error-time-open">
                                <label>Tìm kiếm tin nhắn <span
                                        class="amber-text">khi chưa đến thời gian chat</span></label>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-error-giftcode">
                                <label>Tìm kiếm tin nhắn <span
                                        class="amber-text">khi hết giftcode</span></label>
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
@endsection

@section('js')
    <script>
        $(function () {
            $('textarea.materialize-textarea').characterCounter()

            let attr_search = $('input.search-success, input.search-error-begin-time-active, input.search-error-end-time-active, input.search-error-time-open, input.search-error-giftcode')

                attr_search.on('keyup', delay(function (e) {
                    console.log($(this).attr('class'))
                    let text = $(this).val()
                    let data = {}
                    let data_id = {}

                    attr_search.autocomplete({
                        data
                    })

                    async function doAiax() {
                        const result = await $.ajax({
                            url: "{{ route('setting.message-head') }}" + "?text=" + text,
                            success: function (response) {
                                response.forEach(element => {
                                    text_ = element.text
                                    _id = element._id
                                    data[text_] = null
                                    data_id[text_] = _id
                                    return data
                                })
                            }
                        })
                        return result
                    }

                    doAiax()

                    attr_search.autocomplete({
                            data,
                            onAutocomplete: function (val) {
                                $('#error_begin_time_active').attr('value', data_id[val])
                            }
                        }
                    )
                }, 500))
        })
    </script>
@endsection
