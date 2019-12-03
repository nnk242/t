@extends('layouts.app')
@section('css')

@endsection
@section('content')
    @include('pages.me.header.index')
    <div class="container">
        <div class="row">
            <div class="col s12">
                @if($data->count())
                    @component('components.table.index', ['headers' => $headers])
                        @slot('body')
                            @foreach($data as $key=>$value)
                                <tr>
                                    <td>{{ $key +  1 }}</td>
                                    <td>{{$value->fb_page_id}}</td>
                                    <td>{{$value->page->name}}</td>
                                    <td><img src="{{$value->page->picture}}"></td>
                                    <td class="red-text">{{$value->userChild->email}}</td>
                                    <td>{!! $value->type === 0 ? '<span class="new badge" data-badge-caption="Đã gửi"></span>' :
                                     ($value->type === 1 ? '<span class="green badge" data-badge-caption="Chấp nhận"></span>' :
                                      ($value->type === 2 ?'<span class="yellow badge">Từ chối</span>' :
                                       '<span class="red badge">Đã xoá</span>')) !!}</td>
                                    <td>
                                        <div class="switch">
                                            <label class="label-switch" for="switch-{{ $key }}"
                                                   data-id="{{ $value->id }}">
                                                Đóng
                                                <input type="checkbox" class="status"
                                                       id="switch-{{ $key }}" {{$value->status ? 'checked' : ''}}>
                                                <span class="lever"></span>
                                                Mở
                                            </label>
                                        </div>
                                    </td>
                                    <td>{{$value->created_at}}</td>
                                    <td><span title="Delete page">
                                            <a data-id="{{ $value->id }}"
                                               data-page-id="{{ $value->fb_page_id }}"
                                               class="delete-item modal-trigger"
                                               href="#delete-modal">
                                                <img width="15"
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
                    <p class="text-center text-dark h3">Bạn chưa chia sẻ page nào...</p>
                    <p class="h2">Hướng dẫn:</p>
                    <p><b>B1:</b> Chia sẻ page</p>
                    <p><b>B2:</b> Thêm page cho người muốn sử dụng</p>
                @endif
            </div>
        </div>

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
    <script src="{{ asset('js/me_.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.modal').modal()
        })

        $(document).on('click', '.label-switch', function () {
            let status = $(this).find('.status')
            status.prop("disabled", true)
            let is_checked = status.prop("checked")
            let id = $(this).attr('data-id')
            let data = {
                is_checked,
                id,
                _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
            $.ajax({
                url: '/me/manager-share/',
                method: 'POST',
                data,
                success: function (response) {
                    status.prop("disabled", false)
                    if (parseInt(response) === 1) {
                        status.prop('checked', true)
                    } else if (parseInt(response) === 0) {
                        status.prop('checked', false)
                    }
                }, catch(error) {
                    status.prop("disabled", false)
                }
            })
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
