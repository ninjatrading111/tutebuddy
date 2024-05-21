<button type="button"
    class="btn btn-danger btn-sm"
    data-action="delete"
    @if(!isset($no_tooltip))
    data-toggle="tooltip"
    data-title="delete"
    @endif
    onclick="doSubmit_action_delete(this)"
>

    <i class="material-icons">delete</i>
    <form action="{{$delete_route}}"
          method="POST" name="delete_item" style="display:none">
        @csrf
        {{method_field('DELETE')}}
    </form>
</button>

<script>
    function doSubmit_action_delete(ele) {
        swal({
            title: "Are you sure?",
            text: "This item will removed from this list",
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            dangerMode: false,
        }, function (val) {
            if(val) {
                $(ele).find('form').submit();
            }
        });
    }
</script>