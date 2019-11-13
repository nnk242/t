@extends('layouts.app')
@section('css')

@endsection
@section('content')
    @include('pages.me.header.index')
    <div class="row">
        <div class="col s12">
            @if($user_pages->count())
                <form method="POST" action="{{ route('me.share.store') }}">
                    @csrf
                    <span class="new badge pink cursor-pointer" id="pick-all" data-badge-caption="Chọn tất cả"
                          check="0"></span>
                    <div class="scream-item mb-3">
                        @foreach($user_pages as $value)
                            <div class="item-element" title="{{ $value->name }}">
                                <p>
                                    <label>
                                        <input type="checkbox" id="p_{{ $value->user_page_id }}" class="pick"
                                               name="arr_user_page_id[]" value="{{ $value->id }}"/>
                                        <span><img src="{{ $value->page->picture }}" class="btn-floating"/></span>
                                <p class="center-align">{{ Str::limit($value->name, 11) }}</p>
                                </label>
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="row">
                        <div class="col s12">
                            <label for="mail">Email của user bạn muốn phân quyền</label>
                            <div class="chips chips-placeholder">
                            </div>
                            <div id="arr_email"></div>
                            <button class="btn">Gửi người dùng</button>
                        </div>
                    </div>
                </form>
            @else
                <p>Bạn chưa thêm page...</p>
                <h4>Hướng dẫn:</h4>
                <p><b>B1:</b> Cập nhật access token cá nhân</p>
                <p><b>B2:</b> Thêm hoặc cập nhật page</p>
            @endif
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/me_.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.chips-placeholder').chips({
                placeholder: 'Nhập email',
                secondaryPlaceholder: '+Email',
                minLength: 1,
                onChipAdd: function () {
                    let val = this.chipsData
                    let length = val.length

                    if (validateEmail(val[length - 1].tag)) {
                        $('#arr_email').append('<input name="arr_email[]" class="arr_email" hidden value="' + val[length - 1].tag + '">')
                    } else {
                        this.chipsData.pop()
                        $('.chip').last().remove()
                        return false
                    }
                    // $('#arr_email').append('<input key="' + (val.length - 1) + '" name="arr_email[]">')
                    // console.log(val.length);
                },
                onChipDelete: function () {
                    let val = this.chipsData
                    let chip_data = []

                    val.forEach(function (element, key) {
                        chip_data.push(element.tag)
                    })

                    $('#arr_email input').each(function (idx, el) {
                        if (chip_data.indexOf($(this).attr('value')) != -1) {
                            console.log($(this).attr('value'))
                        } else {
                            $(this).remove()
                        }
                    })
                }
            })
        })
    </script>
@endsection
