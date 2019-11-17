@extends('layouts.app')
@section('css')

@endsection
@section('content')
    @include('pages.me.header.index')
    <div class="container">
        <div class="row">
            @foreach($user_and_page as $value)
                <form method="POST" class="notify-accept">
                    @csrf
                    <input name="id" value="{{ $value->id }}" hidden>
                    <div class="col s12 l2 m4">
                        <div class="card">
                            <div class="card-image">
                                <div>
                                    <img src="{{ $value->page->picture }}">
                                </div>
                                <div>
                                    <button title="Từ chối" value="2" name="type" class="btn-floating waves-effect waves-light red btn-small"><i
                                            class="material-icons">close</i></button>
                                    <button title="Chấp nhận" value="1" name="type" class="btn-floating waves-effect waves-light green btn-small"><i
                                            class="material-icons">add</i></button>
                                </div>
                            </div>
                            <div class="card-content">
                                <p class="text_" title="{{ $value->page->name }}">{{ $value->page->name }}</p>
                                <p class="text_" title="{{ $value->page->fb_page_id }}">{{ $value->page->fb_page_id }}</p>
                            </div>
                        </div>
                    </div>
{{--                    <div class="toast mb-2 m-auto" data-autohide="false">--}}
{{--                        <div class="toast-header">--}}
{{--                                    <span class="mr-auto">Page ID: <strong--}}
{{--                                            class="text-primary">{{ $value->page->fb_page_id }}</strong></span>--}}
{{--                            --}}{{--                                    <small class="text-muted">5 mins ago</small>--}}
{{--                            <button type="button" class="ml-2 mb-1 close">&times;</button>--}}
{{--                        </div>--}}
{{--                        <div class="toast-body">--}}
{{--                            <code class="d-block">{{ $value->userParent->email }}</code>--}}
{{--                            <span class="d-block">{{ $value->page->name }}</span>--}}
{{--                            <button class="badge badge-pill badge-success" value="1" name="type">Chấp nhận</button>--}}
{{--                            <button class="badge badge-pill badge-danger" value="2" name="type">Từ chối</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </form>
            @endforeach
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/me_.js') }}"></script>
@endsection
