@extends('layouts.app')

@section('content')
    <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-md-12 py-5">
                <div class="mb-5">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#page-modal">Thêm/cập nhật</button>
                </div>
                @component('components.table.index', ['headers' => $headers])
                    @slot('body')
                        @foreach($data as $key=>$value)
                            <tr>
                                {{--                                <td><input type="checkbox" name="name"></td>--}}
                                <td>{{ $key +  1 }}</td>
                                <td>{{$value->fb_page_id}}</td>
                                <td>{{$value->name}}</td>
                                <td><img src="{{$value->picture}}"></td>
                                <td>{{$value->category}}</td>
                                <td>{{$value->updated_at}}</td>
                                <td>{{$value->created_at}}</td>
                                <td><span title="Go to page"><a target="_blank"
                                                                href="{{ 'https://fb.com/' . $value->fb_page_id }}"><img
                                                width="15"
                                                src="{{ asset('icons/actions/globe.svg') }}"></a></span>
                                    <span title="Update page"><a
                                            href="{{ route('page.show', ['page' => $value->id]) }}"><img
                                                width="15"
                                                src="{{ asset('icons/actions/circle-notch.svg') }}"></a></span>
                                    <span title="Delete page"><a data-toggle="modal" data-target="#delete-modal"
                                                                 data-id="{{ $value->id }}"
                                                                 data-page-id="{{ $value->fb_page_id }}"
                                                                 class="delete-item" href="#"><img
                                                width="15"
                                                src="{{ asset('icons/actions/trash-alt.svg') }}"></a></span>
                                </td>
                            </tr>
                        @endforeach
                    @endslot
                        @slot('paginate')
                            {{ $data->appends(request()->input())->links() }}
                        @endslot
                @endcomponent
            </div>
        </div>
    </div>
    @component('components.modal.index', ['modal_id' => 'page-modal', 'modal_size' => 'modal-lg', 'modal_title' => 'Thêm/cập nhật', 'modal_form_action' => route('page.store')])
        @slot('model_content')
            <div class="modal-body">
                <div class="form-group">
                    <select class="form-control" name="type">
                        <option value="0">Tất cả</option>
                        <option value="1" selected>Những page mới</option>
                        <option value="2">Những page đã có</option>
                        <option value="3">Chọn page</option>
                    </select>
                </div>
            </div>
        @endslot
        @slot('modal_button')
            <button class="btn btn-success">Gửi</button>
        @endslot
    @endcomponent

    @component('components.modal.index', ['modal_id' => 'delete-modal', 'modal_size' => '', 'modal_title' => 'Xoá page', 'modal_form_action' => '', 'is_delete' => true])
        @slot('model_content')
            <div class="modal-body">
                <div id="modal-body-notify">
                    Bạn chắc chắn muốn xóa?
                </div>
            </div>
        @endslot
        @slot('modal_button')
            <button class="btn btn-success">Có</button>
        @endslot
    @endcomponent
@endsection
@section('js')
    <script>
        $(document).on('click', '.delete-item', function () {
            let id = $(this).attr('data-id')
            let page_id = $(this).attr('data-page-id')
            $('#modal-body-notify').empty()
            $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <code>' + page_id + '</code>?')
            $('#delete-modal').find('form').attr('action', window.location.pathname + '/' + id)
        })
    </script>
@endsection
