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

.select2-container {
    display: block;
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
                    <h2 class="mb-0">@lang('labels.backend.orders.edit')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">@lang('labels.backend.orders.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.orders.edit')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.courses.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                        <button type="button" id="btn_save_course" class="btn btn-accent"> @lang('labels.backend.buttons.save') </button>
                </div>
            </div>

            {{-- <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.schedule') }}" class="btn btn-outline-secondary">@lang('labels.backend.orders.set_schedule')</a>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.courses.update', $course->id], 'files' => true, 'id'
            => 'frm_course']) !!}

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.orders.edit')</div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <label class="form-label">@lang('labels.backend.orders.fields.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.orders.fields.title')" 
                            {{-- value="{{ $course->title }}"  --}}
                            tute-no-empty>
                    </div>

                    <label class="form-label">Orderer</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                            placeholder="slug"
                             {{-- value="{{ $course->slug }}"  --}}
                             tute-no-empty>
                            @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
                                                data-toggle="flatpickr" 
                                                {{-- value="{{ $course->start_date }}" --}}
                                                >
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('labels.backend.sidebar.end_date'):</label>
                                            <input name="end_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" 
                                                {{-- value="{{ $course->end_date }}" --}}
                                                >
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.date_note')</small>
                            </div>

                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.timezone')</label>
                                <select name="timezone" class="form-control"></select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_timezone')</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label">@lang('labels.backend.sidebar.extras')</label>
                        <select name="tags[]" id="course_tags" multiple="multiple" class="form-control">
                            {{-- @foreach($tags as $tag) --}}
                            {{-- @php $course_tags = (!empty($course->tags)) ? json_decode($course->tags) : []; --}}
                            {{-- @endphp --}}
                            <option>Extras</option>
                            {{-- @endforeach --}}
                        </select>
                        <small class="form-text text-muted">@lang('labels.backend.sidebar.select_tags')</small>
                    </div>

                    <label class="form-label">@lang('labels.backend.orders.fields.summary')</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="Short description">Short description</textarea>
                        <small class="form-text text-muted">@lang('labels.backend.orders.fields.description_note')</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">@lang('labels.backend.orders.fields.order_detail')</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px;" id="course_editor" class="mb-0">Detail description</div>
                        <small class="form-text text-muted">@lang('labels.backend.orders.fields.about_course_note')</small>

                        <textarea id="description" style="display:none;">description</textarea>
                    </div>
                </div>

                <!-- Side bar for information -->
                <div class="col-md-4">
                    <label class="form-label">@lang('labels.backend.general.information')</label>
                    <div class="card">
                        <div class="card-body">
                            <!-- Set Category -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.academic_level')</label>
                                <select name="category" class="form-control custom-select" data-toggle="select">
                                    {{-- @foreach ($parentCategories as $category) --}}

                                    <option value=""></option>
                                    {{-- @if ($category->children()->count() > 0 ) --}}
                                    {{-- ?php $space = ''; ?> --}}
                                    {{-- @include(
                                    'backend.category.sub.option',
                                    ['category' => $category, 'space' => $space, 'selected' => $course->category_id]
                                    )
                                    @endif
                                    @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.subject')</label>
                                <select name="category" class="form-control custom-select" data-toggle="select">
                                    {{-- @foreach ($parentCategories as $category) --}}
                                        <option value=""></option>
                                        {{-- @if ($category->children()->count() > 0 )
                                            ?php $space = ''; ?>
                                        @include(
                                        'backend.category.sub.option',
                                        ['category' => $category, 'space' => $space, 'selected' => $course->category_id]
                                        )
                                        @endif
                                    @endforeach --}}
                                </select>
                            </div>

                            <!-- Set Level -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.writer_level')</label>
                                <select name="level" class="form-control">
                                    {{-- @foreach($levels as $level) --}}
                                    <option value=""></option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>
                            
                            <label class="form-label">@lang('labels.backend.sidebar.page')</label>
                            <div class="form-group mb-24pt">
                                <input type="number" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                placeholder="Page Number" value=" " tute-no-empty>
                                @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.spacing')</label>
                                <select name="level" class="form-control">
                                    {{-- @foreach($levels as $level) --}}
                                    <option value="">Single</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.formating')</label>
                                <select name="level" class="form-control">
                                    {{-- @foreach($levels as $level) --}}
                                    <option value="">ATA</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <label class="form-label">@lang('labels.backend.sidebar.promo_code')</label>
                            <div class="form-group mb-24pt">
                                <input type="number" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                placeholder="Input Page Number" value=" " tute-no-empty>
                                @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <label class="form-label">@lang('labels.backend.sidebar.number_source')</label>
                            <div class="form-group mb-24pt">
                                <input type="number" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                placeholder="Input Number of Source" value=" " tute-no-empty>
                                @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label">@lang('labels.backend.sidebar.progress')</label>
                                <div class="progress progress-md">
                                    <div class="progress-bar progress-bar-success" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 90%;" role="progressbar">
                                        <span class="sr-only">70%</span>
                                    </div>
                                </div>
                                <small>90% Completed</small>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.attach_files')</div>
                    </div>

                    <div class="card">
                        <img id="display_course_image" 
                        {{-- src="@if(!empty($course->course_image))  --}}
                                {{-- {{asset('/storage/uploads')}}/{{ $course->course_image }} --}}
                                 {{-- @else  --}}
                                    {{-- {{asset('/assets/img/no-image.jpg')}} --}}
                                 {{-- @endif" id="img_course_image" width="100%" alt="" --}}
                                 >
                        <div class="card-body">
                        <div class="mb-3">
                            <a href="#" target="_blank">Essay.pdf</a> 
                        </div>
                        <div class="mb-3">
                            <a href="#" target="_blank">Book.pdf</a> 
                        </div>
                            <div class="custom-file">
                                <input type="file" name="course_image" id="course_file_image" class="custom-file-input"
                                    data-preview="#display_course_image" accept="*.*">
                                <label for="course_file_image" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@endsection

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

<!-- jQuery Mask Plugin -->
<script src="{{ asset('assets/js/jquery.mask.min.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

// Init Elements
$(function() {

    // Global Variable for this page
    var course_quill;
    var status = {
        lesson_id: '',
        lesson_modal: 'new',
        lesson_step: 0,
        lesson_current: ''
    };

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

    // Single Select for category
    $('select[name="category"]').select2();
    $('select[name="lesson_type"]').select2();

    // Multiselect for Tags
    $('#course_tags').select2({
        tags: true
    });

    // Single Select for Level
    $('select[name="level"]').select2();

    // Timezone
    $('select[name="timezone"]').timezones();
    @if(auth()->user()->hasRole('Administrator'))
    $('select[name="timezone"]').val('{{ $course->teachers->first()->timezone }}').change();
    @else
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').change();
    @endif

    // Check course title
    $('input[name="title"]').on('blur', (e) => {
        let course_title = $(e.target).val();
        if(new RegExp("([a-zA-Z0-9]+://)?([a-zA-Z0-9_]+:[a-zA-Z0-9_]+@)?([a-zA-Z0-9.-]+\\.[A-Za-z]{2,4})(:[0-9]+)?(/.*)?").test(course_title)) {
            $(e.target).addClass('is-invalid');
            swal('Error!', 'Title can not added URL', 'error');
        }
    });

    // Load level when category changed
    $('select[name="category"]').on('change', function(e) {

        $.ajax({
            method: 'GET',
            url: '/dashboard/get/levels/' + $(this).val(),
            success: function(res) {
                if (res.success) {
                    $('select[name="level"]').html($(res.levels)).select2();
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    // Prices
    $('.options').on('change', 'input[type="checkbox"]', function(e) {
        if($(this).prop('checked')) {
            $('.options').find('div[for="' + $(this).attr('id') + '"]').removeClass('d-none');
            $('.options').find('input').attr('required', 'required');
        } else {
            $('.options').find('div[for="' + $(this).attr('id') + '"]').addClass('d-none');
            $('.options').find('input').removeAttr('required');
        }
    });

    // Repeat course
    $('#chk_repeat').on('change', function(e) {
        var repeat_val = $(this).prop('checked') ? '1' : '0';
        $('input[name="repeat"]').val(repeat_val);
        var style = $(this).prop('checked') ? 'block' : 'none';
        $('div[for="chk_repeat"]').css('display', style);
    });

    $('#btn_save_course').on('click', function(e) {
        e.preventDefault();
        // save_course('draft');
        save_course('pending');
    });

    $('#btn_publish_course').on('click', function(e) {
        e.preventDefault();
        if('{!! auth()->user()->hasRole("Administrator") !!}') {
            save_course('publish');
        } else {
            save_course('pending');
        }
    });

    // Event when click save course button id="btn_save_course"
    function save_course(action) {

        // Check form is valid or not
        if ($('#frm_course').find('.is-invalid').length > 0) {
            swal('Error!', 'Please fix invalid form', 'error');
            return false;
        }

        if(action == 'draft') {
            btnLoading($('#btn_save_course'), true);
        } else {
            btnLoading($('#btn_publish_course'), true);
        }

        $('#frm_course').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = course_quill.root.innerHTML;

                // Append Course ID
                formData.push({
                    name: 'course_description',
                    type: 'text',
                    value: content
                });

                formData.push({
                    name: 'action',
                    type: 'string',
                    value: action
                });
            },
            success: function(res) {

                if (res.success) {
                    swal("Success!", "Successfully Updated!", "success");
                } else {
                    swal("Error!", res.message, "error");

                    if (res.action && res.action === 'title') {
                        $("#frm_course").find("input[name=title]").addClass('is-invalid');
                        $("#frm_course").find("input[name=title]").focus();
                    }

                    if (res.action && res.action === 'slug') {
                        $("#frm_course").find("input[name=slug]").addClass('is-invalid');
                        $("#frm_course").find("input[name=slug]").focus();
                    }
                }

                if(action == 'draft') {
                    btnLoading($('#btn_save_course'), false);
                } else {
                    btnLoading($('#btn_publish_course'), false);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                swal("Error!", errMsg, "error");
            }
        });
    }

  
});

</script>

@endpush