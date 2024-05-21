@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.assignments.create.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.assignments.index') }}">@lang('labels.backend.assignments.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.assignments.new')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.assignments.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.assignments.store'], 'files' => true, 'id' => 'frm_assignments']) !!}

            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">@lang('labels.backend.assignments.create.assignment_title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg"
                            placeholder="title" value="" tute-no-empty>
                    </div>

                    <label class="form-label">@lang('labels.backend.assignments.create.content'):</label>
                    <div class="form-group mb-24pt">
                        <!-- quill editor -->
                        <div id="assignment_editor" class="mb-0" style="min-height: 400px;"></div>
                    </div>

                    <label class="form-label">@lang('labels.backend.assignments.create.document'):</label>
                    <div class="form-group">
                        <div class="custom-file">
                            <input id="attach_file" type="file" name="attachment" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                            <label for="attach_file" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                        </div>
                        <small class="form-text text-muted">@lang('labels.backend.general.pdf_note_5m')</small>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" id="btn_publish" class="btn btn-accent">@lang('labels.backend.general.save_changes')</button>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.publish')</strong></a>
                                <i class="material-icons text-muted publish">clear</i>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.general.information')</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.general.course')</label>
                                <div class="form-group mb-0">
                                    <select name="course_id" class="form-control custom-select @error('course') is-invalid @enderror" tute-no-empty>
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}"> {{ $course->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                    <div class="invalid-feedback">Course is required.</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.assignments.select_course')</small>
                            </div>

                            <!-- Set Lesson -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.general.lesson')</label>
                                <select name="lesson_id" class="form-control"></select>
                            </div>

                            <!-- Set Duration -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.general.due_date')</label>
                                <input type="hidden" name="due_date" class="form-control flatpickr-input" data-toggle="flatpickr" value="<?php echo date("Y-m-d"); ?>">
                            </div>

                            <!-- Total Mark -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.assignments.create.total_marks')</label>
                                <input type="number" name="total_mark" class="form-control" placeholder="5" value="5" min="1" tute-no-empty>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

$(function() {

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

    // Init Quill Editor for Assignment Content
    var assignment_editor = new Quill('#assignment_editor', {
        theme: 'snow',
        placeholder: "@lang('labels.backend.assignment.content')",
        modules: {
            toolbar: toolbarOptions
        },
    });

    $('select[name="course_id"]').select2({ tags: true });
    $('select[name="lesson_id"]').select2({ tags: true });

    loadLessons($('select[name="course_id"]').val());

    $('select[name="course_id"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    $('input[type="number"]').on('keypress', function(e) {
        if(e.which == 45) {
            return false;
        }
    });

    $('#frm_assignments').on('submit', function(e) {
        e.preventDefault();

        btnLoading($('#btn_publish'), true);

        if(!checkValidForm($(this))){
            return false;
        }

        $('#frm_assignments').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = assignment_editor.root.innerHTML;

                // Append Course ID
                formData.push({
                    name: 'content',
                    type: 'text',
                    value: content
                });
            },
            success: function(res) {
                if(res.success) {
                    swal({
                        title: "@lang('labels.backend.general.successfully_stored')",
                        text: "@lang('labels.backend.assignment.successfully_stored')",
                        type: 'success',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: "@lang('labels.backend.general.confirm')",
                        cancelButtonText: "@lang('labels.backend.general.cancel')",
                        dangerMode: false,

                    }, function(val) {
                        if (val) {
                            var url = "{{ route('admin.assignments.index') }}";
                            window.location.href = url;
                        }
                    });

                    btnLoading($('#btn_publish'), false);
                }
            },
            error: function(err) {
                btnLoading($('#btn_publish'), false);
                console.log(err);
            }
        });
    });

    function loadLessons(course) {

        console.log(course);

        if(course != null) {
            // Get Lessons by selected Course
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.lessons.getLessonsByCourse') }}",
                data: {course_id: course},
                success: function(res) {
                    if (res.success) {
                        lesson_added = (res.lesson_id != null) ? true : false;
                        $('select[name="lesson_id"]').html(res.options);
                        if(res.options == '') {
                            swal({
                                title: "@lang('labels.backend.general.warning')",
                                text: "@lang('string.backend.load_lesson')",
                                type: 'warning',
                                showCancelButton: true,
                                showConfirmButton: true,
                                confirmButtonText: "@lang('labels.backend.general.confirm')",
                                cancelButtonText: "@lang('labels.backend.general.cancel')",
                                dangerMode: false,
                            }, function (val) {
                                if(val) {
                                    window.location.href = '/dashboard/courses/' + course + '/edit';
                                }
                            });
                        }
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        } else {
            swal({
                title: "@lang('labels.backend.general.warning')",
                text: "There is no any courses. Please create courses",
                type: 'warning',
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: "@lang('labels.backend.general.confirm')",
                cancelButtonText: "@lang('labels.backend.general.cancel')",
                dangerMode: false,
            }, function (val) {
                if(val) {
                    window.location.href = "{{ route('admin.courses.index') }}";
                }
            });
        }
    }
});
</script>

@endpush

@endsection