@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">
<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">


<style>
[dir=ltr] .dv-sticky {
    z-index: 0;
    position: relative;
    position: -webkit-sticky;
    position: sticky;
    top: 4rem;
    display: block;
}

[dir=ltr] .review-stars-item .rating label input {
    position: absolute;
    top: 0;
    left: 0;
    opacity: 0;
}

[dir=ltr] .review-stars-item .rating__item {
    color: rgb(39 44 51 / 0.2);
}

[dir=ltr] .review-stars-item .rating label {
    display: inherit;
}

[dir=ltr] .rating label {
    position: absolute;
    top: 0;
    left: 0;
    height: 100%;
    cursor: pointer;
}

[dir=ltr] .rating label:nth-child(1) {
    z-index: 4;
}

[dir=ltr] .rating label:nth-child(2) {
    z-index: 3;
}

[dir=ltr] .rating label:nth-child(3) {
    z-index: 2;
}

[dir=ltr] .rating label:nth-child(4) {
    z-index: 1;
}

[dir=ltr] .rating label:last-child {
    position: static;
}

[dir=ltr] .rating:hover label:hover input~.rating__item {
    color: #f9c32c;
}

[dir=ltr] .rating:not(:hover) label input:checked~.rating__item {
    color: #ffc926;
}

[dir=ltr] div.invalid-text {
    color: #ed0b4c;
    font-weight: 500;
    margin-left: 15px;
}

/* Button used to open the chat form - fixed at the bottom of the page */
.open-button {
    background-color: #0085eb;
    color: white;
    padding: 12px;
    border: none;
    opacity: 0.8;
    position: fixed;
    bottom: 23px;
    right: 28px;
    border-radius: 100% !important;
    z-index: 99;
}

/* The popup chat - hidden by default */
.chat-popup {
    display: none;
    position: fixed;
    bottom: 15px;
    right: 15px;
    z-index: 100;
    box-shadow: 0px 0 2px 0px black;
    border-radius: 5px;
}

/* Add styles to the form container */
.form-container {
  max-width: 300px;
  padding: 10px;
  background-color: white;
  border-radius: 5px;
}

/* Full-width textarea */
.form-container textarea {
  width: 100%;
  padding: 15px;
  margin: 5px 0 15px 0;
  border: none;
  background: #f1f1f1;
  resize: none;
  min-height: 100px;
}

/* When the textarea gets focus, do something */
.form-container textarea:focus {
  background-color: #ddd;
  outline: none;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}

#messages_content ul {
    background: #f1f1f1;
}

</style>

@endpush

