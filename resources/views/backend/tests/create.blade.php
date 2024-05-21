@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<style>

#questions img {
    max-width: 100%;
}

</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Create Test</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.tests.index') }}">Test</a>
                        </li>

                        <li class="breadcrumb-item active">
                            New Test
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.tests.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.tests.store'], 'files' => true, 'id' => 'frm_test']) !!}

            <div class="row">
                <div class="col-md-8">

                    <label class="form-label">Test Title*</label>

                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="title" value="" tute-no-empty>
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">Description</label>
                    <div class="form-group mb-48pt">
                        <textarea class="mb-0 form-control" name="description" rows="4" placeholder="Question Description"></textarea>
                    </div>

                    <div class="tests-wrap" id="questions"></div>

                    <button type="button" id="btn_new_question" class="btn btn-block btn-outline-secondary">Add Quesion</button>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" class="btn btn-accent" id="btn_save_test">Save changes</button>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Publish</strong></a>
                                <i class="material-icons text-muted publish">clear</i>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Information</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- <div class="form-group">
                                <label class="form-label">Test Type</label>
                                <div class="custom-controls-stacked form-inline">
                                    <div class="custom-control custom-radio">
                                        <input id="test_lesson" name="type" type="radio" class="custom-control-input" value="lesson" checked="">
                                        <label for="test_lesson" class="custom-control-label">For Lesson</label>
                                    </div>
                                    <div class="custom-control custom-radio ml-8pt">
                                        <input id="test_course" name="type" type="radio" class="custom-control-input" value="course">
                                        <label for="test_course" class="custom-control-label">For Course</label>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">Course</label>
                                <div class="form-group mb-0">
                                    <select name="course_id" class="form-control custom-select @error('course') is-invalid @enderror">
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}"> {{ $course->title }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                    <div class="invalid-feedback">Course is required.</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">Select a course.</small>
                            </div>

                            <!-- Set Lesson -->
                            <div class="form-group" for="lesson">
                                <label class="form-label">Lessons</label>
                                <select name="lesson_id" class="form-control"></select>
                                <small class="form-text text-muted">Select a lesson.</small>
                            </div>

                            <!-- Duration -->
                            <div class="form-group">
                                <label class="form-label">Duration</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" name="duration_hours" class="form-control" min="1" placeholder="Hours" value="">
                                    </div>
                                    <div class="col">
                                        <input type="number" name="duration_mins" class="form-control" min="1" placeholder="Mins" value="" tute-no-empty>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Marks -->
                            <div class="form-group">
                                <label class="form-label">Total Marks</label>
                                <input type="number" name="score" class="form-control" placeholder="Total Marks" min="1" value="" tute-no-empty>
                            </div>

                            <!-- Test Type -->
                            <div class="form-group">
                                <label class="form-label">Test Type</label>
                                <div class="custom-controls-stacked">
                                    <div class="custom-control custom-radio py-2">
                                        <input id="t_type_1" name="type" type="radio" class="custom-control-input" value="0">
                                        <label for="t_type_1" class="custom-control-label">Take at any time</label>
                                    </div>
                                    <div class="custom-control custom-radio py-2">
                                        <input id="t_type_2" name="type" type="radio" class="custom-control-input" value="1">
                                        <label for="t_type_2" class="custom-control-label">Take at fixed time</label>
                                    </div>
                                </div>
                            </div>

                            <div for="t_type_1" style="display: none;">
                                <hr>
                                <!-- Test Data -->
                                <div class="form-group">
                                    <label class="form-label">Test Date</label>
                                    <input name="start_date" type="text" class="form-control" data-toggle="flatpickr" data-flatpickr-enable-time="true" 
                                    data-flatpickr-alt-format="F j, Y at H:i" data-flatpickr-date-format="Y-m-d H:i" value="<?php echo date("Y-m-d H:i"); ?>">
                                </div>

                                <!-- Timezone -->
                                <div class="form-group">
                                    <label class="form-label">Timezone</label>
                                    <select name="timezone" class="form-control"></select>
                                    <small class="form-text text-muted">Select timezone</small>
                                </div>
                            </div>

                            <input type="hidden" name="test_id" id="test_id" value="">
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>

<!-- Modal for add new question -->
<div id="mdl_question" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.questions.store'], 'files' => true, 'id' =>'frm_question']) !!}

            <div class="modal-header">
                <h5 class="modal-title">New Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label">Question*</label>

                    <!-- Rich Text Editor by Quill -->
                    <div id="q_new_editor" style="height: 300px;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Document:</label>
                    <div class="custom-file">
                        <input id="q_file" type="file" name="attachment" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                        <label for="q_file" class="custom-file-label">Choose ...</label>
                    </div>
                    <small class="form-text text-muted">PDF for Doc file (Max 5MB).</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Marks (Optional)</label>
                    <input type="number" class="form-control" name="score" placeholder="Marks (Optional)" min="1">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
            </div>

            <input type="hidden" name="model_type" value="test">

            {!! Form::close() !!}
        </div>
    </div>
</div>

<!-- Modal for add edit question -->
<div id="mdl_question_edit" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.questions.update', 0], 'files' => true, 'id' =>'frm_question_edit']) !!}

            <div class="modal-header">
                <h5 class="modal-title">Edit Question</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label">Question*</label>
                    
                    <!-- Rich Text Editor by Quill -->
                    <div id="q_edit_editor" style="height: 300px;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Document:</label>
                    <div class="custom-file">
                        <input id="q_e_file" type="file" name="attachment" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                        <label for="q_e_file" class="custom-file-label">Choose ...</label>
                    </div>
                    <small class="form-text text-muted">Max file size is 5MB.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Marks (Optional)</label>
                    <input type="number" class="form-control" name="score" placeholder="Marks (Optional)" min="1">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
            </div>

            <input type="hidden" name="model_type" value="test">

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

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

