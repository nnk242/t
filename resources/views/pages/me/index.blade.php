@extends('layouts.app')
@section('css')

@endsection
@section('content')
    <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-md-12 py-5">
                <div class="row mb-5">
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.accessToken')}}">
                            <button class="btn btn-primary">{{__('Tổng quan')}}</button>
                        </a>
                    </div>
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.accessToken')}}">
                            <button class="btn btn-primary">{{__('Chia sẻ page')}}</button>
                        </a>
                    </div>
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.accessToken')}}">
                            <button class="btn btn-primary">{{__('Quản lý chia sẻ')}}</button>
                        </a>
                    </div>
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.accessToken')}}">
                            <button class="btn btn-primary">{{__('Cập nhật access token cá nhân')}}</button>
                        </a>
                    </div>
                </div>
                @if($pages->count())
                    <form method="POST">
                        @csrf
                        <div class="mt2 mb-2">
                        <span class="badge badge-secondary p-2 cursor-pointer" id="pick-all"
                              check="0">Chọn tất cả</span>
                        </div>
                        <div class="scream-item mb-3">
                            @foreach($pages as $page)
                                <div class="float-left item-element"
                                     title="{{ $page->name }}">
                                    <input id="p_{{ $page->user_id_fb_page_id }}" type="checkbox" class="w-100 pick"
                                           name="arr_page_id[]"
                                           value="{{ $page->id }}">
                                    <label for="p_{{ $page->user_id_fb_page_id }}" class="d-block">
                                        <img src="{{ $page->picture }}" class="d-block m-auto">
                                        <p>{{ Str::limit($page->name, 10) }}</p>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div id="render-fo">
                            <div class="col-xl-4 col-lg-6 col-md-8 col-sm-10 col-12 m-auto" style="clear: both">
                                <div class="form-group">
                                    <label for="mail">Mail của user bạn muốn phân quyền</label>
                                    <input class="form-control" id="email" name="email"
                                           placeholder="Nhập mail của user..." required>
                                </div>
                                <div class="form-group text-center">
                                    <button class="btn btn-primary">Gửi người dùng</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    @else
                    <p class="text-center text-dark h3">Bạn chưa thêm page...</p>
                    <p class="h2">Hướng dẫn:</p>
                    <p><b>B1:</b> Cập nhật access token cá nhân</p>
                    <p><b>B2:</b> Thêm hoặc cập nhật page</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/tags-input.js') }}"></script>
    <script>
        $('input[name="email"]').amsifySuggestags({
            tagLimit: 5,
            isEmail: true
        })

        function check(_this) {
            let check = parseInt(_this.attr('check'))
            if (check === 0) {
                $('.pick').prop('checked', true)
                $('#pick-all').attr('check', 1)
                $('#render-fo').append(render())
            } else {
                $('.pick').prop('checked', false)
                $('#pick-all').attr('check', 0)
                $('#render-fo').empty()
            }
        }

        $(document).ready(function () {
            $('.pick').prop('checked', false)
            $('#pick-all').attr('check', 0)
        })

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
    </script>
@endsection
