@extends('layouts.app')
@section('content')
    <div id="text-message" class="col s12">
        <div class="col s12">
            <ul class="tabs z-depth-1">
                <li class="tab col"><a href="{{ route('message.index') }}" data-toggle="tab">Gửi hàng loạt</a></li>
                {{--                <li class="tab col"><a href="#text-message" data-toggle="tab">Text messages</a></li>--}}
                {{--                <li class="tab col"><a href="#assets-attachments" data-toggle="tab">Assets & Attachments</a></li>--}}
                {{--                <li class="tab col"><a href="#message-templates" data-toggle="tab">Message Templates</a></li>--}}
                {{--                <li class="tab col"><a href="#quick-replies" data-toggle="tab">Quick Replies</a></li>--}}
            </ul>
        </div>
        <form class="container" method="POST">
            @csrf
            <div class="card-panel">
                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                            <h4>Gửi tin nhắn hàng loạt</h4>
                        </div>
                        <div class="input-field col s12">
                            <i class="material-icons prefix">search</i>
                            <input type="text" class="autocomplete bot_message_reply_id">
                            <input type="text" hidden name="bot_message_reply_id" id="bot_message_reply_id">
                            <label>Tìm kiếm tin nhắn <span class="amber-text">BOT</span></label>
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
                        <div class="input-field col s12">
                            <label>Thời gian tương tác gần nhất VD: Trong vòng 8H thì điền là 8</label>
                            <input placeholder="Nhập số thời gian. Tính bằng giờ..." class="validate" type="number"
                                   name="time_interactive">
                        </div>
                        <div>
                            <div class="col s12">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <i class="material-icons prefix">search</i>
                                        <input type="text" id="search-input">
                                        <label for="seach-input">Tìm kiếm</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-field col s12">
                            <span class="new badge pink cursor-pointer" id="pick-all"
                                  data-badge-caption="Chọn tất cả"
                                  check="0"></span>
                                <div class="center-align display-none" id="preloader">
                                    @include('components.preloader.default')
                                </div>
                                <div class="scream-item mb-3" id="scream-item">
                                    @foreach($pages as $value)
                                        <div class="item-element" title="{{ $value->fb_page_id . $value->page->name }}">
                                            <p>
                                                <label>
                                                    <input type="checkbox" class="pick" name="arr_user_page_id[]"
                                                           value="{{ $value->fb_page_id }}"
                                                    >
                                                    <span><img src="{{ $value->page->picture }}"
                                                               class="btn-floating"/></span>
                                            <p class="center-align text_">{{ $value->page->name }}</p>
                                            </label>
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="input-field col s12">
                            <select name="status">
                                <option value="0" disabled selected>Chạy tin nhắn</option>
                                <option value="0">Đóng</option>
                                <option value="1">Mở</option>
                            </select>
                            <label>Status</label>
                        </div>
                        <div class="center-align">
                            <button class="btn">Gửi</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div class="row col s12 card-panel">
            @if($data->count())
                @component('components.table.index', ['headers' => $headers])
                    @slot('body')
                        @foreach($data as $key=>$value)
                            <tr>
                                <td>{{ $key +  1 }}</td>
                                <td><span
                                        class=""></span>{{$value->botMessageReply->text ? $value->botMessageReply->text :
                                        '"' . $value->botMessageReply->type_message . '"'}}
                                </td>
                                <td style="width: 280px">
                                    @foreach($value->broadcastPages as $broadcast_page)
                                        <span
                                            title="{{ $broadcast_page->page->fb_page_id }} - {{ $broadcast_page->page->name }}"
                                            class="new badge amber"
                                            data-badge-caption="{{ $broadcast_page->page->fb_page_id }} - {{ $broadcast_page->page->name }}"></span>
                                    @endforeach
                                </td>
                                <td class="brown-text center">{{$value->time_interactive}}</td>
                                <td style="width: 280px">
                                    @if($value->begin_time_active)
                                        <span
                                            title="{{date('Y-m-d H:i:s', $value->begin_time_active)}}"
                                            class="new badge teal"
                                            data-badge-caption="{{date('Y-m-d H:i:s', $value->begin_time_active)}}"></span>
                                        - <span
                                            title="{{date('Y-m-d H:i:s', $value->end_time_active)}}"
                                            class="new badge teal"
                                            data-badge-caption="{{date('Y-m-d H:i:s', $value->end_time_active)}}"></span>
                                    @endif
                                </td>
                                <td class="center">
                                    <div class="switch" data-id="{{ $value->_id }}">
                                        <label>
                                            <input class="status"
                                                   type="checkbox" {{$value->status === 1 ? 'checked' : ''}}>
                                            <span class="lever"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>{{$value->updated_at}}</td>
                                <td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->email !== $value->email)
                                        <span title="Delete page">
                                            <a data-id="{{ $value->_id }}"
                                               data-email="{{ $value->email }}"
                                               class="delete-item modal-trigger"
                                               href="#delete-modal">
                                                <img width="15"
                                                     src="{{ asset('icons/actions/trash-alt.svg') }}"></a></span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endslot
                    @slot('paginate')
                        {{ $data->appends(request()->input())->links() }}
                    @endslot
                @endcomponent
            @endif
        </div>
    </div>
    @component('components.modal.index', ['modal_id' => 'delete-modal', 'modal_title' => 'Xoá tin nhắn người dùng gửi', 'modal_form_action' => '', 'is_delete' => true])
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
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $(".bot_message_reply_id").devbridgeAutocomplete({
                serviceUrl: "{{ route('search-data') }}",
                type: 'GET',
                onSelect: function (suggestion) {
                    $('#bot_message_reply_id').attr('value', suggestion.data)
                },
                showNoSuggestionNotice: true,
                noSuggestionNotice: 'Không tìm thấy dữ liệu nào...',


            })
            $('.modal').modal()
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd'
            })

            $('.delete-item').on('click', function () {
                let id = $(this).attr('data-id')
                let email = $(this).attr('data-email')
                $('#modal-body-notify').empty()
                $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + email + '</span>?')
                $('#delete-modal').find('form').attr('action', '/role' + '/' + id)
            })

            $('#search-input').on('keyup', function () {
                $('#preloader').addClass('display-block')
                $('#preloader').removeClass('display-none')

                $('#scream-item').addClass('display-none')
                $('#scream-item').removeClass('display-block')
            })

            $('#search-input').on('keyup',
                delay(function (e) {
                    $('#preloader').addClass('display-none')
                    $('#preloader').removeClass('display-block')

                    $('#scream-item').addClass('display-block')
                    $('#scream-item').removeClass('display-none')

                    let str_search = stripUnicode($(this).val()).toUpperCase()
                    $('.item-element').each(function () {
                        let str = stripUnicode($(this).attr('title')).toUpperCase()
                        if (str.indexOf(str_search) >= 0) {
                            $(this).removeClass('display-none')
                        } else {
                            $(this).addClass('display-none')
                        }
                    })
                }, 500)
            )

            $(document).on('click', '#pick-all', function () {
                let check = parseInt($(this).attr('check'))
                if (check === 0) {
                    $('.pick').prop('checked', true)
                    $('#pick-all').attr('check', 1)
                } else {
                    $('.pick').prop('checked', false)
                    $('#pick-all').attr('check', 0)
                }
            })
            $('.status').on('change', function () {
                let this_ = $(this)
                let id = $(this).closest('.switch').attr('data-id')
                let is_checked = $(this).prop('checked')
                let url = 'message/update/status/' + id
                let method = 'POST'
                let data = {
                    is_checked: is_checked ? 1 : 0,
                    _token: '{{ @csrf_token() }}',
                    _method: 'PUT'
                }
                $(this).attr('disabled', true)
                $.ajax({
                    url,
                    method,
                    data,
                    success: function (res) {
                        this_.removeAttr('disabled')
                    }, catch: function () {
                        this_.prop('checked', is_checked ? false : true)
                        this_.removeAttr('disabled')
                    }
                })
            })
        })
    </script>
@endsection
