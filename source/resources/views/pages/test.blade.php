@extends('layouts.app')

@section('content')
    <div class="fixed-action-btn direction-top active" style="bottom: 45px; right: 24px;">
        <a id="menu" class="waves-effect waves-light btn btn-floating cyan btn-large"
           onclick="$('.tap-target').tapTarget('open')"><i
                class="material-icons">menu</i></a>
    </div>


    <!-- Tap Target Structure -->
    <div class="tap-target cyan" data-target="menu">
        <form class="tap-target-content">
            <div class="chips chips-initial input-field">
                <div class="chip">
                    <img class="btn-floating"
                         src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqmSbGHts_tWHfPkXH7tWQKXof26UQO4bhhm6uKYroxm4PJjNs9g&s">
                    aallllla
                </div>
                <input class="input" placeholder="Tìm kiếm...">
            </div>
            <div style="max-height: 85px; min-height: 85px; margin-bottom: 10px">
                <div class="chip">
                    <img class="btn-floating"
                         src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRqmSbGHts_tWHfPkXH7tWQKXof26UQO4bhhm6uKYroxm4PJjNs9g&s">
                    <label>
                        <input type="checkbox"/>
                        <span>Red</span>
                    </label>
                </div>
            </div>
            <div class="no-pad-bot">
                <button class="btn red waves-effect waves-green">Gửi</button>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('.tap-target').tapTarget()
        })
    </script>
@endsection
