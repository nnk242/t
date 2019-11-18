@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s3"><a href="#test1">Text messages</a></li>
                <li class="tab col s3"><a href="#test2">Assets & Attachments</a></li>
                <li class="tab col s3"><a href="#test3">Message Templates</a></li>
                <li class="tab col s3"><a href="#test4">Quick Replies</a></li>
            </ul>
        </div>
        <div id="test1" class="col s12">
            <form class="container">
                @csrf
                <div class="card-panel">
                    <div class="row">
                        <div class="col s12 center-align">
                            <div class="input-field">
                                <input placeholder="Placeholder" id="first_name" type="text" class="validate">
                            </div>
                            <button class="btn">Gửi</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div id="test2" class="col s12">Test 2</div>
        <div id="test3" class="col s12">Test 3</div>
        <div id="test4" class="col s12">Test 4</div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.tabs').tabs()
        })
    </script>
@endsection
