@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12">
            <ul class="tabs z-depth-1">
                <li class="tab col"><a href="#call-bot-message" data-toggle="tab">Call bot message</a></li>
                <li class="tab col"><a href="#text-message" data-toggle="tab">Text messages</a></li>
                <li class="tab col"><a href="#assets-attachments" data-toggle="tab">Assets & Attachments</a></li>
                <li class="tab col"><a href="#message-templates" data-toggle="tab">Message Templates</a></li>
                <li class="tab col"><a href="#quick-replies" data-toggle="tab">Quick Replies</a></li>
            </ul>
        </div>
        <div id="call-bot-message" class="col s12">
            @include('pages.setting.message.tab.call-bot-message')
        </div>
        <div id="text-message" class="col s12">
            @include('pages.setting.message.tab.text-message')
        </div>
        <div id="assets-attachments" class="col s12">
            @include('pages.setting.message.tab.asset-attachment')
        </div>
        <div id="message-templates" class="col s12">
            @include('pages.setting.message.tab.message-template')
        </div>
        <div id="quick-replies" class="col s12">
            @include('pages.setting.message.tab.quick-reply')
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('js/setting-message.js') }}"></script>
@endsection
