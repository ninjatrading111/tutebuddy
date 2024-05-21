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
                    <h2 class="mb-0">@lang('labels.backend.quiz.edit')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.quizs.index') }}">@lang('labels.backend.quizzes.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.quiz.edit')
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

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.quizs.update', $quiz->id], 'files' => true, 'id' =>'frm_quiz']) !!}

            <div class="row align-items-start">

                <!-- Left Side -->
                <div class="col-md-8">
                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.quiz.edit_quiz')</div>
                    </div>

                    <!-- Quiz Title -->
                    <label class="form-label">@lang('labels.backend.quiz.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.courses.fields.title')" value="{{ $quiz->title }}">
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <!-- Quiz Description -->
                    <label class="form-label">@lang('labels.backend.quiz.description')</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="3"
                            placeholder="@lang('labels.backend.quiz.description_placeholder')">{{ $quiz->description }}</textarea>
                        <small class="form-text text-muted">
                            @lang('labels.backend.quiz.description_note')</small>
                    </div>

                    <!-- Questions Area -->
                    <div id="questions">

                        @if($quiz->question_groups->count() > 0)

                        <div class="border-left-2 page-section pl-32pt">
                            @foreach($quiz->question_groups as $group)

                            <div class="group-wrap py-32pt mb-16pt border-bottom-1" group-id="{{ $group->id }}">

                                <div class="d-flex align-items-center page-num-container">
                                    <div class="page-num">{{ $loop->iteration }}</div>
                                    <div class="flex">
                                        <div class="d-flex">
                                            <h4 class="flex mb-0 group-title" style="cursor:pointer;">{{ $group->title }}</h4>
                                            <h5 class="badge badge-pill font-size-16pt badge-accent">{{ $group->score }}</h4>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-primary ml-16pt btn-edit" data-id="{{ $group->id }}">
                                        @lang('labels.backend.buttons.edit')
                                    </button>
                                    <button type="button" class="btn btn-outline-primary ml-16pt btn-question" data-id="{{ $group->id }}">
                                        @lang('labels.backend.quiz.add_question')
                                    </button>
                                </div>

                                @if($group->questions->count() > 0)

                                <ul class="list-group stack mb-40pt">

                                    @foreach($group->questions as $question)

                                    <li class="list-group-item d-flex quiz-item" data-id="{{ $question->id }}">

                                        <div class="flex d-flex flex-column">
                                            <div class="card-title mb-16pt">{{ $loop->iteration }}. {{ $question->question }}</div>
                                            
                                            <div class="text-right">
                                                <div class="chip chip-outline-secondary">
                                                    @if($question->type == 0)
                                                        @lang('labels.backend.quiz.single_answer')
                                                    @else
                                                        @lang('labels.backend.quiz.multi_answer')
                                                    @endif
                                                </div>
                                                <div class="chip chip-outline-secondary">@lang('labels.backend.quiz.score'): {{ $question->score }}</div>
                                            </div>

                                            <div class="options-wrap">
                                                <div class="form-group">
                                                    <div class="custom-controls-stacked">
                                                    @if($question->type == 0)
                                                        @foreach($question->options as $option)
                                                        <div class="custom-control custom-radio mb-8pt">
                                                            <input id="option_s{{$option->id}}_q{{$question->id}}" name="option_single_q{{$question->id}}" type="radio" class="custom-control-input" @if($option->correct == 1) checked @endif >
                                                            <label for="option_s{{$option->id}}_q{{$question->id}}" class="custom-control-label">{{ $option->option_text }}</label>
                                                        </div>
                                                        @endforeach
                                                     @endif

                                                    @if($question->type == 1)
                                                        @foreach($question->options as $option)
                                                        <div class="custom-control custom-checkbox mb-8pt">
                                                            <input id="option_m{{$option->id}}_q{{$question->id}}" name="option_multi_q{{$question->id}}[]" type="checkbox" class="custom-control-input" @if($option->correct == 1) checked @endif>
                                                            <label for="option_m{{$option->id}}_q{{$question->id}}" class="custom-control-label">{{ $option->option_text }}</label>
                                                        </div>
                                                        @endforeach
                                                    @endif

                                                    @if($question->type == 2)
                                                        @foreach($question->options as $option)
                                                            @if($option->correct == 1)
                                                            <input type="text" class="form-control form-control-flush font-size-16pt text-70 border-bottom-1 inline pl-8pt" 
                                                            style="width: fit-content; display: inline; border-color: #333;" placeholder="@lang('labels.backend.quiz.add_correct_word')"
                                                            value="">
                                                            @else
                                                            <label class="text-70 font-size-16pt">{{ $option->option_text }}</label>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="dropdown">
                                            <a href="#" data-toggle="dropdown" data-caret="false" class="text-muted"><i
                                                    class="material-icons">more_horiz</i></a>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <?php
                                                    $edit_route = route('admin.getQuestionByAjax', $question->id);
                                                    $update_route = route('admin.questions.update', $question->id);
                                                    $delete_route = route('admin.questions.delete', $question->id);
                                                ?>
                                                <a href="{{ $edit_route }}" data-update="{{ $update_route }}" class="dropdown-item question-edit"
                                                group_id="{{ $group->id }}">
                                                    @lang('labels.backend.quiz.edit_question')</a>
                                                <div class="dropdown-divider"></div>
                                                <a href="{{ $delete_route }}" class="dropdown-item text-danger question-delete" group_id="{{ $group->id }}">
                                                    @lang('labels.backend.quiz.delete_question')</a>
                                            </div>
                                        </div>
                                    </li>

                                    @endforeach
                                </ul>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <button type="button" id="btn_new_section" class="btn btn-block btn-outline-primary">
                        @lang('labels.backend.quiz.buttons.add_section')</button>
                </div>

                <!-- Right Side -->
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" id="btn_quiz_save" class="btn btn-accent">@lang('labels.backend.buttons.save_draft')</button>
                            <button type="submit" id="btn_quiz_publish" class="btn btn-primary">@lang('labels.backend.buttons.publish')</button>
                            <a href="{{ route('student.quiz.show', [$quiz->lesson->slug, $quiz->id]) }}" 
                                class="btn btn-info">@lang('labels.backend.buttons.preview')</a>
                        </div>
                        <div class="list-group list-group-flush">
                            @if($quiz->published == 0)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.save_draft')</strong></a>
                                <i class="material-icons text-muted draft">check</i>
                            </div>
                            @endif

                            @if($quiz->published == 1)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.publish')</strong></a>
                                <i class="material-icons text-muted publish">check</i>
                            </div>
                            @endif
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
                                    <select name="course_id" class="form-control custom-select @error('course') is-invalid @enderror">
                                        @foreach($courses as $course)
                                        <option value="{{ $course->id }}" 
                                            @if($course->id == $quiz->course_id) selected @endif> {{ $course->title }}
                                        </option>
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
                                <?php
                                    $hours = (int)((int)$quiz->duration / 60);
                                    $mins = (int)$quiz->duration % 60;
                                ?>
                                <div class="row">
                                    <div class="col">
                                        <input type="number" name="duration_hours" class="form-control" min="1" placeholder="Hours" value="{{ $hours }}">
                                        <small class="text-muted text-right">@lang('labels.backend.sidebar.hours')</small>
                                    </div>
                                    <div class="col">
                                        <input type="number" name="duration_mins" class="form-control" min="1" placeholder="Mins" value="{{ $mins }}" tute-no-empty>
                                        <small class="text-muted text-right">@lang('labels.backend.sidebar.mins')</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Marks -->
                            <!-- <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.quiz.total_marks')</label>
                                <input type="number" name="score" class="form-control" placeholder="@lang('labels.backend.sidebar.quiz.total_marks_placeholder')" min="1" value="{{ $quiz->score }}">
                            </div> -->

                            <!-- Quiz Process -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.quiz.take_quiz')</label>
                                <div class="custom-control custom-checkbox">
                                    <input id="take_quiz" name="take_type" type="checkbox" class="custom-control-input" value="1">
                                    <label for="take_quiz" class="custom-control-label">@lang('labels.backend.sidebar.quiz.take_quiz_description')</label>
                                </div>
                                <small class="text-muted text-right">@lang('labels.backend.sidebar.quiz.take_quiz_note')</small>
                            </div>

                            <!-- Quiz Type -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.quiz.quiz_type')</label>
                                <div class="custom-controls-stacked">
                                    <div class="custom-control custom-radio py-2">
                                        <input id="q_type_1" name="type" type="radio" class="custom-control-input" @if($quiz->type == 1) checked="true" @endif value="1">
                                        <label for="q_type_1" class="custom-control-label">@lang('labels.backend.sidebar.quiz.take_any_time')</label>
                                    </div>
                                    <div class="custom-control custom-radio py-2">
                                        <input id="q_type_2" name="type" type="radio" class="custom-control-input" @if($quiz->type == 2) checked="true" @endif value="2">
                                        <label for="q_type_2" class="custom-control-label">@lang('labels.backend.sidebar.quiz.take_fix_time')</label>
                                    </div>
                                </div>
                            </div>

                            <div for="q_type_1" @if($quiz->type == 1) style="display: none;" @endif>
                                <hr>
                                <!-- Quiz Data -->
                                <div class="form-group">
                                    <label class="form-label">@lang('labels.backend.sidebar.quiz.quiz_date')</label>
                                    <input name="start_date" type="text" class="form-control" data-toggle="flatpickr" data-flatpickr-enable-time="true" 
                                    data-flatpickr-alt-format="F j, Y at H:i" data-flatpickr-date-format="Y-m-d H:i" value="{{ $quiz->start_date }}">
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
                    <input type="text" name="section_title" class="form-control" placeholder="@lang('labels.backend.quiz.modal.section_title_placeholder')" required>
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

    var quiz_id = '{{ $quiz->id }}';
    var course_id = '{{ $quiz->course_id }}';
    var lesson_id = '{{ $quiz->lesson_id }}';
    var group_id;
    var str_ids = ['option_s', 'option_m', 'option_f'];
    var str_names = ['option_single', 'option_multi[]', 'option_fill[]'];
    var q_status = 'new';
    var s_status = 'new';
    
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

    var current_option_type = 0;

    $('select[name="course_id"]').select2();
    $('select[name="lesson_id"]').select2();

    // Timezone
    $('select[name="timezone"]').timezones();
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();

    if('{{ $quiz->type }}' == 2) {
        $('select[name="timezone"]').val('{{ $quiz->timezone }}').change();
    }

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
    loadLessons(course_id, lesson_id);
    $('select[name="course_id"]').on('change', function(e) {
        loadLessons($(this).val());
    });

    //=== Add new section
    $('#btn_new_section').on('click', function(e) {
        e.preventDefault();
        s_status = 'new';
        $('#mdl_section').modal('toggle');
    });

    $('input[type="number"]').on('keypress', function(e) {
        if(e.which == 45) {
            return false;
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

    $('#frm_question').submit(function(e) {

        e.preventDefault();

        if($(this).find('select[name="type"]').val() == '0') {
            var single_options = $(this).find('.options-wrap').find('input[name="option_single"]');
            $.each(single_options, function(idx, item){
                $(item).val(idx);
            });
        }

        if($(this).find('select[name="type"]').val() == '1') {
            var multi_options = $(this).find('.options-wrap').find('input[name="option_multi[]"]');
            $.each(multi_options, function(idx, item){
                $(item).val(idx);
            });
        }

        if($(this).find('select[name="type"]').val() == '2') {
            var fill_options = $(this).find('.options-wrap').find('input[name="option_fill[]"]');
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

    // ==== Update quiz ==== //
    $('#btn_quiz_save').on('click', function(e) {

        e.preventDefault();

        btnLoading($(this), true);

        $('#frm_quiz').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'published',
                    type: 'integer',
                    value: 0
                });
            },
            success: function(res) {
                if(res.success) {
                    swal("Success!", "Successfully updated", "success");
                } else {
                    swal('Warning!', res.message, 'warning');
                }

                btnLoading($('#btn_quiz_save'), false);
            }
        });
    });

    // ==== Update quiz ==== //
    $('#btn_quiz_publish').on('click', function(e) {

        e.preventDefault();

        btnLoading($(this), true);

        $('#frm_quiz').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'published',
                    type: 'integer',
                    value: 1
                });
            },
            success: function(res) {
                if(res.success) {
                    swal("Success!", "Successfully Published", "success");
                } else {
                    swal('Warning!', res.message, 'warning');
                }

                btnLoading($('#btn_quiz_publish'), false);
            }
        });
    });

    function uniqId() {
        return Math.round(new Date().getTime() + (Math.random() * 100));
    }

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

    // Adjust option order
    function adjustOrder(type) {

        if(type == 'option') {
            var ele_rows = $('#options').find('.row');

            $.each(ele_rows, function(idx, item) {
                $(item).find('input[name="'+ str_names[current_option_type] +'"]').val(idx);
            });
        }
    }
});

</script>
@endpush

@endsection