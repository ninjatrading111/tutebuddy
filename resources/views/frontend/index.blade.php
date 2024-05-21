@extends('layouts.app')

@push('after-styles')

<link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/owl.theme.default.min.css') }}" rel="stylesheet">

<style>
    .owl-nav {
        height: 0;
    }
</style>

@endpush

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="bg-white border-bottom-2 py-16pt ">
        <div class="container page__container">
            <div class="row align-items-center">
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div
                        class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">1</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.frontend.home.select_course.title')</div>
                        <p class="card-subtitle text-black-70">@lang('string.frontend.home.select_course.description')</p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div
                        class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">2</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.frontend.home.find_expert.title')</div>
                        <p class="card-subtitle text-black-70">@lang('string.frontend.home.find_expert.description')</p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center">
                    <div
                        class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">3</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.frontend.home.start_learning.title')</div>
                        <p class="card-subtitle text-black-70">@lang('string.frontend.home.start_learning.description')</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories -->
    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.home.categories')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('category.all') }}" class="btn btn-md btn-white float-right border">@lang('labels.frontend.home.browse_all')</a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row">
                {{-- @foreach($parentCategories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6">
                    <div class="card card-body p-2">
                        <div class="d-flex align-items-center">
                            <div class="rounded mr-12pt">
                                <div class="avatar avatar-sm mr-3">
                                    <a href="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}">
                                        <img src="{{ asset('/storage/uploads/' . $category->thumb) }}" alt="avatar"
                                            class="avatar-img rounded">
                                    </a>
                                </div>
                            </div>
                            <div class="flex">
                                <a href="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}" class="card-title mr-3">
                                    {{ $category->name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach --}}
            </div>
        </div>
    </div>

    {{-- @if(count($bundles) > 2)
    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.home.learning_paths')</div>
            </div>

            <div class="row card-group-row">
                @foreach($bundles as $bundle)
                <div class="col-sm-4 card-group-row__col">

                    <div class="card js-overlay card-sm overlay--primary-dodger-blue stack stack--1 card-group-row__card mb-lg-0"
                        data-toggle="popover" data-trigger="click">

                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <div class="flex">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded mr-12pt z-0 o-hidden">
                                            <div class="overlay">
                                                @if(!empty($bundle->bundle_image))
                                                <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @else
                                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @endif
                                                <span class="overlay__content overlay__content-transparent">
                                                    <span class="overlay__action d-flex flex-column text-center lh-1">
                                                        <small class="h6 small text-white mb-0"
                                                            style="font-weight: 500;">{{ mb_substr($bundle->title, 0, 2) }}</small>
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex">
                                            <div class="card-title">{{ $bundle->title }}</div>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>{{ $bundle->courses->count() }} courses</small></p>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->check() && auth()->user()->hasRole('Student'))

                                <a href="{{ route('admin.bundle.addFavorite', $bundle->id) }}" data-toggle="tooltip" data-title="Add Favorite"
                                    data-placement="top" data-boundary="window"
                                    class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>

                                @endif

                            </div>

                        </div>
                    </div>

                    <div class="popoverContainer d-none">
                        <div class="media">
                            <div class="media-left mr-12pt">
                                @if(!empty($bundle->bundle_image))
                                <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                @else
                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                @endif
                            </div>
                            <div class="media-body">
                                <div class="card-title">{{ $bundle->title }}</div>
                                <p class="text-black-50 d-flex lh-1 mb-0 small">{{ $bundle->courses->count() }} courses</p>
                            </div>
                        </div>

                        <p class="mt-16pt text-black-70">{{ $bundle->description }}</p>

                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="d-flex align-items-center">
                                    <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                    <p class="flex text-black-50 lh-1 mb-0"><small>
                                        @if($bundle->category)
                                        {{ $bundle->category->name }}
                                        @else
                                        No Category
                                        @endif
                                    </small></p>
                                </div>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('bundles.show', $bundle->slug) }}" class="btn btn-outline-secondary">Begin</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif --}}

    {{-- @if(count($featuredCourses) > 3)
    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.home.featured_courses')</div>
            </div>

            <div class="row card-group-row">
                <div class="col-lg-12">
                    <div class="position-relative carousel-card">

                        <div class="owl-carousel" id="carousel-courses-dynamic">

                            @foreach($featuredCourses as $course)
                            <div class="card-group-row__col">

                                <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card" data-toggle="popover" data-trigger="click">

                                    <a href="{{ route('courses.show', $course->slug) }}" class="card-img-top js-image" data-position="" data-height="140">
                                        <img src="{{ asset('storage/uploads/' . $course->course_image) }}" alt="course">
                                        <span class="overlay__content">
                                            <span class="overlay__action d-flex flex-column text-center">
                                                <i class="material-icons icon-32pt">play_circle_outline</i>
                                                <span class="card-title text-white">Preview</span>
                                            </span>
                                        </span>
                                    </a>

                                    <div class="card-body flex">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                                <small class="text-50 font-weight-bold mb-4pt">{{ $course->teachers[0]->name }}</small>
                                            </div>

                                            @if(auth()->check())
                                            <a href="{{ route('admin.course.removeFavorite', $course->id) }}" name="remove_favorite" data-toggle="tooltip" data-title="Remove Favorite" data-placement="top" 
                                                data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite font-color-red @if(!$course->favorited()) d-none @endif"
                                                data-original-title="" title="">favorite</a>
                                            <a href="{{ route('admin.course.addFavorite', $course->id) }}" name="add_favorite" data-toggle="tooltip" data-title="Add Favorite" data-placement="top" 
                                                data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite @if($course->favorited()) d-none @endif"
                                                data-original-title="" title="">favorite_border</a>
                                            @endif
                                        </div>
                                        <div class="d-flex mt-1">
                                            <div class="rating flex">
                                                @include('layouts.parts.rating', ['rating' =>
                                                $course->reviews->avg('rating')])
                                            </div>
                                        </div>
                                        <div class="d-flex mt-1">
                                            <div class="price flex">
                                                @if (!empty($course->private_price))
                                                <div class="price-text">
                                                    <span class="card-title text-primary mt-1">
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
                                    </div>
                                    <div class="card-footer">
                                        <div class="row justify-content-between">
                                            <div class="col-auto d-flex align-items-center">
                                                <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }}</small></p>
                                            </div>
                                            <div class="col-auto d-flex align-items-center">
                                                <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="popoverContainer d-none">
                                    <div class="media">
                                        <div class="media-left mr-12pt">
                                            <img src="{{ asset('storage/uploads/thumb/' . $course->course_image) }}" width="40" height="40" alt="{{ $course->title }}" class="rounded">
                                        </div>
                                        <div class="media-body">
                                            <div class="card-title mb-0">{{ $course->title }}</div>
                                            <p class="lh-1 mb-0">
                                                <span class="text-black-50 small">with</span>
                                                <span class="text-black-50 small font-weight-bold">{{ $course->teachers[0]->name }}</span>
                                            </p>
                                        </div>
                                    </div>

                                    <p class="my-16pt text-black-70">{{ $course->short_description }}</p>

                                    <div class="mb-16pt">
                                        @foreach($course->lessons as $lesson)
                                        <div class="d-flex align-items-center">
                                            <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $lesson->title }}</small></p>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <div class="d-flex align-items-center mb-4pt">
                                                <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }} hours</small></p>
                                            </div>
                                            <div class="d-flex align-items-center mb-4pt">
                                                <span class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->lessons->count() }} lessons</small></p>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->level->name }}</small></p>
                                            </div>
                                        </div>
                                        @if(auth()->check() && auth()->user()->hasRole('Student'))
                                        <div class="col text-right">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary">View Detail</a>
                                        </div>
                                        @endif

                                        @if(!auth()->check())
                                        <div class="col text-right">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary">View Detail</a>
                                        </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif --}}

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="row card-group-row">
                <div class="col-md-6 col-lg-4">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.frontend.home.for_instructors.title')</div>
                    </div>

                    <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                        <img src="{{ asset('/assets/img/course-16.jpg') }}" alt="" class="card-img" 
                            style="border-bottom-right-radius: 0; border-bottom-left-radius:0;">
                        <div class="card-body">
                            <div class="text-black-70 mt-16pt">
                                @lang('string.frontend.home.for_instructors.description')
                            </div>
                            <a href="/page/teach-on-tutebuddy" class="btn btn-primary mt-16pt">
                                @lang('labels.frontend.home.start_teaching')
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.frontend.home.for_business.title')</div>
                    </div>

                    <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                        <img src="{{ asset('/assets/img/course-5.jpg') }}" alt="" class="card-img" 
                            style="border-bottom-right-radius: 0; border-bottom-left-radius:0;">
                        <div class="card-body">
                            <div class="text-black-70 mt-16pt">
                                @lang('string.frontend.home.for_business.description')
                            </div>
                            <a href="/page/solutions-for-business" class="btn btn-primary mt-16pt">
                                @lang('labels.frontend.home.start_training')
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.frontend.home.for_schools.title')</div>
                    </div>

                    <div class="card card--elevated posts-card-popular overlay card-group-row__card">
                        <img src="{{ asset('/assets/img/course-9.jpg') }}" alt="" class="card-img" 
                            style="border-bottom-right-radius: 0; border-bottom-left-radius:0;">
                        <div class="card-body">
                            <div class="text-black-70 mt-16pt">
                                @lang('string.frontend.home.for_schools.description')
                            </div>
                            <a href="/page/solutions-for-institutions" class="btn btn-primary mt-16pt">
                                @lang('labels.frontend.home.start_learning.title')
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2 bg-alt position-relative" style="z-index: 999;">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.home.expert_teachers.title')</div>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <img src="{{ asset('/assets/img/course-16.jpg') }}" alt="" class="card-img">
                </div>

                <div class="col-lg-6 font-size-16pt">
                    <div>
                        @lang('string.frontend.home.expert_teachers.description')

                        <div class="ui search category fluid instructor pt-16pt">
                            <div class="ui icon input w-100">
                                <input class="prompt" type="text" placeholder="@lang('labels.frontend.home.search_teachers_placeholder')" 
                                    style="font-size: 0.9rem;">
                                <i class="search icon"></i>
                            </div>
                            <div class="results"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2 bg-alt">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.home.education_for_all')</div>
            </div>

            <div class="row">
                <div class="col-lg-6 font-size-16pt">
                    
                    <div>
                        @lang('string.frontend.home.education_for_all')
                    </div>

                    <div class="form-group">
                        <a href="{{ route('register') }}?r=s" class="btn btn-accent">@lang('labels.frontend.home.signup_as_student')</a>
                    </div>
                </div>

                <div class="col-lg-6">
                    <img src="{{ asset('/assets/img/course-6.jpg') }}" alt="" class="card-img">
                </div>
            </div>
        </div>
    </div>

    {{-- @if(count($reviews) > 1)
    <div class="page-section">
        <div class="container page__container">
            <div class="page-headline text-center">
                <h2>@lang('labels.frontend.home.feedback.title')</h2>
                <p class="lead measure-lead mx-auto text-black-70">@lang('string.frontend.home.feedback.description')</p>
            </div>

            <div class="position-relative carousel-card col-lg-8 p-0 mx-auto">
                <div class="row d-block js-mdk-carousel" id="carousel-feedback">
                    <a class="carousel-control-next js-mdk-carousel-control mt-n24pt" href="#carousel-feedback"
                        role="button" data-slide="next">
                        <span class="carousel-control-icon material-icons"
                            aria-hidden="true">keyboard_arrow_right</span>
                        <span class="sr-only">Next</span>
                    </a>
                    <div class="mdk-carousel__content">

                        @foreach($reviews as $review)

                        <div class="col-12 col-md-6">

                            <div class="card card-feedback card-body">
                                <blockquote class="blockquote mb-0">
                                    <p class="text-70 small mb-0">{{ $review->content }}</p>
                                </blockquote>
                            </div>
                            <div class="media ml-12pt">
                                <div class="media-left mr-12pt">
                                    <a href="{{ route('profile.show', $review->user->uuid) }}" class="avatar avatar-sm">
                                        @if(!empty($review->user->avatar))
                                        <img src="{{asset('/storage/avatars/' . $review->user->avatar )}}" alt="Avatar" class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ mb_substr($review->user->name, 0, 2) }}</span>
                                        @endif
                                    </a>
                                </div>
                                <div class="media-body media-middle">
                                    <a href="{{ route('profile.show', $review->user->uuid) }}" class="card-title">{{ $review->user->name }}</a>
                                    <div class="rating mt-4pt">
                                        @include('layouts.parts.rating', ['rating' => $review->rating])
                                    </div>
                                </div>
                            </div>
                        </div>

                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif --}}
