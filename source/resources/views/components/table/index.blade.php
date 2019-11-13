<table class="responsive-table">
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
    @isset($body)
        {{ $body }}
    @endisset
    </tbody>
</table>
@isset($paginate)
    {{ $paginate }}
@endisset
