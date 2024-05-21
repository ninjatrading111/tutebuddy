<a href="{{ $detail_route }}"
    class="btn btn-primary btn-sm"
    {{-- data-action="show" --}}
    @if(!isset($no_tooltip))
    data-toggle="tooltip"
    data-original-title="Show Item"
    @endif
>
    <span>detail</span>
</a>