<div class="mdk-header-layout__content page-content">
    @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('Superadmin')))
    <div class="navbar navbar-list navbar-light border-bottom navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container">
            <nav class="nav navbar-nav">
                <div class="nav-item navbar-list__item">
                    <a href="{{ route('admin.courses.index') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                </div>
            </nav>
            
            <nav class="nav navbar-nav ml-sm-auto align-items-center align-items-sm-end d-none d-lg-flex">
                @if(auth()->user()->hasRole('Superadmin'))
                <div class="">
                    @if($course->published == 2)
                    <a href="{{ route('admin.courses.publish', $course->id) }}" id="btn_publish" class="btn btn-primary">
                        @lang('labels.frontend.buttons.publish')
                    </a>
                    @endif

                    @if($course->published == 1)
                    <a href="{{ route('admin.courses.publish', $course->id) }}" id="btn_publish" class="btn btn-info">
                        @lang('labels.frontend.buttons.unpublish')
                    </a>
                    @endif
                </div>
                @endif

                @if(auth()->user()->hasRole('admin') && $is_mine)
                <a href="{{ route('admin.courses.edit', $course->id) }}" class="btn btn-accent">@lang('labels.frontend.buttons.edit')</a>
                @endif
            </nav>
        </div>
    </div>
    @endif

    <div class="mdk-box bg-primary mdk-box--bg-gradient-primary2 js-mdk-box mb-0" data-effects="blend-background">
        <div class="mdk-box__content">
            <div class="hero py-64pt text-center text-sm-left">
                <div class="container page__container">
                    <h1 class="text-white">
                        {{-- {{ $course->title }} --}}
                        AAA
                    </h1>
                    <p class="lead text-white-50 measure-hero-lead mb-24pt">
                        {{-- {{ $course->short_description }} --}}
                        aaaaaaaaaaaaaaaaaaa
                    </p>
                </div>
            </div>
            <div
                class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2 navbar-list p-0 m-0 align-items-center">
                <div class="container page__container">
                    <ul class="nav navbar-nav flex align-items-sm-center">
                        <li class="nav-item navbar-list__item">
                            <div class="media align-items-center">
                                <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                                    {{-- @if(empty($course->teachers->first()->avatar)) --}}
                                    <span
                                        class="avatar-title rounded-circle">
                                        {{-- {{ mb_substr($course->teachers->first()->name, 0, 2) }} --}}
                                        asd
                                    </span>
                                    {{-- @else
                                    <img src="{{ asset('/storage/avatars/' . $course->teachers->first()->avatar) }}"
                                        alt="{{ $course->teachers->first()->name }}" class="avatar-img rounded-circle">
                                    @endif --}}
                                </div>
                                <div class="media-body">
                                    <a class="card-title m-0"
                                        {{-- href="{{ route('profile.show', $course->teachers->first()->uuid) }}" --}}
                                        >
                                        {{-- {{ $course->teachers->first()->name }} --}}
                                        Name
                                    </a>
                                    <p class="text-50 lh-1 mb-0">
                                        {{-- {{  $course->teachers->first()->headline }} --}}
                                        Professor
                                    </p>
                                </div>
                            </div>
                        </li>
                        {{-- <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">schedule</i>
                            {{ $course->duration() }}
                        </li> --}}
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">timeline</i>
                            Aug 11, 2022 ~ Aug 14, 2022
                            {{-- {{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }} ~ {{ \Carbon\Carbon::parse($course->end_date)->format('M d, Y') }} --}}
                        </li>

                        <li class="nav-item navbar-list__item">
                            <?php                                
                                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                                $course_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                            ?>
                            <!-- Your share button code -->
                            <div class="fb-share-button" 
                                data-href="{{ $course_url }}" 
                                data-layout="button">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.orders.view')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.courses.index') }}">@lang('labels.backend.orders.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.orders.view')
                        </li>
                    </ol>
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
                        <input disabled type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="@lang('labels.backend.orders.fields.title')" value="order title" tute-no-empty>
                    </div>

                    <label class="form-label">Orderer</label>
                    <div class="form-group mb-24pt">
                        <input disabled type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                            placeholder="slug" value="order slug" tute-no-empty>
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
                                            <input disabled name="start_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="Start date">
                                        </div>
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <div class="form-group mb-0">
                                            <label class="form-label">@lang('labels.backend.sidebar.end_date'):</label>
                                            <input disabled name="end_date" type="hidden" class="form-control flatpickr-input"
                                                data-toggle="flatpickr" value="End Date">
                                        </div>
                                    </div>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.date_note')</small>
                            </div>

                            <!-- Timezone -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.timezone')</label>
                                <select disabled name="timezone" class="form-control"></select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_timezone')</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-0">
                        <label class="form-label">@lang('labels.backend.sidebar.extras')</label>
                        <select disabled name="tags[]" id="course_tags" multiple="multiple" class="form-control">
                            {{-- @foreach($tags as $tag) --}}
                            {{-- @php $course_tags = (!empty($course->tags)) ? json_decode($course->tags) : []; --}}
                            {{--  @endphp --}}
                            <option>Extras</option>
                            {{-- @endforeach --}}
                        </select>
                        <small class="form-text text-muted">@lang('labels.backend.sidebar.select_tags')</small>
                    </div>

                    <label class="form-label">@lang('labels.backend.orders.fields.summary')</label>
                    <div class="form-group mb-24pt">
                        <textarea disabled name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="Short description">description</textarea>
                        <small class="form-text text-muted">@lang('labels.backend.orders.fields.description_note')</small>
                    </div>

                    <div class="form-group mb-32pt">
                        <label class="form-label">@lang('labels.backend.orders.fields.order_detail')</label>

                        <!-- quill editor -->
                        <div style="min-height: 150px; padding:5px; border:1px dashed black" id="course_editor" class="mb-0">Order detail description</div>
                        <small class="form-text text-muted">@lang('labels.backend.orders.fields.about_course_note')</small>
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
                                <select disabled name="category" class="form-control custom-select" data-toggle="select">
                                    {{-- @foreach ($parentCategories as $category) --}}

                                    <option>University</option>
                                    {{-- @if ($category->children()->count() > 0 ) --}}
                                    {{-- ?php $space = ''; ?> --}}
                                    {{-- @include( --}}
                                    {{-- 'backend.category.sub.option', --}}
                                    {{-- ['category' => $category, 'space' => $space, 'selected' => $course->category_id] --}}
                                    {{-- ) --}}
                                    {{-- @endif --}}

                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.subject')</label>
                                <select disabled name="category" class="form-control custom-select" data-toggle="select">
                                    {{-- @foreach ($parentCategories as $category) --}}
                                        <option value="">Subject</option>
                                        {{-- @if ($category->children()->count() > 0 ) --}}
                                            {{-- ?php $space = ''; ?> --}}
                                        {{-- @include( --}}
                                        {{-- 'backend.category.sub.option', --}}
                                        {{-- ['category' => $category, 'space' => $space, 'selected' => $course->category_id] --}}
                                        {{-- ) --}}
                                        {{-- @endif --}}
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <!-- Set Level -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.writer_level')</label>
                                <select disabled name="level" class="form-control">
                                    {{-- @foreach($levels as $level) --}}
                                    <option value="">Writer level</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>
                            
                            <label class="form-label">@lang('labels.backend.sidebar.page')</label>
                            <div class="form-group mb-24pt">
                                <input disabled type="number" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                placeholder="Page Number" value=" " tute-no-empty>
                                @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.spacing')</label>
                                <select disabled name="level" class="form-control">
                                    {{-- @foreach($levels as $level) --}}
                                    <option value="">Spacing</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.formating')</label>
                                <select disabled name="level" class="form-control">
                                    {{-- @foreach($levels as $level) --}}
                                    <option value="">Formating</option>
                                    {{-- @endforeach --}}
                                </select>
                            </div>

                            <label class="form-label">@lang('labels.backend.sidebar.promo_code')</label>
                            <div class="form-group mb-24pt">
                                <input disabled type="number" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                placeholder="Input Page Number" value=" " tute-no-empty>
                                @error('slug')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            
                            <label class="form-label">@lang('labels.backend.sidebar.number_source')</label>
                            <div class="form-group mb-24pt">
                                <input disabled type="number" name="slug" class="form-control @error('slug') is-invalid @enderror"
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
                        <img id="display_course_image" src="@if(!empty($course->course_image)) 
                                {{asset('/storage/uploads')}}/{{ $course->course_image }}
                                 @else 
                                    {{asset('/assets/img/no-image.jpg')}}
                                 @endif" id="img_course_image" width="100%" alt="">
                        <div class="card-body">
                            <div class="mb-3">
                                <a href="#" target="_blank">Essay.pdf</a> 
                            </div>
                            <div class="mb-3">
                                <a href="#" target="_blank">Book.pdf</a> 
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

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

// $(function() {


//     $('#btn_publish').on('click', function(e) {

//         e.preventDefault();
//         var button = $(this);

//         var url = $(this).attr('href');

//         $.ajax({
//             method: 'get',
//             url: url,
//             success: function(res) {
//                 console.log(res);
//                 if(res.success) {
//                     if(res.published == 1) {
//                         swal("Success!", "@lang('labels.frontend.alert.publish_success')", "success");
//                         button.text('Unpublish');
//                         button.removeClass('btn-primary').addClass('btn-info');
//                     } else {
//                         swal("Success!", "@lang('labels.frontend.alert.unpublish_success')", "success");
//                         button.text("@lang('labels.frontend.button.publish')");
//                         button.removeClass('btn-info').addClass('btn-primary');
//                     }
                    
//                 }
//             }
//         });
//     });
// });
</script>
@endpush

@endsection