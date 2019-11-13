<div class="row mb-5">
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.index')}}">
            <button class="btn {{ \App\Components\ActiveComponent::isActiveSide(['/me', '/me/index'])? 'btn-outline-primary' : 'btn-primary' }}">{{__('Tổng quan')}}</button>
        </a>
    </div>
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.share.index')}}">
            <button class="btn {{ \App\Components\ActiveComponent::isActiveSide(['/me/share', '/me/share/index'])? 'btn-outline-primary' : 'btn-primary' }}">{{__('Chia sẻ page')}}</button>
        </a>
    </div>
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.manager-share.index')}}">
            <button class="btn {{ \App\Components\ActiveComponent::isActiveSide(['/me/manager-share', '/me/manager-share/index'])? 'btn-outline-primary' : 'btn-primary' }}">{{__('Quản lý chia sẻ')}}</button>
        </a>
    </div>
    <div class="col-xl-3 text-center mb-2">
        <a href="{{route('me.access-token')}}">
            <button class="btn btn-primary">{{__('Cập nhật access token cá nhân')}}</button>
        </a>
    </div>
</div>
