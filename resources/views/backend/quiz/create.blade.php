@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<style>
.modal .modal-body {
    max-height: 80vh;
    overflow: auto;
}

.accordion .btn-actions {
    margin: 0 10px;
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
                    <h2 class="mb-0">@lang('labels.backend.quiz.create')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.quizs.index') }}">@lang('labels.backend.quizzes.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.quiz.create')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.quizs.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.quizs.store'], 'files' => true, 'id' =>'frm_quiz']) !!}

            <div class="row align-items-start">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.quiz.create')</div>
                    </div>

                    <label class="form-label">@lang('labels.backend.quiz.fields.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg"
                            placeholder="@lang('labels.backend.quiz.fields.title_placeholder')" value="" tute-no-empty>
                    </div>

                    <!-- Quiz Description -->
                    <label class="form-label">@lang('labels.backend.quiz.fields.description')</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="3"
                            placeholder="@lang('labels.backend.quiz.fields.description_placeholder')"></textarea>
                        <small class="form-text text-muted">
                        @lang('labels.backend.quiz.fields.description_note')</small>
                    </div>

                    <div id="questions"></div>

                    <button type="button" id="btn_new_section" class="btn btn-block btn-outline-primary">
                    @lang('labels.backend.quiz.buttons.add_section')</button>

                </div>
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <a href="javascript:void(0);" class="btn btn-accent" id="btn_quiz_save">@lang('labels.backend.quiz.buttons.save_changes')</a>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="#"><strong>@lang('labels.backend.buttons.save_draft')</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.options')</div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            
                            <!-- Set Course -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.course')</label>
                                <div class="form-group mb-0">
                                    <select name="course_id" class="form-control custom-select @error('course') is-invalid @enderror" tute-no-empty>
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}"> {{ $course->title }} </option>
                                        @endforeach
                                    </select>
                                    @error('course')
                                    <div class="invalid-feedback">Course is required.</div>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_course')</small>
                            </div>

                            <!-- Set Lesson -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.lessons')</label>
                                <select name="lesson_id" class="form-control"></select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_lesson')</small>
                            </div>

                            <!-- Duration -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.duration')</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" name="duration_hours" class="form-control" min="1" placeholder="@lang('labels.backend.sidebar.hours')" value="">
                                    </div>
                                    <div class="col">
                                        <input type="number" name="duration_mins" class="form-control" min="1" placeholder="@lang('labels.backend.sidebar.mins')" value="" tute-no-empty>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Marks -->
                            <!-- <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.quiz.total_marks')</label>
                                <input type="number" name="score" class="form-control" placeholder="@lang('labels.backend.sidebar.quiz.total_marks_placeholder')" min="1" value="" tute-no-empty>
                            </div> -->

                            <!-- Quiz Process -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.quiz.take_quiz')</label>
                                <div class="custom-control custom-checkbox">
                                    <input id="take_quiz" name="take_type" type="checkbox" class="custom-control-input" value="1">
                                    <label for="take_quiz" class="custom-control-label">
                                    @lang('labels.backend.sidebar.quiz.take_quiz_description')</label>
                                </div>
                                <small class="text-muted text-right">@lang('labels.backend.sidebar.quiz.take_quiz_note')</small>
                            </div>

                            <!-- Quiz Type -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.quiz.quiz_type')</label>
                                <div class="custom-controls-stacked">
                                    <div class="custom-control custom-radio py-2">
                                        <input id="q_type_1" name="type" type="radio" class="custom-control-input" checked="" value="1">
                                        <label for="q_type_1" class="custom-control-label">
                                        @lang('labels.backend.sidebar.quiz.take_any_time')</label>
                                    </div>
                                    <div class="custom-control custom-radio py-2">
                                        <input id="q_type_2" name="type" type="radio" class="custom-control-input" value="2">
                                        <label for="q_type_2" class="custom-control-label">
                                        @lang('labels.backend.sidebar.quiz.take_fix_time')</label>
                                    </div>
                                </div>
                            </div>

                            <div for="q_type_1" style="display: none;">
                                <hr>
                                <!-- Due Data -->
                                <div class="form-group">
                                    <label class="form-label">@lang('labels.backend.sidebar.quiz.quiz_date')</label>
                                    <input name="start_date" type="text" class="form-control" data-toggle="flatpickr" data-flatpickr-enable-time="true" 
                                    data-flatpickr-alt-format="F j, Y at H:i" data-flatpickr-date-format="Y-m-d H:i" value="<?php echo date("Y-m-d H:i"); ?>">
                                </div>

                                <!-- Timezone -->
                                <div class="form-group">
                                    <label class="form-label">@lang('labels.backend.sidebar.timezone')</label>
                                    <select name="timezone" class="form-control"></select>
                                    <small class="form-text text-muted">@lang('labels.backend.sidebar.select_timezone')</small>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {!! Form::close() !!}
        </div>
    </div>
