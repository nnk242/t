@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col s12 l8 offset-l2 m10 offset-m1">
                <div class="card">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field">
                                <div class="center">
                                    <a href="{{route('login.gmail.redirect')}}">
                                        <button class="btn red text_">Login with gmail</button>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
