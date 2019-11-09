@extends('layouts.app')
@section('css')

@endsection
@section('content')
    <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-md-12 py-5">
                <div class="row mb-5">
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.index')}}">
                            <button class="btn btn-primary">{{__('Tổng quan')}}</button>
                        </a>
                    </div>
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.accessToken')}}">
                            <button class="btn btn-primary">{{__('Chia sẻ page')}}</button>
                        </a>
                    </div>
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.managerShare')}}">
                            <button class="btn btn-primary">{{__('Quản lý chia sẻ')}}</button>
                        </a>
                    </div>
                    <div class="col-xl-3 text-center mb-2">
                        <a href="{{route('me.accessToken')}}">
                            <button class="btn btn-primary">{{__('Cập nhật access token cá nhân')}}</button>
                        </a>
                    </div>
                </div>
                @if($data->count())
                    @component('components.table.index', ['headers' => $headers])
                        @slot('body')
                            @foreach($data as $key=>$value)
                                <tr>
                                    <td>{{ $key +  1 }}</td>
                                    <td>{{$value->page->fb_page_id}}</td>
                                    <td>{{$value->page->name}}</td>
                                    <td><img src="{{$value->page->picture}}"></td>
                                    <td><code>{{$value->user->email}}</code></td>
                                    <td>{!! $value->type === 0 ? '<span class="badge badge-pill badge-warning">Đã gửi</span>' :
                                     ($value->type === 1 ? '<span class="badge badge-pill badge-success">Chấp nhận</span>' :
                                      '<span class="badge badge-pill badge-danger">Từ chối</span>') !!}</td>
                                    <td>
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" class="custom-control-input status"
                                                   id="switch-{{ $key }}" {{$value->status ? 'checked' : ''}}>
                                            <label class="custom-control-label cursor-pointer label-switch"
                                                   for="switch-{{ $key }}" data-id="{{ $value->id }}"></label>
                                        </div>
                                    </td>
                                    <td>{!! $value->updated_at->timestamp === $value->created_at->timestamp ?
                                     '<span class="badge badge-pill badge-warning">Chưa chấp nhận</span>' : $value->updated_at !!}</td>
                                    <td>{{$value->created_at}}</td>
                                    <td><span title="Delete page"><a data-toggle="modal" data-target="#delete-modal"
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
                @else
                    <p class="text-center text-dark h3">Bạn chưa chia sẻ page nào...</p>
                    <p class="h2">Hướng dẫn:</p>
                    <p><b>B1:</b> Chia sẻ page</p>
                    <p><b>B2:</b> Thêm page cho người muốn sử dụng</p>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/tags-input.js') }}"></script>
    <script src="{{ asset('js/me_.js') }}"></script>
    <script>
        $(document).on('click', '.label-switch', function () {
            let status = $(this).closest('.custom-switch').find('.status')
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
    </script>
@endsection
