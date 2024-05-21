@extends('layouts.admin2.app')

@section('content')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Settings</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Terms</li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator mb-4">
            <div class="page-separator__text">Term Of Service</div>
        </div>

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">
                        <div class="card-body text-center pb-0">
                            <h2>Term Of Sevice</h1>
                        </div>

                    <div class="card-body tab-content">
                        <div id="general" class="tab-pane p-4 fade text-70 active show">
                            <div class="form-group mb-2">
                                <label class="form-label">Terms</label>
                                <!-- quill editor -->
                                <div style="min-height: 350px;" id="course_editor" class="mb-0">Edit Terms.</div>
                                <small class="form-text text-muted">Description</small>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="button" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>


<script>
    
$(document).ready(function() {
    var course_quill;

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

        // Init Quill Editor for Course description
        course_quill = new Quill('#course_editor', {
        theme: 'snow',
        placeholder: 'Course description',
        modules: {
            toolbar: toolbarOptions
        }
    });

    // Init code
    $('.show_secret').on('click', function(e) {
        var input_id = $(this).attr('data-id');
        if($(this).find('span.fa').hasClass('fa-eye')) {
            $('#' + input_id).attr('type', 'text');
            $(this).find('span.fa').removeClass('fa-eye');
            $(this).find('span.fa').addClass('fa-eye-slash');
        } else {
            $('#' + input_id).attr('type', 'password');
            $(this).find('span.fa').addClass('fa-eye');
            $(this).find('span.fa').removeClass('fa-eye-slash');
        }
    });
});

$('#frm_setting').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit({
        success: function(res) {

            if (res.success) {
                swal("Success!", "Successfully updated", "success");
            }
        }
    });
});

$('.custom-checkbox-toggle').on('click', 'input[type="checkbox"]', function() {

    var id = $(this).attr('id');

    if ($(this).prop('checked')) {
        $(this).val('1');
        $('div.wrap[for="' + id + '"').removeClass('d-none');
    } else {
        $(this).val('0');
        $('div.wrap[for="' + id + '"').addClass('d-none');
    }

});
</script>

@endpush

@endsection