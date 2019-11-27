@extends('layouts.app')
@section('css')
    <style type="text/css">
        .autocomplete-suggestions {
            border: 1px solid #999;
            background: #FFF;
            overflow: auto;
        }

        .autocomplete-suggestion {
            padding: 2px 5px;
            white-space: nowrap;
            overflow: hidden;
        }

        .autocomplete-selected {
            background: #F0F0F0;
        }

        .autocomplete-suggestions strong {
            font-weight: normal;
            color: #3399FF;
        }

        .autocomplete-group {
            padding: 2px 5px;
        }

        .autocomplete-group strong {
            display: block;
            border-bottom: 1px solid #000;
        }

    </style>
@endsection
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
                                <td><span class=""></span>{{$value->botMessageReply->text}}</td>
                                <td>{{$value->email}}</td>
                                <td><img src="{{$value->avatar}}" width="40"></td>
                                <td>{{$value->role}}</td>
                                <td>{{$value->updated_at}}</td>
                                <td>
                                    @if(\Illuminate\Support\Facades\Auth::user()->email !== $value->email)
                                        <span title="Delete page">
                                            <a data-id="{{ $value->_id }}"
                                               data-email="{{ $value->email }}"
                                               class="delete-item modal-trigger"
                                               href="#delete-modal"><img width="15"
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
    <script src="{{ asset('js/jquery.autocomplete.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $(".bot_message_reply_id").devbridgeAutocomplete({
                serviceUrl: "{{ route('search-data') }}",
                type: 'GET',
                onSelect: function (suggestion) {
                    $('#bot_message_reply_id').attr('value', suggestion.data)
                    // $('#selection').html('You selected: ' + suggestion.value + ', ' + suggestion.data);
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
        })
    </script>
@endsection
