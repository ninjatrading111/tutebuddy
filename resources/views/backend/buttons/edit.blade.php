<a href="{{ $edit_route }}"
    class="btn btn-primary btn-sm"
    data-action="edit"
    @if(!isset($no_tooltip))
        data-toggle="tooltip"
        data-original-title="Edit Item"
    @endif
>
    <i class="material-icons">edit</i>
</a>