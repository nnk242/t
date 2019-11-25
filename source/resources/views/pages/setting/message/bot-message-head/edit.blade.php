@extends('layouts.app')

@section('content')
    <form class="container" action="{{ route('setting.store-message-head') }}" method="POST">
        @csrf
        <input id="input-error-begin-time-active" name="text_error_begin_time_active_id" hidden>
        <input id="input-error-end-time-active" name="text_error_end_time_active_id" hidden>
        <input id="input-error-time-open" name="text_error_time_open_id" hidden>
        <input id="input-error-gift" name="text_error_gift_id" hidden>
        <input id="input-success-id" name="text_success_id" hidden>
        <div class="card-panel">
            <div class="row">
                <div class="col s12">
                    <div class="input-field row col s12">
                        <h4>Ví dụ:</h4>
                        <p>User gửi tin nhắn đến với cú pháp cố định: <span class="green-text">ABC</span></p>
                        <p>User gửi tin nhắn đến với cú pháp linh hoạt hơn: <span
                                class="green-text">!'{value}'</span></p>
                    </div>
                    <div class="input-field row col s12">
                    <textarea placeholder="Nhập tin nhắn nhận từ người dùng" type="text"
                              class="validate materialize-textarea" data-length="20"
                              name="text">{{ isset($bot_message_head) ? $bot_message_head->text:'' }}</textarea>
                    </div>
                    <div class="input-field row col s12">
                        <select class="type" id="type-head" name="type">
                            <option value="normal" disabled>Chọn loại tin nhắn chạy</option>
                            <option
                                value="normal" {{ isset($bot_message_head) ?
                                 ($bot_message_head->type === 'normal' ? 'selected':'') : '' }}>Normal
                            </option>
                            <option
                                value="event" {{ isset($bot_message_head) ?
                                 ($bot_message_head->type === 'event' ? 'selected':'') : '' }}>Event
                            </option>
                        </select>
                        <label>Kiểu tin nhắn</label>
                    </div>

                    <div class="run-event" id="run-event">
                        <div class="input-field">
                            <div class="col s12 row">
                                <label>Thời gian chat trong ngày <b
                                        class="orange-text">{{ date('h-i A', strtotime(date('Y-m-d')) + (int)$bot_message_head->begin_time_open) }}</b>
                                    - <b
                                        class="orange-text">{{ date('h-i A', strtotime(date('Y-m-d')) + (int)$bot_message_head->end_time_open) }}</b></label>
                                <div class="row">
                                    <div class="col s6">
                                        <input type="time" name="time_open[]"
                                               value="{{ date('H-i', strtotime(date('Y-m-d')) + (int)$bot_message_head->begin_time_open) }}">
                                    </div>
                                    <div class="col s6">
                                        <input type="time" name="time_open[]"
                                               value="{{ date('H-i', strtotime(date('Y-m-d')) + (int)$bot_message_head->end_time_open) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="input-field">
                            <div class="col s12 row">
                                <label>Thời gian hoạt động <b
                                        class="orange-text">{{ date('Y-m-d h-i A', (int)$bot_message_head->begin_time_active) }}</b>
                                    - <b
                                        class="orange-text">{{ date('Y-m-d h-i A', (int)$bot_message_head->end_time_active) }}</b></label>
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

                        <div class="input-field row col s12">
                            <select class="type_">
                                <option value="text_messages" disabled selected>Chọn loại tin tìm kiếm</option>
                                <option value="text_messages">Text messages</option>
                                <option value="assets_attachments">Assets & Attachments</option>
                                <option value="message_templates">Message templates</option>
                                <option value="quick_replies">Quick replies</option>
                            </select>
                            <label>Tin nhắn</label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" class="autocomplete search-success" data-type="search-success">
                            <label>Tìm kiếm tin nhắn <span
                                    class="amber-text">khi thành công</span> <b
                                    class="orange-text">{{ $bot_message_head->textSuccess?
                                     ($bot_message_head->textSuccess->text ? $bot_message_head->textSuccess->text : $bot_message_head->textSuccess->title) . ' - ' . $bot_message_head->textSuccess->created_at : '' }}</b></label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" class="autocomplete search-error-begin-time-active"
                                   data-type="search-error-begin-time-active">
                            <label>Tìm kiếm tin nhắn <span
                                    class="amber-text">khi chưa đến thời gian sự kiện</span> <b
                                    class="orange-text">{{ $bot_message_head->textErrorBeginTimeActive?
                                     ($bot_message_head->textErrorBeginTimeActive->text ? $bot_message_head->textErrorBeginTimeActive->text : $bot_message_head->textErrorBeginTimeActive->title) . ' - ' . $bot_message_head->textErrorBeginTimeActive->created_at : '' }}</b></label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" class="autocomplete search-error-end-time-active"
                                   data-type="search-error-end-time-active">
                            <label>Tìm kiếm tin nhắn <span
                                    class="amber-text">khi quá đến thời gian sự kiện</span> <b
                                    class="orange-text">{{ $bot_message_head->textErrorEndTimeActive?
                                     ($bot_message_head->textErrorEndTimeActive->text ? $bot_message_head->textErrorEndTimeActive->text : $bot_message_head->textErrorEndTimeActive->title) . ' - ' . $bot_message_head->textErrorEndTimeActive->created_at : '' }}</b></label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" class="autocomplete search-error-time-open"
                                   data-type="search-error-time-open">
                            <label>Tìm kiếm tin nhắn <span
                                    class="amber-text">khi chưa đến thời gian chat</span> <b
                                    class="orange-text">{{ $bot_message_head->textErrorTimeOpen?
                                     ($bot_message_head->textErrorTimeOpen->text ? $bot_message_head->textErrorTimeOpen->text : $bot_message_head->textErrorTimeOpen->title) . ' - ' . $bot_message_head->textErrorTimeOpen->created_at : '' }}</b></label>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" class="autocomplete search-error-giftcode"
                                   data-type="search-error-giftcode">
                            <label>Tìm kiếm tin nhắn <span
                                    class="amber-text">khi hết giftcode</span> <b
                                    class="orange-text">{{ $bot_message_head->textErrorGift?
                                     ($bot_message_head->textErrorGift->text ? $bot_message_head->textErrorGift->text : $bot_message_head->textErrorGift->title) . ' - ' . $bot_message_head->textErrorGift->created_at : '' }}</b></label>
                        </div>
                    </div>
                    <div class="center-align">
                        <button class="btn">Gửi</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            })

            let attr_search = $('input.search-data-message-head, input.search-success, input.search-error-begin-time-active, input.search-error-end-time-active, input.search-error-time-open, input.search-error-giftcode')

            attr_search.on('keyup', delay(function (e) {
                let text = $(this).val()
                let url = ''
                let data = {}
                let data_id = {}

                attr_search.autocomplete({
                    data
                })
                var type_ = $(this).closest('.run-event').find('.type_').val()
                if (this.getAttribute('data-type') === 'search-data-message-head' ||
                    this.getAttribute('data-type') === 'bot_message_head_id_attachment' ||
                    this.getAttribute('data-type') === 'bot_message_head_id_template' ||
                    this.getAttribute('data-type') === 'bot_message_head_id_quick_reply') {
                    url = "{{ route('setting.message-head') }}" + "?text=" + text
                } else {
                    url = "{{ route('setting.message-reply') }}" + "?text=" + text + '&type=' + type_
                }

                async function doAiax() {
                    const result = await $.ajax({
                        url,
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
                            if ((this.$el[0]).getAttribute('data-type') === 'search-success') {
                                $('#input-success-id').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'search-error-giftcode') {
                                $('#input-error-gift').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'search-error-time-open') {
                                $('#input-error-time-open').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'search-error-begin-time-active') {
                                $('#input-error-begin-time-active').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'search-error-end-time-active') {
                                $('#input-error-end-time-active').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'search-data-message-head') {
                                $('#bot_message_head_id_text_messages').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'bot_message_head_id_attachment') {
                                $('#bot_message_head_id_attachment').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'bot_message_head_id_template') {
                                $('#bot_message_head_id_template').attr('value', data_id[val])
                            }
                            if ((this.$el[0]).getAttribute('data-type') === 'bot_message_head_id_quick_reply') {
                                $('#bot_message_head_id_quick_reply').attr('value', data_id[val])
                            }
                        }
                    }
                )
            }, 500))
        })
    </script>
@endsection