</div>

<!-- Modal for New Section -->
<div id="mdl_section" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.questions.addsection'], 'files' => true, 'id' =>'frm_section']) !!}

            <div class="modal-header">
                <h5 class="modal-title">@lang('labels.backend.quiz.modal.new_section')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.quiz.modal.section_title') *</label>
                    <input type="text" name="section_title" class="form-control" 
                    placeholder="@lang('labels.backend.quiz.modal.section_title_placeholder')" required>
                </div>

                <!-- <div class="form-group">
                    <label class="form-label">@lang('labels.backend.quiz.modal.section_marks')</label>
                    <input type="number" name="section_marks" class="form-control" placeholder="@lang('labels.backend.quiz.modal.section_marks_placeholder')">
                </div> -->

            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">@lang('labels.backend.quiz.buttons.save_changes')</button>
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
                <h5 class="modal-title">@lang('labels.backend.quiz.modal.new_question')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.quiz.modal.question_type')</label>
                    <select name="type" class="form-control custom-select">
                        <option value="0">@lang('labels.backend.quiz.modal.single_answer')</option>
                        <option value="1">@lang('labels.backend.quiz.modal.multi_answer')</option>
                        <option value="2">@lang('labels.backend.quiz.modal.fill_blank')</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.quiz.modal.question')</label>
                    <textarea class="form-control" name="question" rows="3" placeholder="@lang('labels.backend.quiz.modal.question_placeholder')"></textarea>
                </div>

                <div id="options" class="options form-group">
                    <div class="wrap wrap-signle-answer border-1 p-3">
                        <div class="form-inline mb-16pt d-flex">
                            <div class="flex">
                                <label class="form-label option-label">@lang('labels.backend.quiz.modal.add_options'): </label>
                            </div>
                            <button id="btn_addOptions" class="btn btn-md btn-outline-secondary" type="button">+</button>
                        </div>
                        <hr>
                        <div class="options-wrap">
                            <div class="row mb-8pt">
                                <div class="col-10 form-inline">
                                    <div class="custom-control custom-radio">
                                        <input id="option_s0" name="option_single" type="radio" class="custom-control-input" checked="" value="0">
                                        <label for="option_s0" class="custom-control-label">&nbsp;</label>
                                    </div>
                                    <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="Option Text">
                                </div>
                                <div class="col-2 text-right">
                                    <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.quiz.modal.completion_points')</label>
                    <input name="score" type="number" class="form-control" value="1">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">@lang('labels.backend.general.save_changes')</button>
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

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

