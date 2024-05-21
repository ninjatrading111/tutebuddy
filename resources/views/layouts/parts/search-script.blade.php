@push('after-scripts')

<script src="{{ asset('assets/js/semantic.min.js') }}"></script>

<script>

$(function() {

    /* === Search for Course === */
    $('.ui.search.course')
        .search({
            type: 'category',
            apiSettings: {
                url: '/ajax/search/courses?q={query}'
            },
            fields: {
                results : 'results'
            },
            minCharacters : 3
        });

    $('.ui.search.course').on('keypress', 'input', function(e) {
        if(e.which == 13) {
            location.href = '{{ config("app.url") }}' + 'search/courses?_q=' + $(this).val();
        }
    });

    /* === Search for Instructor === */
    $('.ui.search.instructor')
        .search({
            type: 'category',
            apiSettings: {
                url: '/ajax/search/instructor?q={query}'
            },
            fields: {
                results : 'results'
            },
            minCharacters : 3
        });

    $('.ui.search.instructor').on('keypress', 'input', function(e) {
        if(e.which == 13) {
            location.href = '{{ config("app.url") }}' + 'search/instructors?_q=' + $(this).val();
        }
    });

});
</script>

@endpush