@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

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
[dir=ltr] .step-menu {
    box-shadow: 0 0 2px 0px black;
}
[dir=ltr] .step-menu:before, [dir=ltr] .step-menu:after {
    opacity: 0 !important;
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
                    <h2 class="mb-0">@lang('labels.backend.courses.create')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">@lang('labels.backend.courses.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.courses.create')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.courses.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true, 'id' => 'frm_course']) !!}
            <div class="row">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.courses.create')</div>
                    </div>

                    <label class="form-label">@lang('labels.backend.courses.fields.title') *</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title" class="form-control form-control-lg"
                            placeholder="@lang('labels.backend.courses.fields.title')" value="" tute-no-empty>
                    </div>

                    <label class="form-label">Slug *</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                            placeholder="slug" value="" tute-no-empty>
                            @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <label class="form-label">@lang('labels.backend.courses.fields.description') *</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5" maxlength="600"
                            placeholder="Short description" tute-no-empty></textarea>
                        <small class="form-text text-muted">
                        @lang('labels.backend.courses.fields.description_note')</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">@lang('labels.backend.courses.fields.about_course')</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px;" id="course_editor" class="mb-0"></div>
                        <small class="form-text text-muted">
                        @lang('labels.backend.courses.fields.about_course_note')</small>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text"> @lang('labels.backend.general.lessons') </div>
                    </div>

                    <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent"></div>
                    <button type="button" id="btn_add_lesson" class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">
                        + @lang('labels.backend.courses.add_lesson')
                    </button>
                </div>

                <!-- Side bar for information -->
                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="button" id="btn_save_course" class="btn btn-accent"> 
                                @lang('labels.backend.buttons.save_draft') 
                            </button>
                            <button type="button" id="btn_publish_course" class="btn btn-primary">
                                @lang('labels.backend.buttons.publish')
                            </button>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.save_draft')</strong></a>
                                <i class="material-icons text-muted draft">clear</i>
                            </div>
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

                            <!-- Set Category -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.category')*</label>
                                <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                    <option value="">Select Category</option>
                                    @foreach ($parentCategories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @if ($category->children()->count() > 0 )
                                    <?php $space = ''; ?>
                                    @include('backend.category.sub.option', ['category' => $category, 'space' =>
                                    $space, 'selected' => 0])
                                    @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_category')</small>
                            </div>

                            <!-- Set Level -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.level')</label>
                                <select name="level" class="form-control">
                                    @foreach($levels as $level)
                                    <option value="{{ $level->id }}">{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_level')</small>
                            </div>

                            <!-- Set Tags -->
                            <div class="form-group mb-0">
                                <label class="form-label">@lang('labels.backend.sidebar.tags')</label>
                                <select name="tags[]" id="course_tags" multiple="multiple" class="form-control">
                                    @foreach($tags as $tag)
                                    @php $course_tags = (!empty($course->tags)) ? json_decode($course->tags) : []; @endphp
                                    <option @if(in_array($tag->name, $course_tags)) selected @endif>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_tags')</small>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.options')</div>
                    </div>

                    <div class="card">
                        <div class="card-body options">

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_group" type="checkbox" checked="" class="custom-control-input">
                                    <label for="chk_group" class="custom-control-label form-label">@lang('labels.backend.sidebar.group_course')</label>
                                </div>
                            </div>

                            <!-- Set Max number in case of group course -->
                            <div class="form-group" for="chk_group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <label class="form-label">@lang('labels.backend.sidebar.min_students'):</label>
                                        <input type="number" name="min" class="form-control" min="1" value=""
                                            placeholder="5" tute-no-empty>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <label class="form-label">@lang('labels.backend.sidebar.max_students'):</label>
                                        <input type="number" name="max" class="form-control" min="1" value=""
                                            placeholder="30">
                                    </div>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.group_note')</small>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group" for="chk_group">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">@lang('labels.backend.sidebar.price')({{ getCurrency(config('app.currency'))['symbol'] }})</span></span>
                                    <input type="number" name="group_price" class="form-control" placeholder="5.00" min="0.5"
                                        value="" tute-no-empty>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.price_note')</small>
                            </div>

                            <div class="page-separator"></div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_private" type="checkbox" class="custom-control-input">
                                    <label for="chk_private" class="custom-control-label form-label">@lang('labels.backend.sidebar.private_course')</label>
                                </div>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group d-none" for="chk_private">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">@lang('labels.backend.sidebar.price')({{ getCurrency(config('app.currency'))['symbol'] }})</span></span>
                                    <input type="number" name="private_price" class="form-control" value="" placeholder="24.00" min="0.5">
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.private_course_note')</small>
                            </div>

                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.time_setting')</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- Set Date -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('labels.backend.sidebar.start_date'):</label>
                                            <input name="start_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('labels.backend.sidebar.end_date'):</label>
                                            <input name="end_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="<?php echo date("Y-m-d"); ?>">
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.date_note')</small>
                            </div>

                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.timezone')</label>
                                <select name="timezone" class="form-control" disabled></select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_timezone')</small>
                            </div>

                            <!-- Repeat -->
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_repeat" type="checkbox" class="custom-control-input" checked>
                                    <label for="chk_repeat" class="custom-control-label form-label">@lang('labels.backend.sidebar.repeat')</label>
                                    <input type="hidden" name="repeat" value="1">
                                </div>
                            </div>

                            <div class="form-group" for="chk_repeat">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <input type="number" name="repeat_value" value="1" class="form-control" min="1">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <select id="custom-select" name="repeat_type" class="form-control custom-select">
                                            <option value="week">@lang('labels.backend.sidebar.week')</option>
                                            <option value="month">@lang('labels.backend.sidebar.month')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.thumb')</div>
                    </div>

                    <div class="card">
                        <img src="{{asset('/assets/img/no-image.jpg')}}" id="display_course_image" alt="" width="100%">
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" name="course_image" id="course_file_image" class="custom-file-input"
                                    data-preview="#display_course_image" accept=".jpg, .jpeg, .png">
                                <label for="course_file_image" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.intro_video')</div>
                    </div>

                    <div class="card">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_course_video" src="" allowfullscreen=""></iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">@lang('labels.backend.sidebar.url')</label>
                            <input type="text" class="form-control" name="course_video" 
                                id="course_video_url" value="" data-video-preview="#iframe_course_video"
                                placeholder="@lang('labels.backend.sidebar.url_placeholder')">
                            <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

<!-- Add Lesson Modal -->
<div class="modal fade" id="modal_lesson" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xlg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.backend.courses.create_lesson')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true, 'id' =>'frm_lesson']) !!}

                    <div class="row">
                        <div class="col-12 col-md-8 mb-3">
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.lesson.fields.title'):</label>
                                <input type="text" name="lesson_title"
                                    class="form-control form-control-lg @error('lesson_title') is-invalid @enderror"
                                    placeholder="@lang('labels.backend.lesson.fields.title')" value="" tute-no-empty>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.lesson.description'):</label>
                                <textarea class="form-control" name="lesson_short_description" rows="3"></textarea>
                            </div>
                            
                            <div class="form-group" id="lesson_contents"></div>

                            <div class="form-group">
                                <div class="flex" style="max-width: 100%">
                                    <div class="btn-group" id="lesson_add_step" style="width: 100%;">
                                        <button type="button" class="btn btn-block btn-outline-secondary dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">+ @lang('labels.backend.lesson.add_step') </button>
                                        <div class="dropdown-menu" style="width: 100%;">
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="video">
                                                @lang('labels.backend.lesson.video_section')</a>
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="text">
                                                @lang('labels.backend.lesson.text_section')</a>
                                            <a class="dropdown-item" href="javascript:void(0)" section-type="document">
                                                Document Section</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-12 col-md-4">

                            <div class="form-group">
                                <label for="lesson_type" class="form-label">Lesson Type: </label>
                                <select name="lesson_type" id="lesson_type" class="form-control custom-select" tute-no-empty>
                                    <option value="0" selected>Main Lesson</option>
                                    <option value="1">Live Lesson</option>
                                    <option value="2">Demo Lesson</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.thumb'):</label>
                                <div class="card">
                                    <img src="{{asset('/assets/img/no-image.jpg')}}" id="display_lesson_image" width="100%" id="img_lesson_image" alt="">
                                    <div class="card-body">
                                        <div class="custom-file">
                                            <input type="file" id="lesson_file_image" name="lesson_file_image" 
                                                class="custom-file-input" data-preview="#display_lesson_image" 
                                                accept=".jpg, .jpeg, .png">
                                            <label for="file" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.intro_video'):</label>
                                <div class="card">
                                    <div class="embed-responsive embed-responsive-16by9">
                                        <iframe class="embed-responsive-item no-video lesson-video" id="iframe_lesson_intro_video" 
                                            src="" allowfullscreen=""></iframe>
                                    </div>
                                    <div class="card-body">
                                        <label class="form-label">@lang('labels.backend.sidebar.url')</label>
                                        <input type="text" class="form-control" id="lesson_intro_video" name="lesson_intro_video" value="" 
                                            data-video-preview="#iframe_lesson_intro_video"
                                            placeholder="@lang('labels.backend.sidebar.url_placeholder')">
                                        <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.download_file')</label>
                                <div class="card">                                    
                                    <div class="card-body">
                                        <div class="custom-file">
                                            <input type="file" id="lesson_file_download" name="lesson_file_download" class="custom-file-input">
                                            <label for="file" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                                        </div>
                                        <small class="form-text text-muted">@lang('labels.backend.general.choose_file_note')</small>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">@lang('labels.backend.buttons.close')</button>
                <button type="button" class="btn btn-primary" id="btn_save_lesson" >@lang('labels.backend.buttons.save')</button>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
$(document).ready(function() {

    var course_id = '';
    var lesson_step = 1;
    var lesson_modal = 'new';
    var lesson_current = '';
    var $lesson_contents = $('#lesson_contents');
    var lesson_id = '';

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

    // Init Quill Editor for Course description
    var course_quill = new Quill('#course_editor', {
        theme: 'snow',
        placeholder: "@lang('labels.backend.courses.quil.course_description')",
        modules: {
            toolbar: toolbarOptions
        }
    });

    // Slug Generate
    $('input[name="title"]').on('focusout', function(e) {

        if($('input[name="slug"]').val() == '') {
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.slug') }}",
                data: {title: $(this).val()},
                success: function(res) {
                    $('input[name="slug"]').val(res.slug);
                }
            });
        }
    });

    // Multiselect for Tags
    $('#course_tags').select2({ tags: true });

    // Timezone
    $('select[name="timezone"]').timezones();
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();

    // Single Select for category
    $('select[name="category"]').select2();
    $('select[name="category"]').on('change', function(e) {

        if ($(this).val() != '') {
            $(this).removeClass('is-invalid');
        }
        
        $.ajax({
            method: 'GET',
            url: '/dashboard/get/levels/' + $(this).val(),
            success: function(res) {
                if(res.success) {
                    $('select[name="level"]').html($(res.levels)).select2();
                } else {
                    console.log(res.message);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    });

    // Check course title
    $('input[name="title"]').on('blur', (e) => {
        let course_title = $(e.target).val();
        if(new RegExp("([a-zA-Z0-9]+://)?([a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?([a-zA-Z0-9.-]+\\.[A-Za-z]{2,4})(:[0-9]+)?(/.*)?").test(course_title)) {
            $(e.target).addClass('is-invalid');
            swal('Error!', 'Title can not added URL', 'error');
        }
    });

    // Prices
    $('.options').on('change', 'input[type="checkbox"]', function(e) {
        if($(this).prop('checked')) {
            $('.options').find('div[for="' + $(this).attr('id') + '"]').removeClass('d-none');
            $('.options').find('div[for="' + $(this).attr('id') + '"]').find('input').attr('tute-no-empty', 'tute-no-empty');
        } else {
            $('.options').find('div[for="' + $(this).attr('id') + '"]').addClass('d-none');
            $('.options').find('div[for="' + $(this).attr('id') + '"]').find('input').removeAttr('tute-no-empty');
        }
    });

    $('#lesson_contents').on('change', 'input.step-video', function(e) {
        target = $(this).closest('.card-body').find('iframe.lesson-video');
        display_iframe($(this).val(), target);
    });

    // When click add Lesson button course should be saved draft first
    $('#btn_add_lesson').on('click', function(e) {
        e.preventDefault();

        if($('#course_tags').val().length < 1) {
            swal('Warning!', 'Tag is required!', 'warning');
            return false;
        }

        if($('#chk_private').prop('checked') == false && $('#chk_group').prop('checked') == false) {
            swal('Warning!', 'Option is required!', 'warning');
            return false;
        }

        if(!checkValidForm($('#frm_course'))){
            return false;
        }

        if(course_id == '') {

            // Save draft by ajax
            $('#frm_course').ajaxSubmit({
                beforeSerialize: function($form, options) {
                    // Before form Serialized
                },
                beforeSubmit: function(formData, formObject, formOptions) {

                    // Append quill data
                    formData.push({
                        name: 'course_description',
                        type: 'text',
                        value: course_quill.root.innerHTML
                    });
                },
                success: function(res) {
                    if(res.success) {
                        course_id = res.course_id;
                        $('#save_status .draft').text('check');
                        $('#modal_lesson').modal('toggle');
                    } else {
                        swal('Warning!', res.message, 'warning');

                        if (res.action && res.action === 'title') {
                            $("#frm_course").find("input[name=title]").addClass('is-invalid');
                            $("#frm_course").find("input[name=title]").focus();
                        }

                        if (res.action && res.action === 'slug') {
                            $("#frm_course").find("input[name=slug]").addClass('is-invalid');
                            $("#frm_course").find("input[name=slug]").focus();
                        }
                    }
                }
            });
        } else {
            if(lesson_current != 'new') {
                init_lesson_modal();
            }
            lesson_modal = 'new';
            $('#modal_lesson').modal('toggle');
        }
    });

    // Lesson Edit
    $('#parent').on('click', 'a.btn-edit', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');
        lesson_modal = 'edit';
        lesson_id = $(this).closest('.accordion__item').attr('lesson-id');

        // Current Lesson
        if(lesson_current == lesson_id) {
            $('#modal_lesson').modal('toggle');
            return false;
        }

        init_lesson_modal();

        // Get new lesson information
        $.ajax({
            method: 'GET',
            url: url,
            success: function(res) {

                if (res.success) {

                    // Set Lesson Modal Contents
                    $('#frm_lesson').find('input[name="lesson_title"]').val(res.lesson.title);
                    $('#frm_lesson').find('textarea[name="lesson_short_description"]').val(res.lesson.short_text);

                    if (res.lesson.image != '')
                        $('#display_lesson_image').attr('src', '/storage/uploads/' + res.lesson.image);
                    else
                        $('#display_lesson_image').attr('src',
                            "{{asset('/assets/img/no-image.jpg')}}");

                            if (res.lesson.video != null) {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val(res.lesson.video).change();
                    } else {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').addClass('no-video');
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val('');
                        $('#frm_lesson').find('iframe.lesson-video').attr('src', '');
                    }

                    if(res.steps.length > 0) {

                        var lesson_contents = $('#lesson_contents');

                        $.each(res.steps, function(idx, item) {

                            var ele_sep = `<div class="page-separator">
                                                <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                                            </div>`;

                            if(item.type == 'text') {
                                var ele = `<div class="form-group step" section-type="text">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">@lang('labels.backend.courses.step.full_text'):</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('labels.backend.courses.step.title'):</label>
                                                        <input type="text" class="form-control" name="lesson_description_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="@lang('labels.backend.courses.step_title')">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('labels.backend.courses.step.content'):</label>
                                                        <div style="min-height: 200px;" id="lesson_editor__` + lesson_step + `" class="mb-0">`+ item.text +`</div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('labels.backend.courses.step.duration_mins'):</label>
                                                        <input type="number" class="form-control" name="lesson_description_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">@lang('labels.backend.courses.step_duration')</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'video') {

                                var ifrm_video = '<iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""></iframe>';

                                var ele = `<div class="form-group step" section-type="video">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">@lang('labels.backend.courses.step.full_video'):</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('labels.backend.courses.step.title'):</label>
                                                        <input type="text" class="form-control" name="lesson_video_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="@lang('labels.backend.courses.step.video_title')">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('labels.backend.courses.step.video'):</label>
                                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                                            ` + ifrm_video + `
                                                        </div>
                                                        <label class="form-label">@lang('labels.backend.courses.step.url')</label>
                                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" value="" placeholder="Enter Video URL">
                                                        <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">@lang('labels.backend.courses.step.duration_mins'):</label>
                                                        <input type="number" class="form-control" name="lesson_video_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">@lang('labels.backend.courses.step_duration')</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'document') {
                                var ele = `<div class="form-group step" section-type="document" data-step-id="`+ item.id +`">
                                        ${ele_sep}
                                        <div class="card">
                                            <div class="card-header">
                                                <label class="form-label mb-0">Test:</label>
                                                <button type="button" class="close">
                                                    <span aria-hidden="true">×</span>
                                                </button>
                                            </div>
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label class="form-label">Title:</label>
                                                    <input type="text" class="form-control" name="document[${lesson_step}][title][]" 
                                                        value="${item.title}" placeholder="title for test step">
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Upload Document:</label>
                                                    <div class="custom-file">
                                                        <input type="file" id="document_file__${lesson_step}" name="document[${lesson_step}][content][]" 
                                                            value="${item.document}"
                                                            class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file="">
                                                        <label for="document_file__${lesson_step}" class="custom-file-label">${item.document}</label>
                                                        <input type="hidden" name="document[${lesson_step}][id][]" value="${item.id}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-label">Duration (minutes):</label>
                                                    <input type="number" class="form-control" name="document[${lesson_step}][duration][]" 
                                                        value="${item.duration}" placeholder="15">
                                                    <small class="form-text text-muted">Time duration for this step</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                            }

                            if(item.type == 'test') {
                                var ele = `<div class="form-group step" section-type="test">
                                        `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Test:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Title:</label>
                                                    <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for test step">
                                                    <input type="hidden" name="test__`+ lesson_step +`" value="1">
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            lesson_contents.append($(ele));
                            lesson_step++;
                        });

                        var editors = lesson_contents.find('div[id*="lesson_editor__"]');
                        $.each(editors, function(idx, item) {
                            var id = $(item).attr('id');
                            var step = id.slice(id.indexOf('__'));
                            var quill_editor = new Quill('#' + id, {
                                theme: 'snow',
                                placeholder: 'Lesson description',
                                modules: {
                                    toolbar: toolbarOptions
                                }
                            });
                        });
                    }

                    lesson_step = lesson_step;
                    lesson_current = res.lesson.id;
                    $('#modal_lesson').modal('toggle');
                }
            }
        });
    });

    $('#btn_save_course').on('click', function(e) {
        e.preventDefault();
        store_course('draft');
    });

    $('#btn_publish_course').on('click', function(e) {
        e.preventDefault();
        store_course('pending');
    });

    // Add steps
    $('#lesson_add_step').on('click', 'a.dropdown-item', function(e) {

        var ele_sep = `<div class="page-separator">
                            <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                        </div>`;

        var ele_text = `<div class="form-group step" section-type="text">
                            ${ele_sep}
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">@lang('labels.backend.courses.step.full_text'):</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.courses.step.title'):</label>
                                        <input type="text" class="form-control" name="text[${lesson_step}][title][]" 
                                            value="" placeholder="@lang('labels.backend.courses.step_title')">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.courses.step.content'):</label>
                                        <div style="min-height: 200px;" id="text_content__${lesson_step}" class="mb-0"></div>
                                        <textarea name="text[${lesson_step}][content][]" style="display: none;"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.courses.step.duration_mins'):</label>
                                        <input type="number" class="form-control" name="text[${lesson_step}][duration][]" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">@lang('labels.backend.courses.step_duration')</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_video = `<div class="form-group step" section-type="video">
                            ${ele_sep}
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">@lang('labels.backend.courses.step.full_video'):</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.courses.step.title'):</label>
                                        <input type="text" class="form-control" name="video[${lesson_step}][title][]" 
                                            value="" placeholder="@lang('labels.backend.courses.step.video_title')">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.courses.step.video'):</label>
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""
                                                id="iframe_${lesson_step}"></iframe>
                                        </div>
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control step-video" name="video[${lesson_step}][content][]" 
                                            value="" placeholder="Enter Video URL" data-video-preview="#iframe_${lesson_step}">
                                        <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.courses.step.duration_mins'):</label>
                                        <input type="number" class="form-control" name="video[${lesson_step}][duration][]" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">@lang('labels.backend.courses.step_duration')</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_document = `<div class="form-group step" section-type="document">
                                ${ele_sep}
                                <div class="card">
                                    <div class="card-header">
                                        <label class="form-label mb-0">Test:</label>
                                        <button type="button" class="close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label class="form-label">Title:</label>
                                            <input type="text" class="form-control" name="document[${lesson_step}][title][]" 
                                                value="" placeholder="title for test step">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Upload Document:</label>
                                            <div class="custom-file">
                                                <input type="file" id="document_file__${lesson_step}" name="document[${lesson_step}][content][]" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file="">
                                                <label for="document_file__${lesson_step}" class="custom-file-label">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Duration (minutes):</label>
                                            <input type="number" class="form-control" name="document[${lesson_step}][duration][]" value="15" placeholder="15">
                                            <small class="form-text text-muted">Time duration for this step</small>
                                        </div>
                                    </div>
                                </div>
                            </div>`;

        var ele_test = `<div class="form-group step" section-type="test">
                        `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Test:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">Title:</label>
                                    <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                            value="" placeholder="title for test step">
                                    <input type="hidden" name="test__`+ lesson_step +`" value="1">
                                </div>
                            </div>
                        </div>`;

        var type = $(this).attr('section-type');

        switch(type) {
            case 'text':
                $lesson_contents.append($(ele_text));

                var lesson_quill = new Quill(`#text_content__${lesson_step}`, {
                    theme: 'snow',
                    placeholder: 'Lesson description',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });
            break;

            case 'video':
                $lesson_contents.append($(ele_video));
            break;

            case 'document':
                $lesson_contents.append($(ele_document));
            break;

            case 'quiz':
                $lesson_contents.append($(ele_quiz));
            break;

            case 'test':
                $lesson_contents.append($(ele_test));
            break;
        }

        lesson_step++;
    });

    $('#lesson_contents').on('click', 'button.close', function(e) {
        $(this).closest('.form-group').remove();

        // Adjust Steps:
        var steps = $('#lesson_contents').find('div.step');
        $.each(steps, function(idx, item) {
            idx++;
            $(item).find('.page-separator__text').text('Step: ' + idx);
            status.lesson_step = idx;
        });
    });

    // Adding New Lesson
    $('#btn_save_lesson').on('click', function(e) {

        e.preventDefault();

        $('#frm_lesson').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                var editors = formObject.find('div[id*="text_content__"]');
                $.each(editors, function(idx, item) {
                    var id = $(item).attr('id');
                    var step = id.slice(id.indexOf('__')).slice(2);

                    var lesson_editor = new Quill('#' + id);

                    formData.push({
                        name: `text[${step}][content][]`,
                        type: 'text',
                        value: lesson_editor.root.innerHTML
                    });
                });

                // Append Course ID
                formData.push({
                    name: 'course_id',
                    type: 'int',
                    value: course_id
                });

                formData.push({
                    name: 'action',
                    type: 'text',
                    value: lesson_modal
                });

                if(lesson_modal == 'edit') {
                    formData.push({
                        name: 'lesson_id',
                        type: 'int',
                        value: lesson_id
                    });
                }
            },
            beforeSend: function() {
                // console.log('Before Send');
            },
            uploadProgress: function(event, position, total, percentComplete) {
                // console.log(percentComplete);
            },
            success: function(res) {

                if(res.success) {
                    if(res.action == 'new') {
                        var lesson_html = `
                            <div class="accordion__item" lesson-id="`+ res.lesson.id +`">
                                <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                    data-target="#lesson-toc-` + res.lesson.id + `" data-parent="#parent">
                                    <span class="flex">` + res.lesson.position + `. ` + res.lesson.title + `</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu collapse" id="lesson-toc-` + res.lesson.id + `">
                                    <div class="accordion__menu-link">
                                        <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                        <a class="flex" href="#">` + res.lesson.short_text.slice(0, 60) + `</a>
                                        <span class="text-muted">Just Now</span>
                                        <span class="btn-actions">
                                            <a href="/dashboard/lessons/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-preview">
                                                <i class="material-icons">remove_red_eye</i>
                                            </a>
                                            <a href="/dashboard/lessons/lesson/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="/dashboard/lessons/delete/`+ res.lesson.id +`" class="btn btn-outline-secondary btn-sm btn-delete">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#parent').append($(lesson_html));
                        lesson_current = res.lesson.id;
                        localStorage.setItem('steps__' + res.lesson_id, lesson_step);
                    }
                    
                    $('#modal_lesson').modal('toggle');

                } else {
                    swal('Warning!', res.message, 'warning');
                }
            }
        });
    });

    $('input[type="number"]').on('keypress', function(e) {
        if(e.which == 45) {
            return false;
        }
    });

    function store_course(action) {

        if($('#course_tags').val().length < 1) {
            swal('Warning!', 'Tag is required!', 'warning');
            return false;
        }

        if($('#chk_private').prop('checked') == false && $('#chk_group').prop('checked') == false) {
            swal('Warning!', 'Option is required!', 'warning');
            return false;
        }

        if(!checkValidForm($('#frm_course'))){
            return false;
        }

        // Check form is valid or not
        if ($('#frm_course').find('.is-invalid').length > 0) {
            swal('Error!', 'Please fix invalid form', 'error');
            return false;
        }

        if(action == 'draft') {
            btnLoading($('#btn_save_course'), true);
        }

        if(action == 'pending') {
            btnLoading($('#btn_publish_course'), true);
        }

        $('#frm_course').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                var course_description = course_quill.root.innerHTML;

                // Append Quill Description
                formData.push({
                    name: 'course_description',
                    type: 'text',
                    value: course_description
                });

                formData.push({
                    name: 'action',
                    type: 'string',
                    value: action
                });

                formData.push({
                    name: 'course_id',
                    type: 'string',
                    value: course_id
                });
            },
            success: function(res) {
                if(res.success) {
                    swal({
                        title: "Successfully Stored",
                        text: "It will redirected to Editor",
                        type: 'success',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        dangerMode: false,

                    }, function(val) {
                        if (val) {
                            // var url = '/dashboard/courses/' + res.course_id + '/edit';
                            var url = "{{ route('admin.courses.index') }}";
                            window.location.href = url;
                        }
                    });
                } else {
                    
                    swal('Warning!', res.message, 'warning');

                    if (res.action && res.action === 'title') {
                        $("#frm_course").find("input[name=title]").addClass('is-invalid');
                        $("#frm_course").find("input[name=title]").focus();
                    }

                    if (res.action && res.action === 'slug') {
                        $("#frm_course").find("input[name=slug]").addClass('is-invalid');
                        $("#frm_course").find("input[name=slug]").focus();
                    }

                    if (res.action && res.action === 'short_description') {
                        $("#frm_course").find("input[name=short_description]").addClass('is-invalid');
                        $("#frm_course").find("input[name=short_description]").focus();
                    }

                    if (res.action && res.action === 'course_image') {
                        $("#frm_course").find("input[name=course_image]").addClass('is-invalid');
                        $("#frm_course").find("input[name=course_image]").focus();
                    }
                }

                if(action == 'draft') {
                    btnLoading($('#btn_save_course'), false);
                }

                if(action == 'pending') {
                    btnLoading($('#btn_publish_course'), false);
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    }

    function init_lesson_modal() {
        lesson_step = 0;
        lesson_current = 'new';
        $('#frm_lesson').find('input[name="lesson_title"]').val('');
        $('#frm_lesson').find('textarea').val('');
        $('#frm_lesson').find('select').val('').change();
        $('#display_lesson_image').attr('src', "{{asset('/assets/img/no-image.jpg')}}");
        $('#lesson_contents').html('');
    }
});
</script>

@endpush

@endsection