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
                    <h2 class="mb-0">@lang('labels.backend.assignments.edit.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.assignments.index') }}">@lang('labels.backend.assignments.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.assignments.edit.title')
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

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.assignments.update', $assignment->id], 'files' => true, 'id' => 'frm_assignments']) !!}

            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">@lang('labels.backend.assignments.edit.assignment_title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title" class="form-control form-control-lg"
                            placeholder="title" value="{{ $assignment->title }}" tute-no-empty>
                    </div>

                    <label class="form-label">@lang('labels.backend.assignments.edit.content')</label>
                    <div class="form-group mb-24pt">
                        <!-- quill editor -->
                        <div id="assignment_editor" class="mb-0" style="min-height: 400px;">{!! $assignment->content !!}</div>
                    </div>

                    @if(!empty($assignment->attachment))
                    <div class="form-group mb-24pt">
                        <label class="form-label">@lang('labels.backend.assignments.edit.attached_document'):</label>
                        <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                            <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                @php $ext = pathinfo($assignment->attachment, PATHINFO_EXTENSION); @endphp
                                @if($ext == 'pdf')
                                <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                @else
                                <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                                @endif
                            </div>
                            <div class="flex">
                                <a href="{{ asset('/storage/attachments/' . $assignment->attachment) }}">
                                    <div class="form-label mb-4pt">{{ $assignment->attachment }}</div>
                                    <p class="card-subtitle text-black-70">
                                        @lang('labels.backend.assignments.edit.attached_document_note')</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <label class="form-label">@lang('labels.backend.assignments.edit.document'):</label>
                    <div class="form-group">
                        <div class="custom-file">
                            <input id="q_file" type="file" name="attachment" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                            <label for="q_file" class="custom-file-label">@lang('labels.backend.assignments.edit.choose_file')</label>
                        </div>
                        <small class="form-text text-muted">@lang('labels.backend.assignments.edit.pdf_note')</small>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" id="btn_draft" class="btn btn-accent">@lang('labels.backend.buttons.save_draft')</button>
                            <button type="submit" id="btn_publish" class="btn btn-primary">@lang('labels.backend.buttons.publish')</button>
                            <a href="{{ route('student.assignment.show', [$assignment->lesson->slug, $assignment->id]) }}" 
                                class="btn btn-info">@lang('labels.backend.buttons.preview')</a>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            @if($assignment->published == 0)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.save_draft')</strong></a>
                                <i class="material-icons text-muted draft">check</i>
                            </div>
                            @endif

                            @if($assignment->published == 1)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.publish')</strong></a>
                                <i class="material-icons text-muted publish">check</i>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.assignments.edit.information')</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.general.course')</label>
                                <div class="form-group">
                                    <select name="course_id" class="form-control" tute-no-empty>
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}" @if($course->id == $assignment->lesson->course->id) selected @endif>
                                            {{ $course->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.assignments.select_course')</small>
                            </div>

                            <!-- Set Lesson -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.general.lessons')</label>
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
                                <input type="number" name="total_mark" class="form-control" placeholder="5" min="1" value="{{ $assignment->total_mark }}">
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

    var lesson_id = '{{ $assignment->lesson_id }}';

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

    // When add title, Hide Error msg
    $('#frm_assignments').on('keyup', 'input[name="title"], input[name="lesson"]', function() {
        $(this).removeClass('is-invalid');
        $(this).closest('.form-group').find('div.invalid-feedback').remove();
    });

    $('#btn_draft').on('click', function(e) {
        e.preventDefault();

        btnLoading($(this), true);

        $('#frm_assignments').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = assignment_editor.root.innerHTML;

                // Append Course ID
                formData.push({
                    name: 'content',
                    type: 'text',
                    value: content
                });

                formData.push({
                    name: 'published',
                    type: 'integer',
                    value: 0
                });
            },
            success: function(res) {
                if(res.success) {
                    swal("@lang('labels.backend.swal.success.title')", "@lang('labels.backend.swal.success.description')", 'success');
                }
                btnLoading($('#btn_draft'), false);
            },
            error: function(err) {
                btnLoading($('#btn_draft'), false);
                console.log(err);
            }
        });
    });

    $('#btn_publish').on('click', function(e) {
        e.preventDefault();

        btnLoading($(this), true);

        $('#frm_assignments').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = assignment_editor.root.innerHTML;

                // Append Course ID
                formData.push({
                    name: 'content',
                    type: 'text',
                    value: content
                });

                formData.push({
                    name: 'published',
                    type: 'integer',
                    value: 1
                });
            },
            success: function(res) {
                if(res.success) {
                    swal("@lang('labels.backend.swal.success.title')", "@lang('labels.backend.swal.successfully_published')", 'success');
                }
                btnLoading($('#btn_publish'), false);
            },
            error: function(err) {
                console.log(err);
                btnLoading($('#btn_publish'), false);
            }
        });
    });

    function loadLessons(course) {

        // Get Lessons by selected Course
        $.ajax({
            method: 'GET',
            url: "{{ route('admin.lessons.getLessonsByCourse') }}",
            data: {
                course_id: course,
                lesson_id: lesson_id
            },
            success: function(res) {
                if (res.success) {
                    lesson_added = (res.lesson_id != null) ? true : false;
                    $('select[name="lesson_id"]').html(res.options);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    }
});
</script>

@endpush

@endsection