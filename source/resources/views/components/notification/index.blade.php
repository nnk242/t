@if (\Session::has('error') || \Session::has('success') || \Session::has('warning'))
    <div class="toast w-100 position-absolute" style="z-index: 1; right: 0" data-autohide="false">
        <div class="toast-header">
            <strong
                class="mr-auto {{ \Session::has('success') ? 'text-success' : (\Session::has('warning') ? 'text-warning' : 'text-danger')}}">{{__('Thông báo')}}</strong>
            <small class="text-muted">Hiện tại</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
        </div>
        <div class="toast-body">
            {!!\Session::get('success') . \Session::get('warning') . \Session::get('error') !!}
        </div>
    </div>
@endif