</div>
<!-- // END Header Layout Content -->

@include('layouts.parts.search-script');

@push('after-scripts')

<script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>

<script>

$(function(e) {

    let featured_max = false;

    $('a[name="add_favorite"]').on('click', function(e) {
        e.preventDefault();
        var route = $(this).attr('href');
        var btn_add_favorite = $(this);

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                if(res) {
                    btn_add_favorite.addClass('d-none');
                    btn_add_favorite.siblings('a[name="remove_favorite"]').removeClass('d-none');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    $('a[name="remove_favorite"]').on('click', function(e) {
        e.preventDefault();
        var route = $(this).attr('href');
        var btn_remove_favorite = $(this);

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                if(res) {
                    btn_remove_favorite.addClass('d-none');
                    btn_remove_favorite.siblings('a[name="add_favorite"]').removeClass('d-none');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });

    let owl = $('#carousel-courses-dynamic').owlCarousel({
        nav: true,
        dots: false,
        margin: 15,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            768: {
                items: 3
            },
            1024: {
                items: 4
            }
        },
        navText: [`<a class="carousel-control-prev js-mdk-carousel-control mt-n24pt" role="button">
                    <span class="carousel-control-icon material-icons"
                        aria-hidden="true">keyboard_arrow_left</span>
                    <span class="sr-only">Prev</span>
                </a>`, `<a class="carousel-control-next js-mdk-carousel-control mt-n24pt" role="button">
                    <span class="carousel-control-icon material-icons"
                        aria-hidden="true">keyboard_arrow_right</span>
                    <span class="sr-only">Next</span>
                </a>`]
    });

    owl.on('initialized.owl.carousel changed.owl.carousel refreshed.owl.carousel', function (e) {
        if (!e.namespace) return;
        var carousel = e.relatedTarget,
            current = carousel.current();
        if (current === carousel.maximum() && !featured_max) {
            load_content(current);
        }
    });

    const load_content = function (current) {
        $.ajax({
            method: 'GET',
            url: '/ajax/carousel/featured/' + current,
            success: (res) => {
                if (res.data != '') {
                    owl.trigger('add.owl.carousel', $(res.data));
                    owl.trigger('refresh.owl.carousel');
                } else {
                    featured_max = true;
                }
                
            },
            error: (err) => {
                console.log(err);
            }
        });
    }
});

</script>

@endpush

@endsection