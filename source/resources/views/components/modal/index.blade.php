<!-- Modal Structure -->
<div id="{{ $modal_id }}" class="modal">
    <form method="POST" action="{{ $modal_form_action }}">
        @csrf
        @isset($is_delete)
            @if($is_delete)
                {{ method_field('DELETE') }}
            @endif
        @endif
        <div class="modal-content">
            <h5>{{ $modal_title }}</h5>
            {{ $modal_content }}
        </div>
        <div class="modal-footer">
            {{ $modal_button }}
            <a href="#!" class="modal-close waves-effect waves-red btn">Đóng</a>
        </div>
    </form>
</div>
