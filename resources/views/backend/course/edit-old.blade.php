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
                    <h2 class="mb-0">@lang('labels.backend.courses.edit')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">@lang('labels.backend.courses.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.courses.edit')
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

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.schedule') }}" class="btn btn-outline-secondary">@lang('labels.backend.courses.set_schedule')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.courses.update', $course->id], 'files' => true, 'id'
            => 'frm_course']) !!}
            <div class="row">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.courses.edit')</div>
                    </div>

                    <label class="form-label">@lang('labels.backend.courses.fields.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.courses.fields.title')" value="{{ $course->title }}" tute-no-empty>
                    </div>

                    <label class="form-label">Slug</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                            placeholder="slug" value="{{ $course->slug }}" tute-no-empty>
                            @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                    </div>

                    <label class="form-label">@lang('labels.backend.courses.fields.description')</label>
                    <div class="form-group mb-24pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="Short description">{{ $course->short_description }}</textarea>
                        <small class="form-text text-muted">@lang('labels.backend.courses.fields.description_note')</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">@lang('labels.backend.courses.fields.about_course')</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px;" id="course_editor" class="mb-0">{!! $course->description !!}</div>
                        <small class="form-text text-muted">@lang('labels.backend.courses.fields.about_course_note')</small>

                        <textarea id="description" style="display:none;">{{ $course->description }}</textarea>
                    </div>

                    <!-- Lessons -->

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.general.lessons')</div>
                    </div>

                    <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent">

                        <!-- Lesson Items -->
                        @foreach($course->lessons as $lesson)
                        <div class="accordion__item" lesson-id="{{ $lesson->id }}">
                            <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                data-target="#lesson-toc-{{ $lesson->id }}" data-parent="#parent">
                                <span class="flex">{{ $lesson->position }}. {{ $lesson->title }}</span>
                                <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                            </a>
                            <div class="accordion__menu collapse" id="lesson-toc-{{ $lesson->id }}">
                                <div class="accordion__menu-link">
                                    <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                    <span class="flex">
                                        @php
                                        if (strlen($lesson->short_text) > 60)
                                        $description = mb_substr($lesson->short_text, 0, 60) . '...';
                                        else
                                        $description = $lesson->short_text;
                                        @endphp

                                        {{ $description }}
                                    </span>
                                    <span class="text-muted">
                                        {{ \Carbon\Carbon::createFromTimeStamp(strtotime($lesson->updated_at))->diffForHumans() }}
                                    </span>
                                    <span class="btn-actions">
                                        @if($lesson->steps->count() > 0)
                                        <a href="{{ route('lessons.show', [$lesson->course->slug, $lesson->slug, $lesson->steps[0]->step]) }}"
                                            class="btn btn-outline-secondary btn-sm btn-preview" target="_blank">
                                            <i class="material-icons">remove_red_eye</i>
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.lesson.getById', $lesson->id) }}"
                                            class="btn btn-outline-secondary btn-sm btn-edit">
                                            <i class="material-icons">edit</i>
                                        </a>
                                        <a href="{{ route('admin.lessons.delete', $lesson->id) }}"
                                            class="btn btn-outline-secondary btn-sm btn-delete">
                                            <i class="material-icons">delete</i>
                                        </a>
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <button type="button" id="btn_add_lesson"
                        class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">+ @lang('labels.backend.courses.add_lesson')</button>
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
                            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-info">@lang('labels.backend.buttons.preview')</a>
                        </div>

                        <div class="list-group list-group-flush">
                            @if($course->published == 2)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.general.pending')</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            @endif
                            @if($course->published == 0)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.general.draft_saved')</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            @endif
                            @if($course->published == 1)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.general.published')</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            @endif
                        </div>

                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.general.information')</div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                        
                            <!-- Set Category -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.category')</label>
                                <select name="category" class="form-control custom-select" data-toggle="select">
                                    @foreach ($parentCategories as $category)

                                    <option value="{{ $category->id }}" @if($course->category_id == $category->id)
                                        selected @endif>{{ $category->name }}
                                    </option>
                                    @if ($category->children()->count() > 0 )
                                    <?php $space = ''; ?>
                                    @include(
                                    'backend.category.sub.option',
                                    ['category' => $category, 'space' => $space, 'selected' => $course->category_id]
                                    )
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
                                    <option value="{{ $level->id }}" @if($course->level_id == $level->id) selected
                                        @endif>{{ $level->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_level')</small>
                            </div>

                            <!-- Set Tags -->
                            <div class="form-group mb-0">
                                <label class="form-label">@lang('labels.backend.sidebar.tags')</label>
                                <select name="tags[]" id="course_tags" multiple="multiple" class="form-control">
                                    @foreach($tags as $tag)
                                    @php $course_tags = (!empty($course->tags)) ? json_decode($course->tags) : [];
                                    @endphp
                                    <option @if(in_array($tag->name, $course_tags)) selected @endif>{{ $tag->name }}
                                    </option>
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
                                    <input id="chk_group" type="checkbox" @if(!empty($course->group_price)) checked="" @endif class="custom-control-input">
                                    <label for="chk_group" class="custom-control-label form-label">@lang('labels.backend.sidebar.group_course')</label>
                                </div>
                            </div>

                            <!-- Set Max number in case of group course -->
                            <div class="form-group @if(empty($course->group_price)) d-none @endif" for="chk_group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <label class="form-label">@lang('labels.backend.sidebar.min_students'):</label>
                                        <input type="number" name="min" class="form-control" min="1"
                                            value="{{ $course->min }}" placeholder="5">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <label class="form-label">@lang('labels.backend.sidebar.max_students'):</label>
                                        <input type="number" name="max" class="form-control" min="1"
                                            value="{{ $course->max }}" placeholder="30">
                                    </div>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.group_note')</small>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group @if(empty($course->group_price)) d-none @endif" for="chk_group">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">@lang('labels.backend.sidebar.price')({{ getCurrency(config('app.currency'))['symbol'] }})</span></span>
                                    <input type="number" name="group_price" class="form-control"
                                        value="{{ $course->group_price }}">
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.price_note')</small>
                            </div>

                            <div class="page-separator"></div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_private" type="checkbox" @if(!empty($course->private_price)) checked="" @endif class="custom-control-input">
                                    <label for="chk_private" class="custom-control-label form-label">@lang('labels.backend.sidebar.private_course')</label>
                                </div>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group @if(empty($course->private_price)) d-none @endif" for="chk_private">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">@lang('labels.backend.sidebar.price')({{ getCurrency(config('app.currency'))['symbol'] }})</span></span>
                                    <input type="number" name="private_price" class="form-control"
                                        value="{{ $course->private_price }}">
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
                                                data-toggle="flatpickr" value="{{ $course->start_date }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('labels.backend.sidebar.end_date'):</label>
                                            <input name="end_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="{{ $course->end_date }}">
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
                                    <input id="chk_repeat" type="checkbox" class="custom-control-input"
                                        @if($course->repeat) checked="true" @endif>
                                    <label for="chk_repeat" class="custom-control-label form-label">@lang('labels.backend.sidebar.repeat')</label>
                                    <input type="hidden" name="repeat" value="{{ $course->repeat }}">
                                </div>
                            </div>

                            <div class="form-group" for="chk_repeat">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <input type="number" name="repeat_value" value="{{ $course->repeat_value }}"
                                            class="form-control" min="1">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <select id="custom-select" name="repeat_type"
                                            class="form-control custom-select">
                                            <option value="week" @if($course->repeat_type == 'week') selected
                                                @endif>@lang('labels.backend.sidebar.week')</option>
                                            <option value="month" @if($course->repeat_type == 'month') selected
                                                @endif>@lang('labels.backend.sidebar.month')</option>
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
                        <img id="display_course_image" src="@if(!empty($course->course_image)) 
                                    {{asset('/storage/uploads')}}/{{ $course->course_image }}
                                 @else 
                                    {{asset('/assets/img/no-image.jpg')}}
                                 @endif" id="img_course_image" width="100%" alt="">
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
                        @if(!empty($course->mediaVideo))
                        <div class="embed-responsive embed-responsive-16by9">
                            <?php
                                $embed = Embed::make($course->mediaVideo->url)->parseUrl();
                                if($embed) {
                                    $embed->setAttribute([
                                        'id'=>'iframe_course_video',
                                        'class'=>'embed-responsive-item',
                                        'allowfullscreen' => ''
                                    ]);
                                }
                            ?>
                            @if ($embed)
                            {!! $embed->getHtml() !!}
                            @endif
                        </div>
                        <div class="card-body">
                            <label class="form-label">@lang('labels.backend.sidebar.url')</label>
                            <input type="text" class="form-control" name="course_video" id="course_video_url"
                                data-video-preview="#iframe_course_video"
                                value="{{ $course->mediaVideo->url }}" placeholder="@lang('labels.backend.sidebar.url_placeholder')">
                            <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                        </div>
                        @else
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_course_video" src=""
                                allowfullscreen="">
                            </iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">@lang('labels.backend.sidebar.url')</label>
                            <input type="text" class="form-control" name="course_video" id="course_video_url" value=""
                                data-video-preview="#iframe_course_video"
                                placeholder="@lang('labels.backend.sidebar.url_placeholder')">
                            <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                        </div>
                        @endif
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
                <h5 class="modal-title">Lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true, 'id' =>'frm_lesson']) !!}

                <div class="row">
                    <div class="col-12 col-md-8 mb-3">
                        <div class="form-group">
                            <label class="form-label">Title:</label>
                            <input type="text" name="lesson_title"
                                class="form-control form-control-lg @error('lesson_title') is-invalid @enderror"
                                placeholder="@lang('labels.backend.courses.fields.title')" value="">
                            @error('lesson_title')
                            <div class="invalid-feedback">Title is required field.</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label">Short Description:</label>
                            <textarea class="form-control" name="lesson_short_description" rows="3"></textarea>
                        </div>

                        <div class="form-group" id="lesson_contents"></div>

                        <div class="form-group">
                            <div class="flex" style="max-width: 100%">
                                <div class="btn-group" id="lesson_add_step" style="width: 100%;">
                                    <button type="button" class="btn btn-block btn-outline-secondary dropdown-toggle"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">+ Add Content
                                    </button>
                                    <div class="dropdown-menu step-menu" style="width: 100%;">
                                        <a class="dropdown-item" href="javascript:void(0)" section-type="video">Video
                                            Section</a>
                                        <a class="dropdown-item" href="javascript:void(0)" section-type="text">Full Text
                                            Section</a>
                                        <a class="dropdown-item" href="javascript:void(0)" section-type="document">Document
                                            Section</a>
                                        <!-- <a class="dropdown-item" href="javascript:void(0)" section-type="test">Test
                                            Section</a> -->
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
                            <label class="form-label">Thumbnail</label>
                            <div class="card">
                                <img src="{{asset('/assets/img/no-image.jpg')}}" width="100%"
                                    id="display_lesson_image" alt="">
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" id="lesson_file_image" name="lesson_file_image"
                                            class="custom-file-input" data-preview="#display_lesson_image" 
                                            accept=".jpg, .jpeg, .png">
                                        <label for="file" class="custom-file-label">Choose file</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Introduce Video</label>
                            <div class="card">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <iframe class="embed-responsive-item no-video lesson-video"
                                        id="iframe_lesson_intro_video" src="" allowfullscreen=""></iframe>
                                </div>
                                <div class="card-body">
                                    <label class="form-label">URL</label>
                                    <input type="text" class="form-control" name="lesson_intro_video"
                                        data-video-preview="#iframe_lesson_intro_video"
                                        value="" placeholder="Enter Video URL">
                                    <small class="form-text text-muted">
                                        @lang('labels.backend.sidebar.video_url_note')
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div id="lesson_attachment_preview" class="form-group mb-24pt d-none">
                            <label class="form-label">@lang('labels.backend.assignments.edit.attached_document'):</label>
                            <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                                <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                    <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                </div>
                                <div class="flex">
                                    <a href="#" target="_blank">
                                        <div class="form-label mb-4pt">Download File</div>
                                        <p class="card-subtitle text-black-70">
                                            @lang('labels.backend.assignments.edit.attached_document_note')</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Download File</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="custom-file">
                                        <input type="file" id="lesson_file_download" name="lesson_file_download"
                                            class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                                        <label for="lesson_file_download" class="custom-file-label">Choose file</label>
                                    </div>
                                    <small class="form-text text-muted">Max file size is 5MB.</small>
                                </div>
                            </div>
                        </div>

                        <!-- hidden Informations for Lesson -->
                        <input type="hidden" name="lesson_full_description">
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                    </div>
                </div>

                {!! Form::close() !!}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_save_lesson">Save</button>
            </div>
        </div>
    </div>
