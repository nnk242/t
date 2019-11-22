@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12">
            <ul class="tabs z-depth-1">
                <li class="tab col"><a href="#call-bot-message" data-toggle="tab">Call bot message</a></li>
                <li class="tab col"><a href="#text-message" data-toggle="tab">Text messages</a></li>
                <li class="tab col"><a href="#assets-attachments" data-toggle="tab">Assets & Attachments</a></li>
                <li class="tab col"><a href="#message-templates" data-toggle="tab">Message Templates</a></li>
                <li class="tab col"><a href="#test4" data-toggle="tab">Quick Replies</a></li>
            </ul>
        </div>
        <div id="call-bot-message" class="col s12">
            @include('pages.setting.message.tab.call-bot-message')
        </div>
        <div id="text-message" class="col s12">
            @include('pages.setting.message.tab.text-message')
        </div>
        <div id="assets-attachments" class="col s12">
            @include('pages.setting.message.tab.asset-attachment')
        </div>
        <div id="message-templates" class="col s12">
            <form class="container" method="POST">
                @csrf
                <input name="type_message" value="message-templates" hidden>
                <input id="bot_message_head_id_template" name="bot_message_head_id" hidden>
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <h4>Message Templates</h4>
                            </div>
                            <div class="input-field col s12">
                                <i class="material-icons prefix">search</i>
                                <input type="text" class="autocomplete search-data-message-head"
                                       data-type="bot_message_head_id_template">
                                <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
                            </div>
                            <div class="element">
                                <div class="input-field col s12">
                                    <label>Nhập title</label>
                                    <input type="text" class="validate" placeholder="Nhập title" required name="title">
                                </div>
                                <div class="input-field col s12">
                                    <label>Nhập link ảnh</label>
                                    <input type="url" class="validate" placeholder="Nhập link ảnh" required
                                           name="image_url">
                                </div>
                                <div class="input-field col s12">
                                    <label>Nhập subtitle</label>
                                    <input type="text" class="validate" placeholder="Nhập subtitle" required
                                           name="subtitle">
                                </div>
                                <div class="input-field col s12">
                                    <label>Nhập group message template</label>
                                    <input type="number" class="validate" placeholder="Nhập group message template"
                                           name="group">
                                </div>
                            </div>
                            <div class="button">
                                <div class="row">
                                    <div class="col s4 button_">
                                        <div class="input-field">
                                            <h4 class="center">Button 1</h4>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="button_type[]" class="button_type">
                                                <option value="postback" disabled selected>Chọn loại button...</option>
                                                <option value="phone_number">Phone number</option>
                                                <option value="web_url">Web url</option>
                                                <option value="postback">Postback</option>
                                            </select>
                                            <label>Chọn loại button</label>
                                        </div>
                                        <div class="input-field col s12 button_title display-none">
                                            <label>Nhập title của button</label>
                                            <input type="text" class="validate" placeholder="Nhập title của button"
                                                   name="button_title[]">
                                        </div>
                                        <div class="input-field col s12 web_url display-none">
                                            <label>Nhập url</label>
                                            <input type="url" class="validate" placeholder="Nhập link"
                                                   name="button_url[]">
                                        </div>
                                        <div class="input-field col s12 button_phone display-none">
                                            <label>Số điện thoại</label>
                                            <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                                   name="payload[]">
                                        </div>
                                    </div>
                                    <div class="col s4 button_">
                                        <div class="input-field">
                                            <h4 class="center">Button 2</h4>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="button_type[]" class="button_type">
                                                <option value="postback" disabled selected>Chọn loại button...</option>
                                                <option value="phone_number">Phone number</option>
                                                <option value="web_url">Web url</option>
                                                <option value="postback">Postback</option>
                                            </select>
                                            <label>Chọn loại button</label>
                                        </div>
                                        <div class="input-field col s12 button_title display-none">
                                            <label>Nhập title của button</label>
                                            <input type="text" class="validate" placeholder="Nhập title của button"
                                                   name="button_title[]">
                                        </div>
                                        <div class="input-field col s12 web_url display-none">
                                            <label>Nhập url</label>
                                            <input type="url" class="validate" placeholder="Nhập link"
                                                   name="button_url[]">
                                        </div>
                                        <div class="input-field col s12 button_phone display-none">
                                            <label>Số điện thoại</label>
                                            <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                                   name="payload[]">
                                        </div>
                                    </div>
                                    <div class="col s4 button_">
                                        <div class="input-field">
                                            <h4 class="center">Button 3</h4>
                                        </div>
                                        <div class="input-field col s12">
                                            <select name="button_type[]" class="button_type">
                                                <option value="postback" disabled selected>Chọn loại button...</option>
                                                <option value="phone_number">Phone number</option>
                                                <option value="web_url">Web url</option>
                                                <option value="postback">Postback</option>
                                            </select>
                                            <label>Chọn loại button</label>
                                        </div>
                                        <div class="input-field col s12 button_title display-none">
                                            <label>Nhập title của button</label>
                                            <input type="text" class="validate" placeholder="Nhập title của button"
                                                   name="button_title[]">
                                        </div>
                                        <div class="input-field col s12 web_url display-none">
                                            <label>Nhập url</label>
                                            <input type="url" class="validate" placeholder="Nhập link"
                                                   name="button_url[]">
                                        </div>
                                        <div class="input-field col s12 button_phone display-none">
                                            <label>Số điện thoại</label>
                                            <input type="text" class="validate" placeholder="Nhập số điện thoại"
                                                   name="payload[]">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="input-field col s12">
                                <select name="attachment_type" class="attachment_type">
                                    <option value="generic" disabled selected>Chọn kiểu tin nhắn...</option>
                                    <option value="generic">Generic</option>
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
        </div>
        <div id="test4" class="col s12">Test 4</div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('textarea.materialize-textarea').characterCounter()
            $('.modal').modal()
            $('.tabs').tabs()
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            })

            $('.delete-call-bot-message').on('click', function () {
                let id = $(this).attr('data-id')
                let text = $(this).attr('data-text')
                $('#modal-body-notify').empty()
                $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + text + '</span>?')
                $('#call-bot-message-modal').find('form').attr('action', '/setting/message-head' + '/' + id)
            })

            $('.type_notify').on('change', function () {
                switch ($(this).val()) {
                    case 'timer':
                        $('.run_').removeClass('display-none')
                        break
                    default:
                        $('.run_').addClass('display-none')
                        break
                }
            })

            $('.button_type').on('change', function () {
                let button_ = $(this).closest('.button_')
                switch ($(this).val()) {
                    case 'postback':
                        button_.find('.button_title').removeClass('display-none')
                        button_.find('.web_url').addClass('display-none')
                        button_.find('.button_phone').addClass('display-none')
                        break
                    case 'web_url':
                        button_.find('.button_title').removeClass('display-none')
                        button_.find('.web_url').removeClass('display-none')
                        button_.find('.button_phone').addClass('display-none')
                        break
                    default:
                        button_.find('.button_title').removeClass('display-none')
                        button_.find('.web_url').addClass('display-none')
                        button_.find('.button_phone').removeClass('display-none')
                        break
                }
            })

            $('#type-head').on('change', function () {
                switch ($(this).val()) {
                    case 'event':
                        $('#run-event').removeClass('display-none')
                        break
                    default:
                        $('#run-event').addClass('display-none')
                        break
                }
            })

            let attr_search = $('input.search-data-message-head, input.search-success, input.search-error-begin-time-active, input.search-error-end-time-active, input.search-error-time-open, input.search-error-giftcode')

            attr_search.on('keyup', delay(function (e) {
                console.log($(this).attr('class'))
                let text = $(this).val()
                let url = ''
                let data = {}
                let data_id = {}

                attr_search.autocomplete({
                    data
                })

                if (this.getAttribute('data-type') === 'search-data-message-head' ||
                    this.getAttribute('data-type') === 'bot_message_head_id_attachment' ||
                    this.getAttribute('data-type') === 'bot_message_head_id_template') {
                    url = "{{ route('setting.message-head') }}" + "?text=" + text
                } else {
                    url = "{{ route('setting.message-reply') }}" + "?text=" + text
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
                        }
                    }
                )
            }, 500))

        })
    </script>
@endsection
