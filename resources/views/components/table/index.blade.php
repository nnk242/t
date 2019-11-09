<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
        <tr>
            @foreach($headers as $header)
                @if(gettype($header) === 'string')
                    <th>{{$header}}</th>
                @elseif(gettype($header) === 'array')
                    <th {{isset($header['id']) ? 'id=' . $header['id'] . '' : ''}} {{isset($header['class']) ? 'class="' . $header['class'] . '"' : ''}}>{{$header['label']}}</th>
                @endif
            @endforeach
        </tr>
        </thead>
        <tbody>
        {{ $body }}
        </tbody>
    </table>
</div>
<div class="mt-2 mb-2">
    {{ $data->appends(request()->input())->links() }}
</div>
