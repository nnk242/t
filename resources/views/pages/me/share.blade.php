@extends('layouts.app')
@section('css')

@endsection
@section('content')
    @include('pages.me.header.index')
    <div class="row">
        <div class="col s12">
            @if($pages->count())
                <form method="POST" action="{{ route('me.share.store') }}">
                    @csrf
                    <span class="new badge pink cursor-pointer" id="pick-all" data-badge-caption="Chọn tất cả"
                          check="0"></span>
                    <div class="scream-item mb-3">
                        @foreach($pages as $page)
                            <div class="item-element" title="{{ $page->name }}">
                                <p>
                                    <label>
                                        <input type="checkbox" id="p_{{ $page->user_id_fb_page_id }}" class="pick"
                                               name="arr_page_id[]" value="{{ $page->id }}"/>
                                        <span><img src="{{ $page->picture }}" class="btn-floating"/></span>
                                <p class="center-align">{{ Str::limit($page->name, 11) }}</p>
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
            // var chip = {
            //     tag: 'chip content',
            //     image: '', //optional
            // }
            // $('.chips').chips()

            $('.chips-placeholder').chips({
                placeholder: 'Nhập email',
                secondaryPlaceholder: '+Email',
                minLength: 1,
                onChipAdd: function () {
                    let val = this.chipsData
                    let length = val.length

                    if (validateEmail(val[length - 1].tag)) {
                        $('#arr_email').append('<input key="' + val[length - 1].tag + '" name="arr_email[]" class="arr_email" value="' + val[length - 1].tag + '">')
                    } else {
                        this.chipsData.pop()
                        $('.chip').last().remove()
                        return false
                    }
                    // $('#arr_email').append('<input key="' + (val.length - 1) + '" name="arr_email[]">')
                    // console.log(val.length);
                },
                onChipDelete: function () {
                    let outVal = []
                    let val = this.chipsData
                    $('#arr_email input').each(function (idx, el) {
                        outVal.push($(this).attr('key'))
                    })

                    val.forEach(function (element, key) {
                        if (outVal.indexOf(element.tag) != -1) {
                            console.log(element.tag)
                        } else {
                            $('#arr_email .arr_email').find(`[key='${element}']`).remove()
                        }
                    })
                    // console.log(val.values(object1))
                    // console.log(val)
                }
            })
        })
    </script>
@endsection
