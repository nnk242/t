<div class="row mb-5">
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.index')}}">
            <button class="btn btn-primary">{{__('Tổng quan')}}</button>
        </a>
    </div>
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.share')}}">
            <button class="btn btn-primary">{{__('Chia sẻ page')}}</button>
        </a>
    </div>
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.managerShare')}}">
            <button class="btn btn-primary">{{__('Quản lý chia sẻ')}}</button>
        </a>
    </div>
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.accessToken')}}">
            <button class="btn btn-primary">{{__('Cập nhật access token cá nhân')}}</button>
        </a>
    </div>
</div>
