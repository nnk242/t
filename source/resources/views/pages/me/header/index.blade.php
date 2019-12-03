<div class="col s12">
    <ul class="tabs z-depth-1">
        <li class="tab col"><a href="{{route('me.index')}}" >{{__('Tổng quan')}}</a></li>
        <li class="tab col"><a href="{{route('me.share.index')}}">{{__('Chia sẻ page')}}</a></li>
        <li class="tab col"><a href="{{route('me.manager-share.index')}}">{{__('Quản lý chia sẻ')}}</a></li>
        <li class="tab col"><a href="{{route('me.page-use.index')}}" data-toggle="tab">{{__('Page sử dụng')}}</a></li>
        <li class="tab col"><a href="{{route('me.access-token')}}" data-toggle="tab">{{__('Cập nhật access token cá nhân')}}</a></li>
    </ul>
</div>
