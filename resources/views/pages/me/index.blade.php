@extends('layouts.app')
@section('css')

@endsection
@section('content')
    <div class="container-fluid bg-white">
        <div class="row justify-content-center">
            <div class="col-md-12 py-5">
                @include('pages.me.header.index')
                <div>
                    @foreach($user_and_page as $value)
                        <form method="POST" class="notify-accept">
                            @csrf
                            <input name="id" value="{{ $value->id }}" hidden>
                            <div class="toast mb-2 m-auto" data-autohide="false">
                                <div class="toast-header">
                                    <span class="mr-auto">Page ID: <strong
                                            class="text-primary">{{ $value->page->fb_page_id }}</strong></span>
{{--                                    <small class="text-muted">5 mins ago</small>--}}
                                    <button type="button" class="ml-2 mb-1 close">&times;</button>
                                </div>
                                <div class="toast-body">
                                    <code class="d-block">{{ $value->userParent->email }}</code>
                                    <span class="d-block">{{ $value->page->name }}</span>
                                    <button class="badge badge-pill badge-success" value="1" name="type">Chấp nhận</button>
                                    <button class="badge badge-pill badge-danger" value="2" name="type">Từ chối</button>
                                </div>
                            </div>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/tags-input.js') }}"></script>
    <script src="{{ asset('js/me_.js') }}"></script>
@endsection
