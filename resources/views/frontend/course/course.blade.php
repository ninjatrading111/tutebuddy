@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

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
    @if(auth()->check() && (auth()->user()->hasRole('Instructor') || auth()->user()->hasRole('Administrator')))
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
                @if(auth()->user()->hasRole('Administrator'))
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

                @if(auth()->user()->hasRole('Instructor') && $is_mine)
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
                    <h1 class="text-white">{{ $course->title }}</h1>
                    <p class="lead text-white-50 measure-hero-lead mb-24pt">{{ $course->short_description }}</p>

                    @if(auth()->check() && auth()->user()->hasRole('Student'))
                        @if($course->favorited())
                            <button data-route="{{ route('admin.course.addFavorite', $course->id) }}" disabled class="btn btn-white mr-12pt"><i
                                    class="material-icons icon--left">favorite_border</i>
                                @lang('labels.frontend.buttons.add_favorite')
                            </button>
                        @else
                            <button data-route="{{ route('admin.course.addFavorite', $course->id) }}" id="btn_add_favorite" class="btn btn-outline-white mr-12pt"><i
                                class="material-icons icon--left">favorite_border</i> @lang('labels.frontend.buttons.add_favorite')</button>
                        @endif
                        {{-- <a href="javascript:void(0)" id="btn_add_share" class="btn btn-outline-white mr-12pt">
                            <i class="material-icons icon--left">share</i>
                            @lang('labels.frontend.buttons.share')
                        </a> --}}
                    @endif

                    {{-- @if($course->progress() == 100)
                        @if(!$course->isUserCertified())
                        <form method="post" action="{{route('admin.certificates.generate')}}" style="display: inline-block;">
                            @csrf
                            <input type="hidden" value="{{$course->id}}" name="course_id">
                            <button class="btn btn-outline-white" id="finish">
                                <i class="material-icons icon--left">done</i>
                                @lang('labels.frontend.course.finish_course')
                            </button>
                        </form>
                        @else
                        <button disabled="disabled" class="btn btn-white">
                            <i class="material-icons icon--left">done</i> @lang('labels.frontend.course.certified')
                        </button>
                        @endif
                    @endif --}}

                </div>
            </div>
            <div
                class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2 navbar-list p-0 m-0 align-items-center">
                <div class="container page__container">
                    <ul class="nav navbar-nav flex align-items-sm-center">
                        <li class="nav-item navbar-list__item">
                            <div class="media align-items-center">
                                <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                                    @if(empty($course->teachers->first()->avatar))
                                    <span
                                        class="avatar-title rounded-circle">{{ mb_substr($course->teachers->first()->name, 0, 2) }}</span>
                                    @else
                                    <img src="{{ asset('/storage/avatars/' . $course->teachers->first()->avatar) }}"
                                        alt="{{ $course->teachers->first()->name }}" class="avatar-img rounded-circle">
                                    @endif
                                </div>
                                <div class="media-body">
                                    <a class="card-title m-0"
                                        href="{{ route('profile.show', $course->teachers->first()->uuid) }}">{{ $course->teachers->first()->name }}</a>
                                    <p class="text-50 lh-1 mb-0">{{  $course->teachers->first()->headline }}</p>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">schedule</i>
                            {{ $course->duration() }}
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">timeline</i>
                            {{ \Carbon\Carbon::parse($course->start_date)->format('M d, Y') }} ~ {{ \Carbon\Carbon::parse($course->end_date)->format('M d, Y') }}
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">assessment</i>
                            {{ $course->level->name }}
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
                        <li class="nav-item ml-sm-auto text-sm-center flex-column navbar-list__item">
                            <div class="rating rating-24">
                                @include('layouts.parts.rating', ['rating' => $course_rating])
                            </div>
                            <p class="lh-1 mb-0"><small class="text-muted">{{ $total_ratings }} @lang('labels.frontend.general.ratings')</small></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->check() && $course->isEnrolled() || $is_mine)

    <div class="container page__container">
        <div class="row">
            <div class="col-lg-7">
                <div class="border-left-2 page-section pl-32pt">

                    @if(isset($course->mediaVideo))
                    <div class="mb-32pt">
                        <div class="bg-primary embed-responsive embed-responsive-16by9" data-domfactory-upgraded="player">
                            <div class="player embed-responsive-item">
                                <div class="player__content">
                                    <div class="player__image"
                                        style="--player-image: url({{ asset('storage/uploads/' . $course->course_image) }})">
                                    </div>
                                    <a href="" class="player__play bg-primary">
                                        <span class="material-icons">play_arrow</span>
                                    </a>
                                </div>
                                <div class="player__embed d-none">
                                    <?php
                                        $embed = Embed::make($course->mediaVideo->url)->parseUrl();
                                        $embed->setAttribute([
                                            'id'=>'display_course_video',
                                            'class'=>'embed-responsive-item',
                                            'allowfullscreen' => true
                                        ]);

                                        echo $embed->getHtml();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                    @foreach($course->lessons as $lesson)

                    <div class="d-flex align-items-center page-num-container" id="sec-{{ $lesson->id }}">
                        <div class="page-num">{{ $loop->iteration }}</div>

                        @if(empty($lesson->schedule))
                        
                            <a href="{{ route('lessons.show', [$course->slug, $lesson->slug, 'start']) }}">
                                <h4>{{ $lesson->title }}
                                    @if($lesson->isCompleted())
                                    <span class="badge badge-dark badge-notifications ml-2 p-1">
                                        <i class="material-icons m-0">check</i>
                                    </span>
                                    @endif
                                </h4>
                            </a>

                        @else
                            <a href="{{ route('lessons.live', [$lesson->slug, $lesson->id, $lesson->schedule->id]) }}">
                                <h4>{{ $lesson->title }}
                                    @if($lesson->isCompleted())
                                    <span class="badge badge-dark badge-notifications ml-2 p-1">
                                        <i class="material-icons m-0">check</i>
                                    </span>
                                    @endif
                                </h4>
                            </a>
                        @endif
                    </div>

                    <p class="text-70 mb-24pt">{{ $lesson->short_text }}</p>

                    @if(!empty($lesson->schedule))

                    <?php
                        $schedule = $lesson->schedule;
                    ?>
                    @if($schedule)
                    <p class="text-70 mb-24pt">
                        <span class="mr-20pt">
                            <i class="material-icons text-muted icon--left">schedule</i>
                            @lang('labels.frontend.course.start'): {{ $schedule->start_time }}
                        </span>

                        <span>
                            <i class="material-icons text-muted icon--left">schedule</i>
                            @lang('labels.frontend.course.end'): {{ $schedule->end_time }}
                        </span>
                    </p>

                    <div class="mb-32pt">
                        @if($lesson->isCompleted())
                        <button type="button" class="btn btn-outline-primary btn-block" disabled="">Finished</button>
                        @else
                            <?php $result = live_schedule($schedule); ?>
                            @if($result['today'] && $result['status'])
                            <a href="{{ route('lessons.live', [$lesson->slug, $lesson->id, $lesson->schedule->id]) }}" target="_blank"
                                data-lesson-id="" class="btn btn-outline-accent-dodger-blue btn-block btn-live-session">
                                @lang('labels.frontend.course.button.join_to_live')</a>
                            @elseif ($result['today'] && !$result['status'])
                                @if($result['result'] == 1)
                                    <button type="button" class="btn btn-outline-primary btn-block" disabled>Finished</button>
                                @else
                                    <button type="button" class="btn btn-outline-secondary btn-block btn-timer" 
                                        data-sec="'. $result['diff'] .'">{{ $result['timestr'] }}</button>
                                @endif
                            @else
                            <button type="button" class="btn btn-outline-primary btn-block" disabled="">
                                @lang('labels.frontend.course.button.scheduled')
                            </button>
                            @endif
                        @endif
                    </div>
                    @endif

                    @else

                    <div class="mb-32pt">
                        <ul class="accordion accordion--boxed js-accordion mb-0" id="toc-{{ $lesson->id }}">
                            <li class="accordion__item @if($loop->iteration == 1) open @endif">
                                <a class="accordion__toggle" data-toggle="collapse" data-parent="#toc-{{ $lesson->id }}"
                                    href="#toc-content-{{ $lesson->id }}">
                                    <span class="flex">{{ $lesson->steps->count() }} @lang('labels.frontend.course.steps')</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu">
                                    <ul class="list-unstyled collapse @if($loop->iteration == 1) show @endif"
                                        id="toc-content-{{ $lesson->id }}">

                                        @foreach( $lesson->steps as $step )

                                        <li class="accordion__menu-link">
                                            <span
                                                class="material-icons icon-16pt icon--left text-body">{{ config('stepicons')[$step['type']] }}</span>
                                            <a class="flex"
                                                href="{{ route('lessons.show', [$course->slug, $lesson->slug, $step->step]) }}">
                                                Step {{ $step['step'] }} : <span>{{ $step['title'] }}</span>
                                            </a>
                                            @if($step['duration'] && $step['type'] != 'document')
                                            <span class="text-muted">
                                                {{ $step['duration'] }} @lang('labels.frontend.general.min')
                                            </span>
                                            @else
                                            <span class="material-icons icon-16pt icon--left text-body text-muted">
                                                alarm
                                            </span>
                                            @endif
                                        </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    @endif

                    @endforeach
                </div>
            </div>

            <div class="page-section col-lg-5 border-left-2 dv-sticky">
                <div class="container page__container">
                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.frontend.course.about_course')</div>
                        </div>
                        <div class="course-description">{!! $course->description !!}</div>
                    </div>

                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.frontend.course.what_you_learn')</div>
                        </div>
                        <ul class="list-unstyled">
                            @foreach($course->lessons as $lesson)
                            <li class="d-flex align-items-center">
                                <span class="material-icons text-50 mr-8pt">check</span>
                                <span class="text-70">{{ $lesson->title }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">@lang('labels.frontend.course.about_teacher')</div>
                        </div>

                        @foreach($course->teachers as $teacher)

                        <div class="pt-sm-32pt pt-md-0 d-flex flex-column">
                            <div class="avatar avatar-xl avatar-online mb-lg-16pt">
                                @if(empty($teacher->avatar))
                                <span class="avatar-title rounded-circle">{{ mb_substr($teacher->name, 0, 2) }}</span>
                                @else
                                <img src="{{ asset('/storage/avatars/'. $teacher->avatar) }}" alt="{{ $teacher->name }}"
                                    class="avatar-img rounded-circle">
                                @endif
                            </div>
                            <h4 class="m-0">{{ $teacher->name }}</h4>
                            <p class="lh-1">
                                <small class="text-muted">{{ $teacher->headline }}t</small>
                            </p>
                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-start">
                                @if($is_mine)
                                <button class="btn btn-outline-primary mb-16pt mb-sm-0 mr-sm-16pt" disabled> @lang('labels.frontend.buttons.follow')</button>
                                <button class="btn btn-outline-secondary" disabled>@lang('labels.frontend.buttons.view_profile')</button>
                                @else
                                <a href="{{ route('profile.show', $teacher->uuid) }}"
                                    class="btn btn-outline-primary mb-16pt mb-sm-0 mr-sm-16pt">@lang('labels.frontend.buttons.follow')</a>
                                <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-outline-secondary">@lang('labels.frontend.buttons.view_profile')</a>
                                @endif
                            </div>
                        </div>

                        @endforeach
                    </div>

                    <!-- Puchase for Child -->
                    @if(auth()->check() && auth()->user()->child()->count() > 0)
                        <div class="page-separator mt-4">
                            <div class="page-separator__text">@lang('labels.frontend.course.purchase_for_child')</div>
                        </div>

                        <form action="{{ route('cart.process') }}" method="POST" id="frm_checkout">@csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="price_type" value="group">
                            <input type="hidden" name="child" value="">
                            <button type="button" id="btn_checkout" class="btn btn-primary btn-block mb-8pt" 
                                data-action="checkout">@lang('labels.frontend.course.buy_now')</button>
                        </form>

                        <form action="{{ route('cart.addToCart') }}" method="POST" id="frm_cart">@csrf
                            <input type="hidden" name="course_id" value="{{ $course->id }}">
                            <input type="hidden" name="price_type" value="group">
                            <input type="hidden" name="child" value="">
                            <button type="button" id="btn_addtocart" class="btn btn-accent btn-block mb-8pt" 
                                data-action="cart">@lang('labels.frontend.course.add_to_cart')</button>
                        </form>
                    @endif

                </div>
            </div>
        </div>
    </div>

    @else

    <div class="page-section bg-alt border-bottom-2">

        <div class="container page__container">
            <div class="row ">
                <div class="col-md-7">
                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.frontend.course.about_course')</div>
                    </div>
                    <div class="course-description">{!! $course->description !!}</div>
                </div>
                <div class="col-md-5">
                    <div class="page-separator">
                        <div class="page-separator__text bg-alt">@lang('labels.frontend.course.what_you_learn')</div>
                    </div>
                    <ul class="list-unstyled">
                        @foreach($course->lessons as $lesson)
                        <li class="d-flex align-items-center">
                            <span class="material-icons text-50 mr-8pt">check</span>
                            <span class="text-70">{{ $lesson->title }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.course.table_contents')</div>
            </div>

            <div class="row">
                <div class="col-lg-7">

                    @if(isset($course->mediaVideo))

                    <div class="mb-32pt">
                        <div class="bg-primary embed-responsive embed-responsive-16by9" data-domfactory-upgraded="player">
                            <div class="player embed-responsive-item">
                                <div class="player__content">
                                    <div class="player__image"
                                        style="--player-image: url({{ asset('storage/uploads/' . $course->course_image) }})">
                                    </div>
                                    <a href="" class="player__play bg-primary">
                                        <span class="material-icons">play_arrow</span>
                                    </a>
                                </div>
                                <div class="player__embed d-none">
                                    <?php
                                        $embed = Embed::make($course->mediaVideo->url)->parseUrl();
                                        $embed->setAttribute([
                                            'id'=>'display_course_video',
                                            'class'=>'embed-responsive-item',
                                            'allowfullscreen' => true
                                        ]);

                                        echo $embed->getHtml();
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                    @foreach($course->lessons as $lesson)
                    <div class="mb-32pt">
                        <ul class="accordion accordion--boxed js-accordion mb-0" id="toc-{{ $lesson->id }}">
                            <li class="accordion__item @if($loop->iteration == 1) open @endif">
                                <a class="accordion__toggle" data-toggle="collapse" data-parent="#toc-{{ $lesson->id }}"
                                    href="#toc-content-{{ $lesson->id }}">
                                    <span class="flex">{{$loop->iteration}} - {{ $lesson->title }}
                                        <small>({{ $lesson->steps->count() }} @lang('labels.frontend.course.steps'))</small></span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu">
                                    <ul class="list-unstyled collapse @if($loop->iteration == 1) show @endif"
                                        id="toc-content-{{ $lesson->id }}">

                                        @foreach( $lesson->steps as $step )

                                        <li class="accordion__menu-link">
                                            <span class="material-icons icon-16pt icon--left text-body">lock</span>
                                            <a class="flex" href="javascript:void(0)">
                                                Step {{ $step['step'] }} : <span>{{ $step['title'] }}</span>
                                            </a>
                                            @if($step['duration'])
                                            <span class="text-muted">
                                                {{ $step['duration'] }} min
                                            </span>
                                            @else
                                            <span class="material-icons icon-16pt icon--left text-body text-muted">
                                                alarm
                                            </span>
                                            @endif
                                        </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @endforeach
                </div>
                
                <div class="col-lg-5 justify-content-center">

                    <div class="card">
                        <div class="card-body py-16pt">

                            <div class="text-center">
                                <span
                                    class="icon-holder icon-holder--outline-secondary rounded-circle d-inline-flex mb-8pt">
                                    <i class="material-icons">timer</i>
                                </span>
                                <h4 class="card-title"><strong>Enrollment</strong></h4>
                                <p class="card-subtitle text-70 mb-12pt">Get access to the Course</p>

                                @if(!auth()->check())
                                <div class="d-flex mt-1 mb-2">
                                    <div class="price flex">
                                        @if (!empty($course->private_price))
                                        <div class="price-text">
                                            <span class="card-title text-primary">
                                                {{ getCurrency(config('app.currency'))['symbol'] . $course->private_price }}
                                                <small class="text-muted"> (@lang('labels.frontend.course.private'))</small>
                                            </span>
                                        </div>
                                        @endif
                                        @if (!empty($course->group_price))
                                        <div class="price-text">
                                            <span class="card-title text-accent">
                                                {{ getCurrency(config('app.currency'))['symbol'] . $course->group_price }} 
                                                <small class="text-muted"> (@lang('labels.frontend.course.group'))</small>
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                </div>

                                <a href="{{ route('register') }}" class="btn btn-accent mb-8pt">Sign up to Enroll</a>
                                <p class="mb-0">Have an account? <a href="{{ route('login') }}">Login</a></p>
                                @endif
                            </div>

                            @if(auth()->check() && auth()->user()->hasRole('Student'))
                            <div class="pl-5 pr-5">

                                <div class="form-group mb-32pt">
                                    <div class="custom-controls-stacked">

                                        @if(!empty($course->private_price))
                                        <div class="custom-control custom-radio mb-16pt">
                                            <input id="enroll_private" name="enroll_type" type="radio" enroll-type="private"
                                                data-amount="{{ $course->private_price }}" class="custom-control-input" checked="">
                                            <label for="enroll_private" class="card-title custom-control-label">
                                                @lang('labels.frontend.course.private'): {{ getCurrency(config('app.currency'))['symbol'] . $course->private_price }}
                                            </label>
                                        </div>
                                        @endif

                                        @if(!empty($course->group_price))
                                        <div class="custom-control custom-radio">
                                            <input id="enroll_group" name="enroll_type" type="radio" enroll-type="group"
                                                data-amount="{{ $course->group_price }}" class="custom-control-input" checked="">
                                            <label for="enroll_group" class="card-title custom-control-label">
                                                @lang('labels.frontend.course.group'): {{ getCurrency(config('app.currency'))['symbol'] . $course->group_price }}
                                            </label>
                                        </div>
                                        @endif

                                    </div>
                                </div>

                                @php
                                    $price_type = 'group';
                                    if(empty($course->group_price) && !empty($course->private_price)) {
                                        $price_type = 'private';
                                    }
                                @endphp

                                <form action="{{ route('cart.process') }}" method="POST">@csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <input type="hidden" name="price_type" value="{{ $price_type }}">
                                    <input type="hidden" name="child" value="">
                                    <button class="btn btn-primary btn-block mb-8pt" data-action="checkout">@lang('labels.frontend.course.buy_now')</button>
                                </form>

                                <form action="{{ route('cart.addToCart') }}" method="POST">@csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}">
                                    <input type="hidden" name="price_type" value="{{ $price_type }}">
                                    <input type="hidden" name="child" value="">
                                    <button class="btn btn-accent btn-block mb-8pt" data-action="cart">@lang('labels.frontend.course.add_to_cart')</button>
                                </form>

                                @if(auth()->check() && auth()->user()->child()->count() > 0)
                                    <div class="page-separator mt-4">
                                        <div class="page-separator__text bg-alt">@lang('labels.frontend.course.purchase_for_child')</div>
                                    </div>

                                    <form action="{{ route('cart.process') }}" method="POST" id="frm_checkout">@csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="price_type" value="{{ $price_type }}">
                                        <input type="hidden" name="child" value="">
                                        <button type="button" id="btn_checkout" class="btn btn-primary btn-block mb-8pt" 
                                            data-action="checkout">@lang('labels.frontend.course.buy_now')</button>
                                    </form>

                                    <form action="{{ route('cart.addToCart') }}" method="POST" id="frm_cart">@csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                                        <input type="hidden" name="price_type" value="{{ $price_type }}">
                                        <input type="hidden" name="child" value="">
                                        <button type="button" id="btn_addtocart" class="btn btn-accent btn-block mb-8pt" 
                                            data-action="cart">@lang('labels.frontend.course.add_to_cart')</button>
                                    </form>
                                @endif

                                @if ($course->hasDemo())
                                <div class="page-separator mt-4">
                                    <div class="page-separator__text bg-alt">Demo Request</div>
                                </div>

                                <div class="form-group">
                                    @if($course->demo())
                                    <button type="button" disabled class="btn btn-primary btn-block mb-8pt">
                                        Demo requested
                                    </button>
                                    @else
                                    <button type="button" id="btn_request_demo" class="btn btn-primary btn-block mb-8pt">
                                        Request Demo
                                    </button>
                                    @endif
                                </div>
                                @endif

                                <div class="page-separator mt-4">
                                    <div class="page-separator__text bg-alt">Pre Enroll</div>
                                </div>

                                <div class="form-group">
                                    <button type="button" id="btn_enroll_start" class="btn btn-accent btn-block mb-8pt">
                                        Chat with Instructor
                                    </button>
                                </div>

                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @endif

    <div class="page-section bg-alt border-top-2 border-bottom-2">

        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.course.student_feedback')</div>
            </div>
            <div class="row mb-32pt">
                <div class="col-md-3 mb-32pt mb-md-0">
                    <div class="display-1">{{ number_format($course_rating, 1) }}</div>
                    <div class="rating rating-24">
                        @include('layouts.parts.rating', ['rating' => $course_rating])
                    </div>
                    <p class="text-muted mb-0">{{ $total_ratings }} @lang('labels.frontend.general.ratings')</p>
                </div>
                <div class="col-md-9">

                    <?php
                        
                        if($total_ratings > 0) {
                            $ratings_5 = $course->reviews()->where('rating', '=', 5)->get()->count();
                            $percent_5 = number_format(($ratings_5 / $total_ratings) * 100, 1);
                            $ratings_4 = $course->reviews()->where('rating', '=', 4)->get()->count();
                            $percent_4 = number_format(($ratings_4 / $total_ratings) * 100, 1);
                            $ratings_3 = $course->reviews()->where('rating', '=', 3)->get()->count();
                            $percent_3 = number_format(($ratings_3 / $total_ratings) * 100, 1);
                            $ratings_2 = $course->reviews()->where('rating', '=', 2)->get()->count();
                            $percent_2 = number_format(($ratings_2 / $total_ratings) * 100, 1);
                            $ratings_1 = $course->reviews()->where('rating', '=', 1)->get()->count();
                            $percent_1 = number_format(($ratings_1 / $total_ratings) * 100, 1);
                        } else {
                            $ratings_5 = 0;
                            $percent_5 = 0;
                            $ratings_4 = 0;
                            $percent_4 = 0;
                            $ratings_3 = 0;
                            $percent_3 = 0;
                            $ratings_2 = 0;
                            $percent_2 = 0;
                            $ratings_1 = 0;
                            $percent_1 = 0;
                        }
                        
                    ?>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_5 }}% rated 5/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_5 }}" style="width: {{ $percent_5 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_5 }} @lang('labels.frontend.general.ratings')</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_4 }}% rated 4/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_4 }}" style="width: {{ $percent_4 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_4 }} @lang('labels.frontend.general.ratings')</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_3 }}% rated 3/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_3 }}" style="width: {{ $percent_3 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_3 }} @lang('labels.frontend.general.ratings')</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_2 }}% rated 2/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_2 }}" style="width: {{ $percent_2 }}%" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_2 }} @lang('labels.frontend.general.ratings')</span>
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip"
                        data-title="{{ $percent_1 }}% rated 0/5" data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar"
                                    aria-valuenow="{{ $percent_1 }}" aria-valuemin="{{ $percent_1 }}"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-md-auto col-sm-6 d-none d-sm-flex align-items-center">
                            <div class="rating">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                            </div>
                            <span class="text-muted ml-8pt">{{ $ratings_1 }} @lang('labels.frontend.general.ratings')</span>
                        </div>
                    </div>

                </div>
            </div>

            @foreach($course->reviews as $review)
            <div class="pb-16pt mb-16pt border-bottom row">
                <div class="col-md-3 mb-16pt mb-md-0">
                    <div class="d-flex">
                        <a href="{{ route('profile.show', $review->user->uuid) }}" class="avatar avatar-sm mr-12pt">
                            @if(!empty($review->user->avatar))
                            <img src="{{ asset('storage/avatars/' . $review->user->avatar ) }}" alt="avatar"
                                class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ mb_substr($review->user->name, 0, 2) }}</span>
                            @endif
                        </a>
                        <div class="flex">
                            <p class="small text-muted m-0">{{ $review->created_at->diffforhumans() }}</p>
                            <a href="{{ route('profile.show', $review->user->uuid) }}" class="card-title">{{ $review->user->name }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="rating mb-8pt">
                        @include('layouts.parts.rating', ['rating' => $review->rating])
                    </div>
                    <p class="text-70 mb-0">{{ $review->content }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    @if(auth()->check() && auth()->user()->hasRole('Student') && $course->isEnrolled())
    <div id="review_section"
        class="page-section border-bottom-2 bg-alt @if($course->isReviewed() == true) d-none @endif">

        <div class="container page__container">
            <!-- Add Reviews -->
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.course.provide_your_review')</div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div id="star_rate" class="review-stars-item form-inline form-group mb-0">
                        <span class="form-label">@lang('labels.frontend.course.your_rating'):</span>
                        <div class="rating rating-24 position-relative">
                            <label>
                                <input type="radio" name="stars" value="1">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="2">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="3">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="4">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                            <label>
                                <input type="radio" name="stars" value="5">
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                <span class="rating__item"><span class="material-icons">star</span></span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    @php
                    if(isset($review) && $course->isReviewed()) {
                    $review_route = route('courses.review.update', ['id'=>$review->id]);
                    } else {
                    $review_route = route('courses.review', ['id'=>$course->id]);
                    }
                    @endphp
                    <form method="POST" action="{{ $review_route }}" id="frm_review" class="mt-3">@csrf
                        <input type="hidden" name="rating" id="rating" value="">
                        <label for="review" class="form-label">@lang('labels.frontend.course.message'):</label>
                        <textarea name="review" class="form-control bg-light" id="review" rows="5"
                            cols="20" tute-no-empty></textarea>
                        <button id="btn_review" type="submit" class="btn btn-primary mt-3" value="Submit">
                            @lang('labels.frontend.course.button.add_review')
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<!-- // END Header Layout Content -->

<input type="hidden" id="course_description" value="{{ $course->description }}">
<div id="course_editor" style="display:none;"></div>

@if(auth()->check())

<!-- Enroll Chat -->
<!-- <button id="btn_enroll_start" class="open-button">
    <span class="material-icons icon-32pt">chat</span>
</button> -->

<div class="chat-popup" id="dv_enroll_chat">
    <form method="POST" action="{{ route('admin.messages.sendEnrollChat') }}" class="form-container">@csrf
        <div class="media align-items-center mt-8pt mb-16pt">
            <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                @if(empty($course->teachers->first()->avatar))
                <span
                    class="avatar-title rounded-circle">{{ mb_substr($course->teachers->first()->name, 0, 2) }}</span>
                @else
                <img src="{{ asset('/storage/avatars/' . $course->teachers->first()->avatar) }}"
                    alt="{{ $course->teachers->first()->name }}" class="avatar-img rounded-circle">
                @endif
            </div>
            <div class="media-body">
                <a class="card-title m-0"
                    href="{{ route('profile.show', $course->teachers->first()->uuid) }}">{{ $course->teachers->first()->name }}</a>
                <p class="text-50 lh-1 mb-0">{{ $course->teachers->first()->headline }}</p>
            </div>
        </div>
        <div id="messages_content"></div>
        <textarea placeholder="Type message.." name="message" required></textarea>
        <input type="hidden" name="user_id" value="{{ $course->teachers->first()->id }}">
        <input type="hidden" name="course_id" value="{{ $course->id }}">
        <input type="hidden" name="thread_id" value="">
        <button type="submit" class="btn btn-primary btn-block">@lang('labels.frontend.button.send')</button>
        <button type="button" id="btn_enroll_end" class="btn btn-accent btn-block">@lang('labels.frontend.button.close')</button>
    </form>
</div>

@endif

<!-- Modal for childs -->
<div class="modal fade" id="modal_childs" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('labels.frontend.course.select_child')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="childs_container" class="form-group p-3 font-size-16pt">
                    <!-- Childs -->
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_child_ok" class="btn btn-outline-primary">@lang('labels.frontend.course.buy_now')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Request Demo -->
<div class="modal fade" id="modal_demo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Select Date and Time</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="date_picker" class="form-group p-3">

                    @if(auth()->check())
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label for="request_user" class="form-label">Select A User</label>
                                <select id="request_user" name="request_user" class="form-control">
                                    <option value="{{ auth()->user()->id }}">{{ auth()->user()->name }}</option>
                                    @foreach(auth()->user()->child() as $child)
                                    <option value="{{ $child->id }}">{{ $child->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-12 col-xs-12">
                            <div class="form-group">
                                <label class="form-label" for="timezone">@lang('labels.auth.register.your_timezone') *:</label>
                                <select id="timezone" name="timezone" class="form-control"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-xs-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Preferred Date:</label>
                                <input id="demo_date" name="demo_date" type="hidden" class="form-control flatpickr-input" 
                                     value="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="col-md-6 col-xs-12">
                            <div class="form-group mb-0">
                                <label class="form-label">Preferred Time:</label>
                                @php
                                    $times = ['00:00 AM', '00:30 AM', '01:00 AM', '01:30 AM', '02:00 AM', '02:30 AM', 
                                    '03:00 AM', '03:30 AM', '04:00 AM', '04:30 AM', '05:00 AM', '05:30 AM', '06:00 AM', 
                                    '06:30 AM', '07:00 AM', '07:30 AM', '08:00 AM', '08:30 AM', '09:00 AM', '09:30 AM', 
                                    '10:00 AM', '10:30 AM', '11:00 AM', '11:30 AM', '12:00 AM', '12:30 AM', '01:00 PM', 
                                    '01:30 PM', '02:00 PM', '02:30 PM', '03:00 PM', '03:30 PM', '04:00 PM', '04:30 PM', 
                                    '05:00 PM', '05:30 PM', '06:30 PM', '07:00 PM', '07:30 PM', '08:00 PM', '08:30 PM', 
                                    '09:00 PM', '09:30 PM', '10:00 PM', '10:30 PM', '11:00 PM', '11:30 PM'];
                                @endphp
                                <select name="" id="demo_start_time" class="form-control">
                                    @foreach($times as $time)
                                    <option value="{{ $time }}">{{ $time }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_demo_ok" class="btn btn-outline-primary">Request Demo</button>
                </div>
            </div>
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

$(function() {

    let timer;
    let course_id = '{{ $course->id }}';

    $('#demo_date').flatpickr({
        minDate: "today"
    });

    $('#demo_start_time').select2();

    $('select[name="timezone"]').timezones();
    @if(auth()->check())
    $('select[name="timezone"]').val('{{ auth()->user()->timezone }}').prop('selected', true);
    @endif
    $('select[name="timezone"]').select2();

    $('input[name="stars"]').on('click', function() {
        $('#rating').val($(this).val());
        $('#star_rate').find('div.invalid-text').remove();
        $('#star_rate').removeClass('invalid');
    });

    $('#frm_review').on('submit', function(e) {
        e.preventDefault();

        if($('#rating').val() == '') {
            if($('#star_rate').find('.invalid-text').length < 1) {
                var err_msg = $('<div class="invalid-text">Star rate is required.</div>');
                $('#star_rate').append(err_msg);
            }
            $('#star_rate').addClass('invalid');
        }

        if(!checkValidForm($(this))) {
            return false;
        }

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    swal({
                        title: "Submit Review",
                        type: 'success',
                    },
                    function(val) {
                        if(val) {
                            location.reload();
                        }
                    });
                }
            }
        });
    });

    $('.player__play').on('click', function(e) {
        e.preventDefault();
        $(this).closest('.player').find('.player__embed').removeClass('d-none');
    });

    $('input[name="enroll_type"]').on('change', function() {
        $('input[name="price_type"]').val($(this).attr('enroll-type'));
    });

    $('#btn_checkout, #btn_addtocart').on('click', function(e) {
        e.preventDefault();
        var action = $(this).attr('data-action');
        $.ajax({
            method: 'GET',
            url: "{{ route('cart.getChilds', $course->id) }}",
            success: function(res) {
                if(res.success && res.result) {
                    $('#childs_container').empty();
                    $.each(res.childs, function(idx, item) {
                        var status = '';
                        var notice = '';
                        if(item.status == '1') {
                            status = 'disabled=true';
                            notice = ' (Already Enrolled)';
                        }
                        var ele = `<div class="custom-control custom-radio mb-2">
                            <input id="rad_child_` + item.id + `" name="radio_child" data-id="`+ item.id +`" type="radio" 
                                data-action="`+ action +`" class="custom-control-input" `+ status +`>
                            <label for="rad_child_` + item.id + `" class="custom-control-label">`+ item.name + notice + `</label>
                        </div>`;

                        $('#childs_container').append($(ele));
                    });

                    $('#modal_childs').modal('toggle');
                } else {
                    // $('#frm_' + action).submit();
                }
            }
        });
    });

    $('#btn_child_ok').on('click', function(e) {
        var child_id = $('input[name="radio_child"]:checked', '#childs_container').attr('data-id');
        var action = $('input[name="radio_child"]:checked', '#childs_container').attr('data-action');
        $('#frm_' + action).find('input[name="child"]').val(child_id);
        $('#modal_childs').modal('toggle');
        $('#frm_' + action).submit();
    });

    // Subscribe Course
    $('.btn-enroll').on('click', function() {

        var route = "{{ route('ajax.course.subscribe') }}";
        var type = $(this).attr('enroll-type');
        var course_id = $(this).attr('course-id');

        swal({
            title: "@lang('labels.frontend.alert.unlock_this_course')",
            text: "@lang('labels.frontend.alert.unlock_this_course_description')",
            type: 'success',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: "@lang('labels.frontend.alert.button.confirm')",
            cancelButtonText: "@lang('labels.frontend.alert.button.cancel')",
            dangerMode: false,
        }, function(val) {
            if (val) {
                $.ajax({
                    method: 'post',
                    url: route,
                    data: {
                        course_id: course_id,
                        type: type
                    },
                    success: function(res) {

                        if (res.success) {
                            window.location.reload();
                        }
                    }
                });
            }
        });
    });

    $('#btn_add_favorite').on('click', function(e) {
        
        var route = $(this).attr('data-route');
        $.ajax({
            method: 'GET',
            url: route,
            beforeSend: function() {
                // setting a timeout
                $('#btn_add_favorite').addClass('is-loading is-loading-sm');
            },
            success: function(res) {
                if(res) {
                    $('#btn_add_favorite').attr('disabled', 'disabled');
                    $('#btn_add_favorite').removeClass('btn-outline-white is-loading is-loading-sm');
                    $('#btn_add_favorite').addClass('btn-white');
                    $('#btn_add_favorite').html('<i class="material-icons icon--left">favorite_border</i> Added to Favorite');
                }
            },
            error: function(err) {
                console.log(err);
            },
            complete: function() {
                $('#btn_add_favorite').removeClass('is-loading is-loading-sm');
            }
        });
    });

    // Pre Enroll Chat
    $('#btn_enroll_start').on('click', function() {
        timer = setInterval(loadMessage, 2000);
        $('#dv_enroll_chat').toggle('medium');
    });

    // Request Demo
    $('#btn_request_demo').on('click', () => {
        
        // Get Available time
        let data = {
            course_id: course_id,
            date: $('#demo_date').val(),
            timezone: $('#timezone').val()
        };

        update_demo_times(data);

        $('#modal_demo').modal('toggle');
    });

    // When change demo time zone
    $('#timezone').on('change', () => {
        let data = {
            course_id: course_id,
            date: $('#demo_date').val(),
            timezone: $('#timezone').val()
        };

        update_demo_times(data);
    });

    $('#btn_demo_ok').on('click', function() {

        let demo_data = {
            user_id: $('#request_user').val(),
            course_id: '{{ $course->id }}',
            date: $('#demo_date').val(),
            start_time: $('#demo_start_time').val(),
            timezone: $('#timezone').val()
        };

        btnLoading($('#btn_demo_ok'), true);

        $.ajax({
            method: 'POST',
            url: '{{ route("admin.ajax.demo.create") }}',
            data: demo_data,
            success: function(res) {
                if (res.success) {
                    $('#modal_demo').modal('toggle');
                    $('#btn_request_demo').text('Demo Requested');
                    $('#btn_request_demo').attr('disabled', true);
                    swal('Success!', 'Demo successfully requested!', 'success');
                }

                btnLoading($('#btn_demo_ok'), false);
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    $('#dv_enroll_chat form').on('submit', function(e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    $('#messages_content').append()
                    if(res.action == 'send') {
                        $('#messages_content').append($('<ul class="d-flex flex-column list-unstyled p-2"></ul>'));
                    }
                    $(res.html).hide().appendTo('#messages_content ul').toggle(500);
                    $('textarea[name="message"]').val('');
                }
            }
        });
    });

    $('#btn_enroll_end').on('click', function() {
        clearInterval(timer);
        $('#dv_enroll_chat').toggle('medium');
    });

    $('#btn_publish').on('click', function(e) {

        e.preventDefault();
        var button = $(this);

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                console.log(res);
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", "@lang('labels.frontend.alert.publish_success')", "success");
                        button.text('Unpublish');
                        button.removeClass('btn-primary').addClass('btn-info');
                    } else {
                        swal("Success!", "@lang('labels.frontend.alert.unpublish_success')", "success");
                        button.text("@lang('labels.frontend.button.publish')");
                        button.removeClass('btn-info').addClass('btn-primary');
                    }
                    
                }
            }
        });
    });

    // Disable iframe right click
    $('iframe').bind("contextmenu", function (e) {
        e.preventDefault();
    });

    function update_demo_times(data) {
        $.ajax({
            method: 'POST',
            url: '{{ route("admin.ajax.demo.times") }}',
            data: data,
            success: (res) => {
                if (res.success) {

                    let options = ``;
                    $.each(res.times, (idx, time) => {
                        
                        options += `<option value="${time}">${time}</option>`;
                    });

                    $('#demo_start_time').html(options).select2();
                }
            },
            error: (err) => {
                console.log(err);
            }
        });
    }
    
    function loadMessage() {
        $.ajax({
            method: 'GET',
            url: '{{ route("admin.messages.getEnrollThread") }}',
            data: {
                course_id: '{{ $course->id }}',
                user_id: '{{ $course->teachers->first()->id }}',
                type: 'student'
            },
            success: function(res) {
                if(res.success) {
                    $('#dv_enroll_chat').find('input[name="thread_id"]').val(res.thread_id);
                    $('#messages_content').html(res.html);
                    // $(res.html).hide().appendTo('#messages_content').toggle(500);
                }
            }
        });
    }
});
</script>
@endpush

@endsection