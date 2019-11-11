@extends('layouts.app')
@section('css')

@endsection
@section('content')
    <div class="row">
        <div class="col s12">
            @include('pages.me.header.index')
            @if($pages->count())
                <form method="POST" action="{{ route('me.share.store') }}">
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

                    <div class="row">
                        <div class="col s12">
                            <label for="mail">Email của user bạn muốn phân quyền</label>
                            <div class="chips chips-placeholder">
                                <input name="arr_email[]">
                            </div>
                            <button class="btn btn-primary">Gửi người dùng</button>
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
@endsection
@section('js')
    <script>
        function email() {

        }
        $(document).ready(function () {
            $('.chips').chips()

            $('.chips-placeholder').chips({
                placeholder: 'Nhập email',
                secondaryPlaceholder: '+Email',
                minLength: 1
            })
        })
    </script>
@endsection
