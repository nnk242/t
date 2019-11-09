<div class="modal" id="{{ $modal_id }}">
    <div class="modal-dialog {{ $modal_size }}">
        <form class="modal-content" method="POST" action="{{ $modal_form_action }}">
            @csrf
            @isset($is_delete)
                @if($is_delete)
                    {{ method_field('DELETE') }}
                @endif
            @endif
            <div class="modal-header">
                <h4 class="modal-title">{{ $modal_title }}</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            {{ $model_content }}
            {{--            <div class="modal-body">--}}
            {{--                <div class="form-group">--}}
            {{--                    <select class="form-control" name="type">--}}
            {{--                        <option value="0">Tất cả</option>--}}
            {{--                        <option value="1" selected>Những page mới</option>--}}
            {{--                        <option value="2">Những page đã có</option>--}}
            {{--                        <option value="3">Chọn page</option>--}}
            {{--                    </select>--}}
            {{--                </div>--}}
            {{--            </div>--}}

            <div class="modal-footer">
                {{ $modal_button }}
                {{--                <button class="btn btn-success">Gửi</button>--}}
                <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
            </div>

        </form>
    </div>
</div>
