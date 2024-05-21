@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div data-push data-responsive-width="992px" class="mdk-drawer-layout js-mdk-drawer-layout">
        <div class="mdk-drawer-layout__content">

            <div class="page-section">
                <div class="container page__container">

                    <div class="d-flex flex-column flex-sm-row align-items-sm-center mb-24pt"
                        style="white-space: nowrap;">
                        <small class="flex text-muted text-headings text-uppercase mr-3 mb-2 mb-sm-0">Displaying {{ $courses->count() }} courses</small>

                        <div class="w-auto ml-sm-auto table d-flex align-items-center mb-2 mb-sm-0">
                            <small class="text-muted text-headings text-uppercase mr-3 d-none d-sm-block">Sort by</small>
                            <a href="#" class="sort desc small text-headings text-uppercase">Newest</a>
                            <a href="#" class="sort small text-headings text-uppercase ml-2">Popularity</a>
                        </div>

                        <a href="#" data-target="#library-drawer" data-toggle="sidebar" class="btn btn-sm btn-white ml-sm-16pt">
                            <i class="material-icons icon--left">tune</i> Filters
                        </a>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Search</div>
                    </div>

                    <div class="form-group pb-32pt" style="position: relative;">
                        <div class="search-form input-group-lg">
                            <input type="text" class="form-control" placeholder="What do you wan to learn today?" id="search_homepage"
                            value="{{ $_GET['_q'] }}">
                            <button class="btn" type="button" role="button"><i class="material-icons">search</i></button>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Search Results</div>
                    </div>

                    <div class="row card-group-row">

                        @foreach($courses as $course)

                        <div class="col-md-6 col-lg-4 col-xl-3 card-group-row__col">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                                data-toggle="popover" data-trigger="click">


                                <a href="{{ route('courses.show', $course->slug) }}" class="card-img-top js-image"
                                    data-position="" data-height="140">
                                    <img src="{{ asset('storage/uploads/' . $course->course_image) }}" alt="course"
                                        height="140">
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
                                            <a class="card-title" href="{{ route('courses.show', $course->slug) }}">
                                                {{ $course->title }}
                                            </a>
                                            <small class="text-50 font-weight-bold mb-4pt">
                                                {{ $course->teachers[0]->name }}
                                            </small>
                                        </div>
                                        <a href="{{ route('courses.show', $course->slug) }}" data-toggle="tooltip"
                                            data-title="Add Favorite" data-placement="top" data-boundary="window"
                                            class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                    </div>
                                    <div class="d-flex">
                                        <div class="rating flex">
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span class="material-icons">star</span></span>
                                            <span class="rating__item"><span
                                                    class="material-icons">star_border</span></span>
                                        </div>
                                        <!-- <small class="text-50">6 hours</small> -->
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="row justify-content-between">
                                        <div class="col-auto d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="col-auto d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('storage/uploads/thumb/' . $course->course_image) }}"
                                            width="40" height="40" alt="Angular" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">{{ $course->title }}</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span
                                                class="text-black-50 small font-weight-bold">{{ $course->teachers[0]->name }}</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">{{ $course->short_description }}</p>

                                <div class="mb-16pt">

                                    @foreach($course->lessons as $lesson)
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>{{ $lesson->title }}</small></p>
                                    </div>
                                    @endforeach
                                </div>


                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>6 hours</small></p>
                                        </div>
                                        <div class="d-flex align-items-center mb-4pt">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->lessons->count() }} lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0">
                                                <small>{{ $course->level->name }}</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="{{ route('courses.show', $course->slug) }}"
                                            class="btn btn-primary">Enroll</a>
                                    </div>
                                </div>

                            </div>

                        </div>

                        @endforeach

                    </div>

                    {{ $courses->render() }}
                </div>
            </div>
        </div>

        <div class="mdk-drawer js-mdk-drawer " id="library-drawer" data-align="end">
            <div class="mdk-drawer__content top-navbar">
                <div class="sidebar sidebar-light sidebar-right py-16pt" data-perfect-scrollbar
                    data-perfect-scrollbar-wheel-propagation="true">

                    <div class="d-flex align-items-center mb-24pt  d-lg-none">
                        <form action="" class="search-form search-form--light mx-16pt pr-0 pl-16pt">
                            <input type="text" class="form-control pl-0" placeholder="Search">
                            <button class="btn" type="submit"><i class="material-icons">search</i></button>
                        </form>
                    </div>

                    <div class="sidebar-heading">Category</div>
                    <ul class="sidebar-menu">

                        @foreach($parentCategories as $category)
                        <li class="sidebar-menu-item @if(isset($_GET['_t']) && $_GET['_k'] == $category->id) active @endif">
                            <a href="/search?_q={{ $_GET['_q'] }}&_t=category&_k={{ $category->id }}" class="sidebar-menu-button">
                                <span class="material-icons sidebar-menu-icon sidebar-menu-icon--left">folder</span>
                                <span class="sidebar-menu-text">{{ $category->name }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection