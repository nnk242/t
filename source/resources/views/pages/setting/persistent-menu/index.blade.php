@extends('layouts.app')

@section('content')
    <div class="section no-pad-bot">
        <div class="container">
            <div class="row card-panel">
                <form method="POST" action="{{ route('setting.persistent-menu.update', ['persistent_menu' => 'send-persistent-menu']) }}">
                    @csrf
                    {{ method_field('PUT') }}
                    <h3>Persistent menu</h3>
                    <div class="right-align input-field">
                        <button class="btn">Gửi persistent menu</button>
                    </div>
                </form>
                <form method="POST">
                    <input hidden name="level_menu" value="1">
                    @csrf
                    @foreach($persistent_menus as $key => $persistent_menu)
                        <div class="col s4">
                            <div>
                                <i class="material-icons">filter_{{ $key + 1 }}</i>
                                Item {{ $key + 1 }}
                                <a href="#call-modal" class="call-modal modal-trigger"
                                   data-id="{{ $persistent_menu->_id }}"
                                   data-text="{{ $persistent_menu->title }}"><span class="new badge red"
                                                                                   data-badge-caption="Xóa item"></span></a>
                            </div>
                            <div class="element">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" class="validate title" name="title[]" maxlength="20"
                                               autocomplete="off" data-length="20"
                                               value="{{ $persistent_menu->title }}">
                                        <label>Nhập title</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select class="type" name="type[]">
                                            <option value="block"
                                                {{ $persistent_menu->type === 'block' ? 'selected' : '' }}>Block
                                            </option>
                                            <option value="url"
                                                {{ $persistent_menu->type === 'url' ? 'selected' : '' }}>URL
                                            </option>
                                            <option value="submenu"
                                                {{ $persistent_menu->type === 'submenu' ? 'selected' : '' }}>Submenu
                                            </option>
                                        </select>
                                        <label>Loại thẻ</label>
                                    </div>
                                </div>
                                <div class="row url_ {{ $persistent_menu->type !== 'url' ? 'display-none' : '' }}">
                                    <div class="input-field col s12">
                                        <input type="url" name="url[]" class="validate"
                                               value="{{ $persistent_menu->url }}">
                                        <label>Nhập URL</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="number" name="priority[]" class="validate"
                                               value="{{ $persistent_menu->priority }}">
                                        <label>Nhập vị trí</label>
                                    </div>
                                </div>
                                <div class="row {{ $persistent_menu->type !== 'submenu' ? 'display-none' : '' }}">
                                    <div class="input-field col s12 right-align">
                                        <a href="{{ route('setting.persistent-menu.show', ['persistent_menu' => $persistent_menu->_id ])}}">Submenu
                                            <span class="material-icons">arrow_forward</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @for($i = $persistent_menus->count(); $i < 3; $i++)
                        <div class="col s4">
                            <div>
                                <i class="material-icons">filter_{{ $i + 1 }}</i>
                                Item {{ $i + 1 }}
                            </div>
                            <div class="element">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" class="validate title" name="title[]" maxlength="20"
                                               autocomplete="off" data-length="20">
                                        <label>Nhập title</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select class="type" name="type[]">
                                            <option value="block" selected>Block</option>
                                            <option value="url">URL</option>
                                            <option value="submenu">Submenu</option>
                                        </select>
                                        <label>Loại thẻ</label>
                                    </div>
                                </div>
                                <div class="row display-none url_">
                                    <div class="input-field col s12">
                                        <input type="url" name="url[]" class="validate">
                                        <label>Nhập URL</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="number" name="priority[]" class="validate">
                                        <label>Nhập vị trí</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor

                    <div class="row">
                        <div class="input-field col s12 center">
                            <button class="btn">Lưu</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @component('components.modal.index', ['modal_id' => 'call-modal', 'modal_title' => 'Xoá persistent', 'modal_form_action' => '', 'is_delete' => true])
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

            $('.call-modal').on('click', function () {
                let id = $(this).attr('data-id')
                let text = $(this).attr('data-text')
                $('#modal-body-notify').empty()
                $('#modal-body-notify').append('Bạn chắc chắn muốn xóa <span class="red-text">' + text + '</span>?')
                $('#call-modal').find('form').attr('action', '/setting/persistent-menu/' + id)
            })

            $('input .title').characterCounter()

            $('.type').on('change', function () {
                switch ($(this).val()) {
                    case 'url':
                        $(this).closest('.element').find('.url_').removeClass('display-none')
                        break
                    default:
                        $(this).closest('.element').find('.url_').addClass('display-none')
                        break
                }
            })
        })
    </script>
@endsection