$(function() {

    var status = 'new';
    var q_status = 'new';
    var s_status = 'new';
    var quiz_id = -1;
    var group_id;
    var current_option_type = 0;
    var str_ids = ['option_s', 'option_m', 'option_f'];
    var str_names = ['option_single', 'option_multi[]', 'option_fill[]'];

    var template = [

        $(`<div class="row mb-8pt">
            <div class="col-10 form-inline">
                <div class="custom-control custom-radio">
                    <input id="option_s" name="option_single" type="radio" class="custom-control-input" value="0">
                    <label for="option_s" class="custom-control-label">&nbsp;</label>
                </div>
                <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="@lang('labels.backend.quiz.template.single_text')">
            </div>
            <div class="col-2 text-right">
                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
            </div>
        </div>`),

        $(`<div class="row mb-8pt">
            <div class="col-10 form-inline">
                <div class="custom-control custom-checkbox">
                    <input id="option_m" name="option_multi[]" type="checkbox" class="custom-control-input" value="0">
                    <label for="option_m" class="custom-control-label">&nbsp;</label>
                </div>
                <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="@lang('labels.backend.quiz.template.multi_text')">
            </div>
            <div class="col-2 text-right">
                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
            </div>
        </div>`),

        $(`<div class="row mb-8pt">
            <div class="col-10 form-inline">
                <div class="custom-control custom-checkbox">
                    <input id="option_f" name="option_fill[]" type="checkbox" class="custom-control-input" value="0">
                    <label for="option_f" class="custom-control-label">&nbsp;</label>
                </div>
                <input type="text" name="option_text[]" class="form-control" style="width: 90%" placeholder="@lang('labels.backend.quiz.template.blank_text')">
            </div>
            <div class="col-2 text-right">
                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
            </div>
        </div>`)

    ];

    $('select[name="course_id"]').select2();
    $('select[name="lesson_id"]').select2();

    // Timezone
    $('select[name="timezone"]').timezones();
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();

    // Course Type
    $('#q_type_1').on('change', function(e) {
        var style = $(this).prop('checked') ? 'none' : 'block';
        $('div[for="q_type_1"]').css('display', style);
    });

    // Course Type
    $('#q_type_2').on('change', function(e) {
        var style = $(this).prop('checked') ? 'block' : 'none';
        $('div[for="q_type_1"]').css('display', style);
    });

    //=== Load Lesson by Course
    loadLessons($('select[name="course_id"]').val());
    $('select[name="course_id"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    $('input[type="number"]').on('keypress', function(e) {
        if(e.which == 45) {
            return false;
        }
    });

    //=== Add new section
    $('#btn_new_section').on('click', function(e) {
        e.preventDefault();

        if(!checkValidForm($('#frm_quiz'))){
            return false;
        }

        s_status = 'new';

        if(status == 'new') {

            // Store Quiz
            $('#frm_quiz').ajaxSubmit({
                success: function(res) {
                    
                    if(res.success) {
                        if(status == 'new') {
                            status = 'update';
                            quiz_id = res.quiz.id;
                        }
                        
                        $('#mdl_section').modal('toggle');
                    }
                }
            });
        } else {
            $('#mdl_section').modal('toggle');
        }
    });

    $('#frm_section').on('submit', function(e){
        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: quiz_id
                });

                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });

                formData.push({
                    name: 'action',
                    type: 'text',
                    value: s_status
                });

                if(s_status == 'edit') {
                    formData.push({
                        name: 'group_id',
                        type: 'text',
                        value: group_id
                    });
                }
            },
            success: function(res) {
                if(s_status == 'new') {
                    var page_section = $('#questions').find('div.page-section');
                    if(page_section.length < 1) {
                        $('#questions').html($('<div class="border-left-2 page-section pl-32pt"></div>'));
                    }
                    $(res.html).hide().appendTo($('#questions .page-section')).toggle(500);
                    $('#mdl_section').modal('toggle');

                    // init Modal
                    $('#frm_section input[name="section_title"]').val('');
                    $('#frm_section input[name="section_marks"]').val('');
                } else {
                    var group = $('#questions').find('div[group-id="'+ group_id +'"]');
                    group.find('h4').text(res.title);
                    group.find('h5').text(res.score);
                    $('#mdl_section').modal('toggle');
                }
            }
        });
    });

    // Edit Group Information
    $('#questions').on('click', '.btn-edit', function(e) {
        // init Modal
        s_status = 'edit';
        group_id = $(this).attr('data-id');
        var title = $(this).closest('.group-wrap').find('h4').text();
        var marks = $(this).closest('.group-wrap').find('h5').text();
        $('#frm_section input[name="section_title"]').val(title);
        $('#frm_section input[name="section_marks"]').val(marks);
        $('#mdl_section').modal('toggle');
    });

    //=== Add new question to group
    $('#questions').on('click', '.btn-question', function(e) {
        e.preventDefault();
        q_status = 'new';
        group_id = $(this).attr('data-id');

        // Init Modal
        $('#mdl_question').find('select[name="type"]').val(0);
        $('#mdl_question').find('textarea[name="question"]').val('');
        $('#mdl_question').find('input[name="score"]').val(1);

        $('#mdl_question').find('div.options-wrap').empty();
        $('#mdl_question').find('div.options-wrap').html(template[current_option_type].clone());

        $('#mdl_question').modal('toggle');
    });

    $('#frm_question').submit(function(e) {

        e.preventDefault();

        if($('#frm_question').find('select[name="type"]').val() == '0') {
            var single_options = $('#frm_question').find('.options-wrap').find('input[name="option_single"]');
            $.each(single_options, function(idx, item){
                $(item).val(idx);
            });
        }

        if($('#frm_question').find('select[name="type"]').val() == '1') {
            var multi_options = $('#frm_question').find('.options-wrap').find('input[name="option_multi[]"]');
            $.each(multi_options, function(idx, item){
                $(item).val(idx);
            });
        }

        if($('#frm_question').find('select[name="type"]').val() == '2') {
            var fill_options = $('#frm_question').find('.options-wrap').find('input[name="option_fill[]"]');
            $.each(fill_options, function(idx, item){
                $(item).val(idx);
            });
        }

        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: quiz_id
                });

                formData.push({
                    name: 'group_id',
                    type: 'int',
                    value: group_id
                });

                formData.push({
                    name: 'model_type',
                    type: 'text',
                    value: 'quiz'
                });

                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {

                if(res.success) {

                    if(q_status == 'new') {
                        var ele_group_ul = $('#questions').find('div[group-id="' + group_id + '"] ul');
                        if(ele_group_ul.length > 0) {
                            $(res.html).hide().appendTo(ele_group_ul).toggle(500);
                        } else {
                            $('#questions').find('div[group-id="' + group_id + '"]').append(`
                                <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                            );
                        }
                    }

                    if(q_status == 'edit') {
                        var ele_li = $('#questions').find('li[data-id="'+ res.question.id +'"]');
                        ele_li.replaceWith($(res.html));
                    }

                    if(res.section_score !== undefined) {
                        $('#questions').find('div[group-id="' + group_id + '"]').find('h5.badge-pill').text(res.section_score);
                    }

                    $('#mdl_question').modal('toggle');
                }
            }
        });
    });

    // ==== Edit Question ==== //
    $('#questions').on('click', 'a.question-edit', function(e) {
        e.preventDefault();
        q_status = 'edit';
        var route = $(this).attr('href');
        var update_route = $(this).attr('data-update');
        group_id = $(this).attr('group_id');

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                $('#frm_question').attr('action', update_route);
                $('#frm_question').prepend('<input name="_method" type="hidden" value="PATCH">');
                $('#frm_question').find('select[name="type"]').val(res.question.type).change();
                $('#frm_question').find('textarea[name="question"]').val(res.question.question);
                $('#frm_question').find('input[name="score"]').val(res.question.score);
                $('#options').find('div.options-wrap').html($(res.html));
                $('#mdl_question').modal('toggle');
            },
            error: function(err) {
                console.log(res);
            }
        });
        
    });

    $('#mdl_question').on('change', 'select[name="type"]', function(e) {
        current_option_type = $(this).val();
        if(current_option_type == 2) {
            $('#options').find('.option-label').text('Add text (Check for blank)');
        } else {
            $('#options').find('.option-label').text('Add Options');
        }
        $('#mdl_question').find('div.options-wrap').html(template[current_option_type]);
    });

    $('#btn_addOptions').click(function () {
        option_num = uniqId();
        var new_val = parseInt(option_num) + 1;
        var new_id = str_ids[current_option_type] + new_val;
        var new_ele = template[current_option_type].clone();
        new_ele.find('input[name="' + str_names[current_option_type] + '"]').attr('id', new_id);
        new_ele.find('label').attr('for', new_id);
        new_ele.appendTo("#options .options-wrap");
    });

    // Delete option from question modal
    $('#options').on('click', '.options-wrap .remove', function(e) {
        $(this).closest('.row').remove();
        adjustOrder('option');
    });

    // ==== Delete Question ====/
    $('#questions').on('click', 'a.question-delete', function(e) {

        e.preventDefault();
        var route = $(this).attr('href');
        var question_item = $(this).closest('li');
        group_id = $(this).attr('group_id');

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

                            if(res.section_score !== undefined) {
                                $('#questions').find('div[group-id="' + group_id + '"]').find('h5.badge-pill').text(res.section_score);
                            }

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

    // ==== Save quiz ==== //
    $('#btn_quiz_save').on('click', function() {

        if(!checkValidForm($('#frm_quiz'))){
            return false;
        }

        btnLoading($(this), true);

        $('#frm_quiz').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                formData.push({
                    name: 'model_id',
                    type: 'int',
                    value: quiz_id
                });
            },
            success: function(res) {
                
                if(res.success) {
                    swal({
                        title: "Successfully Stored",
                        text: "Quiz is stored successfully",
                        type: 'success',
                        showCancelButton: true,
                        showConfirmButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        dangerMode: false,

                    }, function(val) {
                        if (val) {
                            // var url = '/dashboard/quizs/' + res.quiz_id + '/edit';
                            var url = "{{ route('admin.quizs.index') }}";
                            window.location.href = url;
                        }
                    });
                } else {
                    swal('Warning!', res.message, 'warning');
                }

                btnLoading($('#btn_quiz_save'), false);
            }
        });
    });

    // === Load Lesson
    function loadLessons(course_id, lesson_id = 0) { // Course ID and selected Lesson ID

        // Get Lessons by selected Course
        $.ajax({
            method: 'GET',
            url: "{{ route('admin.lessons.getLessonsByCourse') }}",
            data: {
                course_id: course_id,
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

// Add New Question
$('#btn_new_quiz').on('click', function(e) {

    e.preventDefault();

    if(quiz.id == '') {

        $('#frm_quiz').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                var title = formObject.find('input[name="title"]');
                if (title.val() == '') { // If title is empty then display Error msg
                    title.addClass('is-invalid');
                    var err_msg = $('<div class="invalid-feedback">Title is required field.</div>');
                    err_msg.insertAfter(title);
                    title.focus();
                    return false;
                }

                // Add course Id;
                formData.push({
                    name: 'course_id',
                    type: 'int',
                    value: $('#course').val()
                });
                formData.push({
                    name: 'send_type',
                    type: 'text',
                    value: 'ajax'
                });
            },
            success: function(res) {
                if(res.success) {
                    quiz.id = res.quiz.id;
                    createQuestion();
                } else {
                    swal('Warning!', res.message, 'warning');
                }
            }
        });
    } else createQuestion();

});

function createQuestion() {

    if($('#frm_question').find('select[name="type"]').val() == '0') {
        var single_options = $('#frm_question').find('.options-wrap').find('input[name="option_single"]');
        $.each(single_options, function(idx, item){
            $(item).val(idx);
        });
    }

    if($('#frm_question').find('select[name="type"]').val() == '1') {
        var multi_options = $('#frm_question').find('.options-wrap').find('input[name="option_multi[]"]');
        $.each(multi_options, function(idx, item){
            $(item).val(idx);
        });
    }

    if($('#frm_question').find('select[name="type"]').val() == '2') {
        var fill_options = $('#frm_question').find('.options-wrap').find('input[name="option_fill[]"]');
        $.each(fill_options, function(idx, item){
            $(item).val(idx);
        });
    }

    $('#frm_question').ajaxSubmit({
        beforeSubmit: function(formData, formObject, formOptions) {

            // Append quill data
            formData.push({
                name: 'question',
                type: 'text',
                value: JSON.stringify(quiz_quill.getContents().ops)
            });
            formData.push({
                name: 'quiz_id',
                type: 'int',
                value: quiz.id
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
                    ele_quiz_ul.append($(res.html));
                } else {
                    $('#questions').html(`
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.backend.quiz.questions')</div>
                        </div>
                        <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                    );
                }

                loadQuestions();
                quiz_quill.setContents('');
            }
        }
    });
}
function uniqId() {
    return Math.round(new Date().getTime() + (Math.random() * 100));
}

function loadQuestions() {
    var ele_quiz_texts = $('.quiz-textarea');
    if(ele_quiz_texts.length > 0) {

        $.each(ele_quiz_texts, function(idx, item) {

            var ele_id = $(item).attr('id');
            var content = $(item).val();
            var quiz_quill = new Quill('#editor_' + ele_id);
            quiz_quill.setContents(JSON.parse(content));
            var html = quiz_quill.root.innerHTML;
            $('#content_' + ele_id).html($(html));
        });
    }
}

</script>
@endpush

@endsection