</div>

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
    @if(auth()->user()->hasRole('Superadmin'))
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
        if('{!! auth()->user()->hasRole("Superadmin") !!}') {
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

    // Add New lesson
    $('#btn_add_lesson').on('click', function(e) {
        if(status.lesson_current != 'new') {
            init_lesson_modal();
        }
        status.lesson_modal = 'new';
        $('#modal_lesson').modal('toggle');
    });

    // Delete a Lesson
    $('.accordion').on('click', '.accordion__item a.btn-delete', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        var accordion_item = $(this).closest('div.accordion__item');

        swal({
            title: "Are you sure?",
            text: "This lesson will removed from this course",
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
                    url: url,
                    success: function(res) {
                        if (res.success) {
                            accordion_item.remove();
                        }
                    }
                });
            }
        });
    });

    // Click save in modal
    $('#btn_save_lesson').on('click', function(e) {

        e.preventDefault();

        $('#frm_lesson').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                var editors = formObject.find('div[id*="lesson_editor__"]');
                $.each(editors, function(idx, item) {
                    var id = $(item).attr('id');
                    var step = id.slice(id.indexOf('__'));

                    var lesson_editor = new Quill('#' + id);

                    formData.push({
                        name: 'lesson_description' + step,
                        type: 'text',
                        value: lesson_editor.root.innerHTML
                    });
                });

                formData.push({
                    name: 'action',
                    type: 'text',
                    value: status.lesson_modal
                });

                if(status.lesson_modal == 'edit') {
                    formData.push({
                        name: 'lesson_id',
                        type: 'int',
                        value: status.lesson_id
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

                if (res.success) {
                    if(res.action == 'new') {

                        let lesson_html = `
                            <div class="accordion__item" lesson-id="${res.lesson.id}">
                                <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                    data-target="#lesson-toc-${res.lesson.id}" data-parent="#parent">
                                    <span class="flex">${res.lesson.position}. ${res.lesson.title}</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu collapse" id="lesson-toc-${res.lesson.id}">
                                    <div class="accordion__menu-link">
                                        <i class="material-icons text-70 icon-16pt icon--left">drag_handle</i>
                                        <a class="flex" href="#">${res.lesson.short_text.slice(0, 60)}</a>
                                        <span class="text-muted">Just Now</span>
                                        <span class="btn-actions">
                                            <a href="/course/${res.course_slug}/${res.lesson.slug}/1"
                                                class="btn btn-outline-secondary btn-sm btn-preview" target="_blank">
                                                <i class="material-icons">remove_red_eye</i>
                                            </a>
                                            <a href="/dashboard/lessons/lesson/${res.lesson.id}" 
                                                class="btn btn-outline-secondary btn-sm btn-edit">
                                                <i class="material-icons">edit</i>
                                            </a>
                                            <a href="/dashboard/lessons/delete/${res.lesson.id}" 
                                                class="btn btn-outline-secondary btn-sm btn-delete">
                                                <i class="material-icons">delete</i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        `;

                        $('#parent').append($(lesson_html));

                        status.lesson_current = res.lesson.id;
                        localStorage.setItem('steps__' + res.lesson_id, status.lesson_step);
                    }
                    
                    $('#modal_lesson').modal('toggle');

                } else {
                    swal('Warning!', res.message, 'warning');
                }
            },
            error: function(err) {
                swal("Error!", err, "error");
            }
        });
    });

    // Lesson Modal Title Validation
    $('#frm_lesson').on('keyup', 'input[name="lesson_title"]', function() {
        $(this).removeClass('is-invalid');
        $('#frm_lesson').find('div.invalid-feedback').remove();
    });

    $('#chk_liveLesson').on('change', function(e) {

        if(status.lesson_modal == 'edit') {
            if($(this).prop('checked')) {
                var live_url = '{{ config("app.url") }}' + 'lesson/live/' + status.lesson_slug + '/' + status.lesson_id;
                $('div[for="dv_liveLesson').find('.text-muted').text(live_url);
                $('div[for="dv_liveLesson').find('a').attr('href', live_url);
                $('div[for="dv_liveLesson').css('display', 'block');
                $('input[name="live_lesson"]').val('1');
            } else {
                $('div[for="dv_liveLesson').css('display', 'none');
                $('input[name="live_lesson"]').val('0');
            }
        } else {
            if($(this).prop('checked')) {
                $('input[name="live_lesson"]').val('1');
            } else {
                $('input[name="live_lesson"]').val('0');
            }
        }
    });

    // Lesson Edit
    $('#parent').on('click', 'a.btn-edit', function(e){

        e.preventDefault();
        var url = $(this).attr('href');
        status.lesson_modal = 'edit';
        status.lesson_id = $(this).closest('.accordion__item').attr('lesson-id');

        // Current Lesson
        if(status.lesson_current == status.lesson_id) {
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
                    $('#frm_lesson').find('select[name="lesson_type"]').val(res.lesson.lesson_type).change();
                    
                    if (res.lesson.image != undefined && res.lesson.image != '') {
                        $('#display_lesson_image').attr('src',
                            '/storage/uploads/' + res.lesson.image);
                    } else {
                        $('#display_lesson_image').attr('src',
                            "{{asset('/assets/img/no-image.jpg')}}");
                    }

                    if (res.lesson.attachment != undefined && res.lesson.attachment != '') {
                        let file_name = res.lesson.attachment;
                        let extension = file_name.substr( (file_name.lastIndexOf('.') +1) );
                        if (extension == 'pdf') {
                            $('#lesson_attachment_preview').find('img').attr('src', "{{ asset('/images/pdf.png') }}");
                        } else {
                            $('#lesson_attachment_preview').find('img').attr('src', "{{ asset('/images/docx.png') }}");
                        }
                        $('#lesson_attachment_preview').find('a').attr('href', "{{ asset('/storage/attachments/') }}/" + file_name)
                        $('#lesson_attachment_preview').removeClass('d-none');
                    } else {
                        $('#lesson_attachment_preview').addClass('d-none');
                    }

                    if (res.lesson.video != null) {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val(res.lesson.video).change();
                    } else {
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').addClass('no-video');
                        $('#frm_lesson').find('input[name="lesson_intro_video"]').val('');
                        $('#frm_lesson').find('iframe.lesson-video').attr('src', '');
                    }

                    if(res.lesson.lesson_type == 1) {
                        $('#chk_liveLesson').prop('checked', true);
                        var live_url = '{{ config("app.url") }}' + 'lesson/live/' + res.lesson.slug + '/' + res.lesson.id;
                        $('div[for="dv_liveLesson').find('.text-muted').text(live_url);
                        $('div[for="dv_liveLesson').find('a').attr('href', live_url);
                        $('div[for="dv_liveLesson').css('display', 'block');
                        $('input[name="live_lesson"]').val('1');
                    } else {
                        $('#chk_liveLesson').prop('checked', false);
                        $('div[for="dv_liveLesson').find('.text-muted').text('');
                        $('div[for="dv_liveLesson').find('a').attr('href', '#');
                        $('div[for="dv_liveLesson').css('display', 'none');
                        $('input[name="live_lesson"]').val('0');
                    }

                    // add Steps
                    var lesson_step = 0;
                    if(res.steps.length > 0) {

                        var lesson_contents = $('#lesson_contents');

                        $.each(res.steps, function(idx, item) {

                            lesson_step = idx + 1;
                            var ele_sep = `<div class="page-separator">
                                                <div class="page-separator__text"> Step: ` + lesson_step + `</div>
                                            </div>`;

                            if(item.type == 'text') {
                                var ele = `<div class="form-group step" section-type="text" data-step-id="`+ item.id +`">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Full Text:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Title:</label>
                                                        <input type="text" class="form-control" name="lesson_description_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for step">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Content:</label>
                                                        <div style="min-height: 200px;" id="lesson_editor__` + lesson_step + `" class="mb-0">`+ item.text +`</div>
                                                        <input type="hidden" name="lesson_description_id__` + lesson_step + `" value="`+ item.id +`">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="lesson_description_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'video') {

                                var ifrm_video = '<iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen=""></iframe>';

                                if(item.video != '') {
                                    
                                    ifrm_video = `<iframe class="embed-responsive-item lesson-video" src=""
                                         allowfullscreen="" id="step_video_`+ lesson_step +`"></iframe>`;
                                }

                                var ele = `<div class="form-group step" section-type="video" data-step-id="`+ item.id +`">
                                            `+ ele_sep +`
                                            <div class="card">
                                                <div class="card-header">
                                                    <label class="form-label mb-0">Full Video:</label>
                                                    <button type="button" class="close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label class="form-label">Title:</label>
                                                        <input type="text" class="form-control" name="lesson_video_title__` + lesson_step + `" 
                                                            value="`+ item.title +`" placeholder="title for video step">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Video:</label>
                                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                                            ` + ifrm_video + `
                                                        </div>
                                                        <label class="form-label">URL</label>
                                                        <input type="text" class="form-control step-video" name="lesson_video__`+ lesson_step +`" 
                                                        value="` + item.video + `" placeholder="Enter Video URL"
                                                        data-video-preview="#step_video_`+ lesson_step +`">
                                                        <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                                                        <input type="hidden" name="lesson_video_id__` + lesson_step + `" value="`+ item.id +`">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="lesson_video_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            if(item.type == 'test') {
                                var ele = `<div class="form-group step" section-type="test" data-step-id="`+ item.id +`">
                                        `+ ele_sep +`
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
                                                        <input type="text" class="form-control" name="test_title__` + lesson_step + `" 
                                                                value="`+ item.title +`" placeholder="title for test step">
                                                        <input type="hidden" name="test_id__` + lesson_step + `" value="`+ item.id +`">
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Select:</label>
                                                        <select name="test__`+ lesson_step +`" class="form-control" data-selected="`+ item.test +`">
                                                            @foreach($course->tests as $test)
                                                            <option value="{{ $test->id }}">
                                                                {{ $test->title }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="form-label">Duration (minutes):</label>
                                                        <input type="number" class="form-control" name="test_duration__` + lesson_step + `" 
                                                            value="`+ item.duration +`" placeholder="15">
                                                        <small class="form-text text-muted">Time duration for this step</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>`;
                            }

                            lesson_contents.append($(ele));
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

                        var selects = lesson_contents.find('select[name*="test__"]');
                        $.each(selects, function(idx, item) {
                            var val = $(item).attr('data-selected');
                            $(this).val(val).change();
                        });

                        var step_videos = lesson_contents.find('input.step-video');
                        $.each(step_videos, function(idx, item) {
                            $(this).change();
                        });
                    }

                    status.lesson_step = lesson_step;
                    status.lesson_current = res.lesson.id;
                    status.lesson_slug = res.lesson.slug;
                    $('#modal_lesson').modal('toggle');
                }
            }
        });
    });

    $('#lesson_contents').on('click', 'button.close', function(e) {

        var step_ele = $(this).closest('.form-group');
        var step_id = step_ele.attr('data-step-id');

        $.ajax({
            method: 'get',
            url: '/dashboard/steps/delete/' + step_id,
            success: function(res) {

                step_ele.toggle( function() { 
                    
                    $(this).remove();

                    // Adjust Steps:
                    var steps = $('#lesson_contents').find('div.step');
                    $.each(steps, function(idx, item) {
                        idx++;
                        $(item).find('.page-separator__text').text('Step: ' + idx);
                        status.lesson_step = idx;
                    });
                });
            }
        });
    });

    // Add steps
    $('#lesson_add_step').on('click', 'a.dropdown-item', function(e) {

        status.lesson_step++;

        var ele_sep = `<div class="page-separator">
                            <div class="page-separator__text"> Step: ` + status.lesson_step + `</div>
                        </div>`;

        var ele_text = `<div class="form-group step" section-type="text">
                            `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Full Text:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="lesson_description_title__` + status.lesson_step + `" 
                                            value="" placeholder="title for step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Content:</label>
                                        <div style="min-height: 200px;" id="lesson_editor__` + status.lesson_step + `" class="mb-0"></div>
                                        <textarea name="lesson_description__` + status.lesson_step + `" style="display: none;"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_description_duration__` + status.lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var ele_video = `<div class="form-group step" section-type="video">
                            `+ ele_sep +`
                            <div class="card">
                                <div class="card-header">
                                    <label class="form-label mb-0">Full Video:</label>
                                    <button type="button" class="close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="lesson_video_title__` + status.lesson_step + `" 
                                            value="" placeholder="title for video step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Video:</label>
                                        <div class="embed-responsive embed-responsive-16by9 mb-2">
                                            <iframe class="embed-responsive-item no-video lesson-video" src="" allowfullscreen="" 
                                            id="iframe_`+ status.lesson_step +`"></iframe>
                                        </div>
                                        <label class="form-label">URL</label>
                                        <input type="text" class="form-control step-video" name="lesson_video__`+ status.lesson_step +`" 
                                        value="" placeholder="Enter Video URL" data-video-preview="#iframe_`+ status.lesson_step +`">
                                        <small class="form-text text-muted">@lang('labels.backend.sidebar.video_url_note')</small>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="lesson_video_duration__` + status.lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
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
                                            <input type="text" class="form-control" name="document_title__${status.lesson_step}" 
                                                value="" placeholder="title for test step">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Upload Document:</label>
                                            <div class="custom-file">
                                                <input type="file" id="document_file__${status.lesson_step}" name="document_file__${status.lesson_step}" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file="">
                                                <label for="document_file__${status.lesson_step}" class="custom-file-label">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Duration (minutes):</label>
                                            <input type="number" class="form-control" name="lesson_document_duration__` + status.lesson_step + `" 
                                                value="15" placeholder="15">
                                            <small class="form-text text-muted">Time duration for this step</small>
                                        </div>
                                    </div>
                                </div>
                            </div>`

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
                                    <div class="form-group">
                                        <label class="form-label">Title:</label>
                                        <input type="text" class="form-control" name="test_title__` + status.lesson_step + `" 
                                                value="" placeholder="title for test step">
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Select:</label>
                                        <select name="test__`+ status.lesson_step +`" class="form-control">
                                            @foreach($course->tests as $test)
                                            <option value="{{ $test->id }}">{{ $test->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label">Duration (minutes):</label>
                                        <input type="number" class="form-control" name="test_duration__` + status.lesson_step + `" 
                                            value="15" placeholder="15">
                                        <small class="form-text text-muted">Time duration for this step</small>
                                    </div>
                                </div>
                            </div>
                        </div>`;

        var type = $(this).attr('section-type');

        switch(type) {
            case 'text':
                $('#lesson_contents').append($(ele_text));

                // Init Quill Editor for Lesson Full description
                var lesson_quill = new Quill('#lesson_editor__' + status.lesson_step, {
                    theme: 'snow',
                    placeholder: 'Lesson description',
                    modules: {
                        toolbar: toolbarOptions
                    }
                });
                break;
            case 'video':
                $('#lesson_contents').append($(ele_video));
                break;
            case 'test':
                $('#lesson_contents').append($(ele_test));
                break;
        }
    });

    $('input[type="number"]').on('keypress', function(e) {
        if(e.which == 45) {
            return false;
        }
    });

    function init_lesson_modal() {
        status.lesson_step = 0;
        status.lesson_current = 'new';
        $('#frm_lesson').find('input[name="lesson_title"]').val('');
        $('#frm_lesson').find('textarea').val('');
        $('#display_lesson_image').attr('src', "{{asset('/assets/img/no-image.jpg')}}");
        $('#lesson_contents').html('');

        $('#chk_liveLesson').prop('checked', false);
        $('div[for="dv_liveLesson').find('.text-muted').text('');
        $('div[for="dv_liveLesson').find('a').attr('href', '#');
        $('div[for="dv_liveLesson').css('display', 'none');
        $('input[name="live_lesson"]').val('0');
    }
});

</script>

@endpush