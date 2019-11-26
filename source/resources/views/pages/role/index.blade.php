@extends('layouts.app')

@section('content')
    <div id="text-message" class="col s12">
        <form class="container" method="POST">
            @csrf
            <div class="card-panel">
                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                            <h4>User</h4>
                        </div>
                        <div class="input-field col s12">
                            <label>Nhập nội dung người dùng gửi</label>
                            <input placeholder="Nhập email..." class="validate" type="email"
                                   name="email">
                        </div>
                        <div class="input-field col s12">
                            <select name="role" class="type_notify">
                                <option value="normal" disabled selected>Chọn loại</option>
                                <option value="normal">Normal</option>
                                <option value="admin">Admin</option>
                            </select>
                            <label>Kiểu nhận nội dung</label>
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
                                <td>{{$value->name}}</td>
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
    <script>
        $(document).ready(function () {
            $('.modal').modal()

            $('.delete-item').on('click', function () {
                let id = $(this).attr('data-id')
                let email = $(this).attr('data-email')
                $('#modal-body-notify').empty()
                $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + email + '</span>?')
                $('#delete-modal').find('form').attr('action', '/role' + '/' + id)
            })
        })
    </script>
@endsection
