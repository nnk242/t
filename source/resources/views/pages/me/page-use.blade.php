@extends('layouts.app')
@section('css')

@endsection
@section('content')
    @include('pages.me.header.index')
    <div class="row">
        <div class="col s12">
            @if($pages->count())
                <div class="container">
                    <div class="row">
                        <div class="col s12">
                            <div class="row">
                                <div class="input-field col s12">
                                    <i class="material-icons prefix">search</i>
                                    <input type="text" id="search-input">
                                    <label for="seach-input">Tìm kiếm</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="center-align display-none" id="preloader">
                    @include('components.preloader.default')
                </div>

                <form method="POST" action="{{ route('me.page-use.store') }}" class="display-block" id="form_">
                    @csrf
                    <span class="new badge pink cursor-pointer" id="pick-all" data-badge-caption="Chọn tất cả"
                          check="0"></span>
                    <div class="scream-item mb-3">
                        @foreach($pages as $value)
                            <div class="item-element" title="{{ $value->fb_page_id . $value->page->name }}">
                                <p>
                                    <label>
                                        <input type="checkbox" class="pick" name="arr_user_page_id[]"
                                               value="{{ $value->fb_page_id }}"
                                               @if(in_array($value->fb_page_id, $page_use)) checked="checked" @endif
                                        >
                                        <span><img src="{{ $value->page->picture }}" class="btn-floating"/></span>
                                <p class="center-align text_">{{ $value->page->name }}</p>
                                </label>
                                </p>
                            </div>
                        @endforeach
                    </div>

                    <div class="center-align ">
                        <button class="btn">Sử dụng ngay!!!</button>
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
        $('#search-input').on('keyup', function () {
            $('#preloader').addClass('display-block')
            $('#preloader').removeClass('display-none')

            $('#form_').addClass('display-none')
            $('#form_').removeClass('display-block')
        })

        $('#search-input').on('keyup',
            delay(function (e) {
                $('#preloader').addClass('display-none')
                $('#preloader').removeClass('display-block')

                $('#form_').addClass('display-block')
                $('#form_').removeClass('display-none')

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
    </script>
@endsection
