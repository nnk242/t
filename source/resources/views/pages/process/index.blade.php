@extends('layouts.app')

@section('content')
    run console
@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.dev.js"></script>
    <script>
        $(function () {
            var url = 'http://127.0.0.1:3000/'
            var socket = io.connect(url, {secure: true})
            socket.on('data', function (msg) {
                console.log(msg)
            })
        })
    </script>
@endsection