$(function() {

    var status = 'create';

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

    var quill_new = new Quill('#q_new_editor', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    var quill_edit = new Quill('#q_edit_editor', {
        modules: {
            toolbar: toolbarOptions
        },
        theme: 'snow'
    });

    $('select[name="course_id"]').select2();
    $('select[name="lesson_id"]').select2();

    // Timezone
    $('select[name="timezone"]').timezones();
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();

    // Course Type
    $('#t_type_1').on('change', function(e) {
        var style = $(this).prop('checked') ? 'none' : 'block';
        $('div[for="t_type_1"]').css('display', style);
    });

    // Course Type
    $('#t_type_2').on('change', function(e) {
        console.log('dddd');
        var style = $(this).prop('checked') ? 'block' : 'none';
        $('div[for="t_type_1"]').css('display', style);
    });

    loadLessons($('select[name="course_id"]').val());

    $('select[name="course_id"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    // Test Type setting
    $('input[name="type"]').on('change', function(e) {
        
        if($(this).val() == 'course') {
            $('div[for="lesson"]').addClass('d-none');
        }

        if($(this).val() == 'lesson') {
            $('div[for="lesson"]').removeClass('d-none');
        }
    });

    $('input[type="number"]').on('keypress', function(e) {
        if(e.which == 45) {
            return false;
        }
    });

    // Add New Question
    $('#btn_new_question').on('click', function() {

        if(!checkValidForm($('#frm_test'))){
            return false;
        }

        if(status == 'create') {
            // Store Test first
            $('#frm_test').ajaxSubmit({
                success: function(res) {
                    
                    if(res.success) {
                        status = 'update';
                        $('#test_id').val(res.test_id);
                        $('#mdl_question').modal('toggle');
                    }
                }
            });
        }

        if(status == 'update') {
            $('#mdl_question').modal('toggle');
        }
    });

    $('#frm_question').submit(function(e) {

        e.preventDefault();
        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var question = quill_new.root.innerHTML;

                if(question == '<p><br></p>') {
                    if($('#q_new_editor').siblings('.invalid-feedback').length < 1) {
                        var err_msg = $('<div class="invalid-feedback" style="margin-top: -20px; display:block;">This field is required.</div>');
                        err_msg.insertAfter($('#q_new_editor'));
                    }
                    return false;
                }

                if(!checkValidForm($(this))){
                    return false;
                }

                formData.push({
                    name: 'question',
                    type: 'text',
                    value: question
                });
                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: $('#test_id').val()
                });
                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {

                if(res.success) {

                    var ele_quiz_ul = $('#questions').find('ul');

                    if(ele_quiz_ul.length > 0) {
                        $(res.html).hide().appendTo(ele_quiz_ul).toggle(500);
                    } else {
                        $('#questions').html(`
                            <div class="page-separator">
                                <div class="page-separator__text">Questions</div>
                            </div>
                            <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                        );
                    }

                    $('#mdl_question').modal('toggle');

                    // init Modal
                    $('#frm_question input[name="image"]').val('');
                    $('#frm_question textarea[name="question"]').val('');
                    $('#frm_question input[name="score"]').val('');
                }
            }
        });
    });

    // Edit question
    $('#questions').on('click', 'a.edit', function(e) {
        e.preventDefault();
        var mdl_edit = $('#mdl_question_edit');
        var ele_li = $(this).closest('li');
        var route = $(this).attr('href');
        var question = ele_li.find('div.tute-question').html();
        var marks = ele_li.find('input[name="score"]').val();

        // set content
        quill_edit.root.innerHTML = question;
        mdl_edit.find('input[name="score"]').val(marks);
        
        $('#frm_question_edit').attr('action', route);
        mdl_edit.modal('toggle');
    });

    $('#frm_question_edit').on('submit', function(e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var question = quill_edit.root.innerHTML;
                formData.push({
                    name: 'question',
                    type: 'text',
                    value: question
                });
                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: $('#test_id').val()
                });
                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {
                if(res.success) {
                    var ele_li = $('li[data-id="'+ res.question.id +'"]');
                    ele_li.replaceWith(res.html);
                    $('#mdl_question_edit').modal('toggle');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    // Delete a question
    $('#questions').on('click', 'a.delete', function(e) {

        e.preventDefault();
        var route = $(this).attr('href');
        var question_item = $(this).closest('li');

        swal({
            title: "Are you sure?",
            text: "This Question will removed from this quiz",
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            dangerMode: false,

        }, function(val) {
            if (val) {
                $.ajax({
                    method: 'GET',
                    url: route,
                    success: function(res) {
                        if (res.success) {
                            question_item.toggle( function() { 
                                $(this).remove();
                                adjustOrder();
                            });
                        }
                    }
                });
            }
        });
    });

    $('#frm_test').on('submit', function(e) {
        e.preventDefault();

        if(!checkValidForm($(this))){
            return false;
        }

        btnLoading($('#btn_save_test'), true);

        $(this).ajaxSubmit({
            success: function(res) {

                if(res.success) {
                    swal({
                        title: "Successfully Stored",
                        text: "Test is stored successfully",
                        type: 'success',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        dangerMode: false,

                    }, function(val) {
                        if (val) {
                            // var url = '/dashboard/tests/' + res.test_id + '/edit';
                            var url = "{{ route('admin.tests.index') }}";
                            window.location.href = url;
                        }
                    });
                }

                btnLoading($('#btn_save_test'), false);
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    function loadLessons(course) {

        // Get Lessons by selected Course
        $.ajax({
            method: 'GET',
            url: "{{ route('admin.test.getLessonsByCourse') }}",
            data: {course_id: course},
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