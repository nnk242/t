@extends('layouts.app')

@section('content')
    <div class="section no-pad-bot">
        <div class="row">
            <div class="col s12">
                <button class="waves-effect waves-light btn modal-trigger" data-target="page-modal">Thêm/cập nhật
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                @if($data->count())
                    @component('components.table.index', ['headers' => $headers])
                        @slot('body')
                            @foreach($data as $key=>$value)
                                <tr>
                                    {{--                                <td><input type="checkbox" name="name"></td>--}}
                                    <td>{{ $key +  1 }}</td>
                                    <td>{{$value->page->fb_page_id}}</td>
                                    <td>{{$value->page->name}} {!! $value->user_id === \Illuminate\Support\Facades\Auth::id() ? '<span class="new badge blue" data-badge-caption="Cá nhân"></span>' : '<span class="new badge" data-badge-caption="' . $value->user->email . '"></span>' !!}</td>
                                    <td><img src="{{$value->page->picture}}"></td>
                                    <td>{{$value->page->category}}</td>
                                    <td>{{$value->updated_at}}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td><span title="Go to page"><a target="_blank"
                                                                    href="{{ 'https://fb.com/' . $value->fb_page_id }}"><img
                                                    width="15"
                                                    src="{{ asset('icons/actions/globe.svg') }}"></a></span>
                                        @if($value->user_id === \Illuminate\Support\Facades\Auth::id())
                                            <span title="Update page"><a
                                                    href="{{ route('page.show', ['page' => $value->id]) }}"><img
                                                        width="15"
                                                        src="{{ asset('icons/actions/circle-notch.svg') }}"></a></span>
                                        @endif
                                        <span title="Delete page"><a data-id="{{ $value->id }}"
                                                                     data-page-id="{{ $value->page->fb_page_id }}"
                                                                     class="delete-item modal-trigger"
                                                                     href="#delete-modal"><img width="15"
                                                                                               src="{{ asset('icons/actions/trash-alt.svg') }}"></a></span>
                                    </td>
                                </tr>
                            @endforeach
                        @endslot
                        @slot('paginate')
                            {{ $data->appends(request()->input())->links() }}
                        @endslot
                    @endcomponent
                @else
                    <p class="text-center text-dark h3">Bạn chưa có page nào...</p>
                @endif
            </div>
        </div>
        @component('components.modal.index', ['modal_id' => 'page-modal', 'modal_title' => 'Thêm/cập nhật', 'modal_form_action' => route('page.store')])
            @slot('modal_content')
                <div class="input-field col s12">
                    <select name="type">
                        <option value="0">Tất cả</option>
                        <option value="1" selected>Những page mới</option>
                        <option value="2">Những page đã có</option>
                        <option value="3">Chọn page</option>
                    </select>
                    <label>Chọn loại cập nhật page</label>
                </div>
            @endslot
            @slot('modal_button')
                <button class="waves-effect waves-green btn">Gửi</button>
            @endslot
        @endcomponent

        @component('components.modal.index', ['modal_id' => 'delete-modal', 'modal_title' => 'Xoá page', 'modal_form_action' => '', 'is_delete' => true])
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
    </div>
@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('.modal').modal()
        })

        $(document).on('click', '.delete-item', function () {
            let id = $(this).attr('data-id')
            let page_id = $(this).attr('data-page-id')
            $('#modal-body-notify').empty()
            $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + page_id + '</span>?')
            $('#delete-modal').find('form').attr('action', window.location.pathname + '/' + id)
        })
    </script>
@endsection
