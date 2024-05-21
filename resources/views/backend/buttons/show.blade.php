<a href="{{ $show_route }}"
    class="btn btn-accent btn-sm"
    data-action="show"
    @if(!isset($no_tooltip))
    data-toggle="tooltip"
    data-original-title="Show Item"
    @endif
>
    <i class="material-icons">remove_red_eye</i>
</a>