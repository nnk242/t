@extends('layouts.app')
@section('css')

@endsection
@section('content')
    @include('pages.me.header.index')
    <div class="container">
        <div class="row">
            @if($user_and_page->count())
                <div class="input-field">
                    <form method="POST" class="left">
                        @csrf
                        <button class="btn" value="accept-all" name="type">Xác nhận tất cả</button>
                    </form>
                    <form method="POST">
                        @csrf
                        <button class="btn red" value="deny-all" name="type">Từ chối tất cả</button>
                    </form>
                </div>
            @endif
            @foreach($user_and_page as $value)
                <form method="POST" class="notify-accept">
                    @csrf
                    <input name="_id" value="{{ $value->_id }}" hidden>
                    <div class="col s12 l2 m4">
                        <div class="card">
                            <div class="card-image">
                                <div>
                                    <img src="{{ $value->page->picture }}">
                                </div>
                                <div>
                                    <button title="Từ chối" value="2" name="type"
                                            class="btn-floating waves-effect waves-light red btn-small"><i
                                            class="material-icons">close</i></button>
                                    <button title="Chấp nhận" value="1" name="type"
                                            class="btn-floating waves-effect waves-light green btn-small"><i
                                            class="material-icons">add</i></button>
                                </div>
                            </div>
                            <div class="card-content">
                                <p class="text_" title="{{ $value->page->name }}">{{ $value->page->name }}</p>
                                <p class="text_"
                                   title="{{ $value->page->fb_page_id }}">{{ $value->page->fb_page_id }}</p>
                            </div>
                        </div>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/me_.js') }}"></script>
@endsection
