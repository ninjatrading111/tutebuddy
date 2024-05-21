@if ($message = Session::get('success'))
<script>
    swal("Success!", "{{ $message }}", "success");
</script>
@endif

@if ($message = Session::get('error'))
<script>
    swal("Error!", "{{ $message }}", "error");
</script>
@endif

@if ($message = Session::get('warning'))
<script>
    swal("Warning!", "{{ $message }}", "warning");
</script>
@endif

@if ($message = Session::get('info'))
<script>
    swal("Info!", "{{ $message }}", "info");
</script>
@endif

@if ($message = Session::get('error'))
<script>
    swal("Error!", "{{ $message }}", "error");
</script>
@endif
