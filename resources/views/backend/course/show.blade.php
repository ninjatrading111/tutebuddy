@extends('layouts.app')

@section('content')

@push('after-styles')
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
[dir=ltr] .rating:hover label:hover input ~ .rating__item {
    color: #f9c32c;
}
[dir=ltr] .rating:not(:hover) label input:checked ~ .rating__item {
    color: #ffc926;
}
</style>
@endpush

<div class="mdk-header-layout__content page-content ">

    <div class="mdk-box bg-primary mdk-box--bg-gradient-primary2 js-mdk-box mb-0" data-effects="blend-background">
        <div class="mdk-box__content">
            <div class="hero py-64pt text-center text-sm-left">
                <div class="container page__container">
                    <h1 class="text-white">{{ $course->title }}</h1>
                    <p class="lead text-white-50 measure-hero-lead mb-24pt">{{ $course->short_description }}</p>
                    <a href="#" class="btn btn-white">Resume course</a>
                </div>
            </div>
            <div
                class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2 navbar-list p-0 m-0 align-items-center">
                <div class="container page__container">
                    <ul class="nav navbar-nav flex align-items-sm-center">
                        <li class="nav-item navbar-list__item">
                            <div class="media align-items-center">
                                <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                                    @if(empty($course->teachers[0]->avatar))
                                    <span class="avatar-title rounded-circle">{{ mb_substr($course->teachers[0]->name, 0, 2) }}</span>
                                    @else
                                    <img src="{{ asset('/storage/avatars') }}/{{ $course->teachers[0]->avatar }}"
                                        alt="$course->teachers[0]->name" class="avatar-img rounded-circle">
                                    @endif
                                </div>
                                <div class="media-body">
                                    <a class="card-title m-0" href="">{{ $course->teachers[0]->name }}</a>
                                    <p class="text-50 lh-1 mb-0">admin</p>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">schedule</i>
                            {{ Carbon\Carbon::now()->format('H') }}h {{ Carbon\Carbon::now()->format('i') }}m
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">assessment</i>
                            {{ $course->level->name }}
                        </li>
                        <li class="nav-item ml-sm-auto text-sm-center flex-column navbar-list__item">
                            <div class="rating rating-24">
                                @for($r = 1; $r <= $course_rating; $r++)
                                <span class="rating__item"><span class="material-icons">star</span></span>
                                @endfor

                                @if($course_rating > ($r-1))
                                <span class="rating__item"><span class="material-icons">star_half</span></span>
                                @else
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                @endif
                                
                                @for($r_a = $r; $r < 5; $r++)
                                <span class="rating__item"><span class="material-icons">star_border</span></span>
                                @endfor
                            </div>
                            <p class="lh-1 mb-0"><small class="text-muted">{{ $total_ratings }} ratings</small></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="row">
            <div class="col-lg-7">
                <div class="border-left-2 page-section pl-32pt">

                    @foreach($course->lessons as $lesson)

                    <div class="d-flex align-items-center page-num-container" id="sec-{{ $lesson->id }}">
                        <div class="page-num">{{ $loop->iteration }}</div>
                        <h4>{{ $lesson->title }}</h4>
                    </div>

                    <p class="text-70 mb-24pt">{{ $lesson->short_text }}</p>

                    <div class="mb-32pt">
                        <ul class="accordion accordion--boxed js-accordion mb-0" id="toc-{{ $lesson->id }}">
                            <li class="accordion__item @if($loop->iteration == 1) open @endif">
                                <a class="accordion__toggle" data-toggle="collapse" data-parent="#toc-{{ $lesson->id }}" href="#toc-content-{{ $lesson->id }}">
                                    <span class="flex">{{ $lesson->steps->count() }} Steps</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu">
                                    <ul class="list-unstyled collapse @if($loop->iteration == 1) show @endif" id="toc-content-{{ $lesson->id }}">
                                        
                                        @foreach( $lesson->steps as $step )

                                        <li class="accordion__menu-link">
                                            <span class="material-icons icon-16pt icon--left text-body">{{ config('stepicons')[$step['type']] }}</span>
                                            <a class="flex" href="{{ route('admin.lessons.show', $lesson->id) }}?step={{ $step->id }}">
                                                Step {{ $step['step'] }} : <span>{{ $step['title'] }}</span>
                                            </a>
                                            <span class="text-muted">1h 30m</span>
                                        </li>

                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>

                    @endforeach
                </div>
            </div>
            <div class="page-section col-lg-5 border-left-2 dv-sticky">
                <div class="container page__container">
                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">About this course</div>
                        </div>
                        <div class="course-description"></div>
                    </div>

                    <div class="mb-lg-64pt">
                        <div class="page-separator">
                            <div class="page-separator__text">What you’ll learn</div>
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
                            <div class="page-separator__text">About the Teachers</div>
                        </div>

                        @foreach($course->teachers as $teacher)

                        <div class="pt-sm-32pt pt-md-0 d-flex flex-column">
                            <div class="avatar avatar-xl avatar-online mb-lg-16pt">
                                @if(empty($teacher->avatar))
                                <span class="avatar-title rounded-circle">{{ mb_substr($teacher->name, 0, 2) }}</span>
                                @else
                                    <img src="{{ asset('/storage/avatars') }}/{{ $teacher->avatar }}" 
                                        alt="{{ $teacher->name }}" class="avatar-img rounded-circle">
                                @endif
                            </div>
                            <h4 class="m-0">{{ $teacher->name }}</h4>
                            <p class="lh-1">
                                <small class="text-muted">Angular, Web Development</small>
                            </p>
                            <div class="d-flex flex-column flex-sm-row align-items-center justify-content-start">
                                <a href=""
                                    class="btn btn-outline-primary mb-16pt mb-sm-0 mr-sm-16pt">Follow</a>
                                <a href="" class="btn btn-outline-secondary">View Profile</a>
                            </div>
                        </div>

                        @endforeach
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="page-section bg-alt border-top-2 border-bottom-2">

        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">Student Feedback</div>
            </div>
            <div class="row mb-32pt">
                <div class="col-md-3 mb-32pt mb-md-0">
                    <div class="display-1">{{ number_format($course_rating, 1) }}</div>
                    <div class="rating rating-24">
                        @for($r = 1; $r <= $course_rating; $r++)
                        <span class="rating__item"><span class="material-icons">star</span></span>
                        @endfor

                        @if($course_rating > ($r-1))
                        <span class="rating__item"><span class="material-icons">star_half</span></span>
                        @else
                        <span class="rating__item"><span class="material-icons">star_border</span></span>
                        @endif

                        @for($r_a = $r; $r < 5; $r++)
                        <span class="rating__item"><span class="material-icons">star_border</span></span>
                        @endfor
                    </div>
                    <p class="text-muted mb-0">{{ $total_ratings }} ratings</p>
                </div>
                <div class="col-md-9">

                    <?php
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
                    ?>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip" data-title="{{ $percent_5 }}% rated 5/5"
                        data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="{{ $percent_5 }}"
                                    style="width: {{ $percent_5 }}%" aria-valuemin="0" aria-valuemax="100"></div>
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
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip" data-title="{{ $percent_4 }}% rated 4/5"
                        data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="{{ $percent_4 }}"
                                    style="width: {{ $percent_4 }}%" aria-valuemin="0" aria-valuemax="100"></div>
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
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip" data-title="{{ $percent_3 }}% rated 3/5"
                        data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="{{ $percent_3 }}"
                                    style="width: {{ $percent_3 }}%" aria-valuemin="0" aria-valuemax="100"></div>
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
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip" data-title="{{ $percent_2 }}% rated 2/5"
                        data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="{{ $percent_2 }}"
                                    style="width: {{ $percent_2 }}%" aria-valuemin="0" aria-valuemax="100"></div>
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
                        </div>
                    </div>
                    <div class="row align-items-center mb-8pt" data-toggle="tooltip" data-title="{{ $percent_1 }}% rated 0/5"
                        data-placement="top">
                        <div class="col-md col-sm-6">
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-secondary" role="progressbar" aria-valuenow="{{ $percent_1 }}"
                                    aria-valuemin="{{ $percent_1 }}" aria-valuemax="100"></div>
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
                        </div>
                    </div>

                </div>
            </div>

            @foreach($course->reviews as $review)
            <div class="pb-16pt mb-16pt border-bottom row">
                <div class="col-md-3 mb-16pt mb-md-0">
                    <div class="d-flex">
                        <a href="" class="avatar avatar-sm mr-12pt">
                            @if(!empty($review->user->avatar))
                            <img src="{{ asset('storage/avatars/' . $review->user->avatar ) }}" alt="avatar" class="avatar-img rounded-circle">
                            @else
                            <span class="avatar-title rounded-circle">{{ mb_substr($review->user->name, 0, 2) }}</span>
                            @endif
                        </a>
                        <div class="flex">
                            <p class="small text-muted m-0">{{ $review->created_at->diffforhumans() }}</p>
                            <a href="" class="card-title">{{ $review->user->name }}</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="rating mb-8pt">
                        @for($r = 1; $r <= $review->rating; $r++)
                        <span class="rating__item"><span class="material-icons">star</span></span>
                        @endfor

                        @if($review->rating > ($r-1))
                        <span class="rating__item"><span class="material-icons">star_half</span></span>
                        @else
                        <span class="rating__item"><span class="material-icons">star_border</span></span>
                        @endif


                        @for($r_a = $r; $r < 5; $r++)
                        <span class="rating__item"><span class="material-icons">star_border</span></span>
                        @endfor
                    </div>
                    <p class="text-70 mb-0">{{ $review->content }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="page-section border-bottom-2 bg-alt">

        <div class="container page__container">
            <!-- Add Reviews -->
            <div class="page-separator">
                <div class="page-separator__text">Provide your review</div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="review-stars-item form-inline form-group">
                        <span class="form-label">Your Rating: </span>
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
                        if(isset($review)){
                            $review_route = route('admin.review.update',['id'=>$review->id]);
                        }else{
                            $review_route = route('admin.review.add', ['id'=>$course->id]);
                        }
                    @endphp
                    <form method="POST" action="{{ $review_route }}" id="frm_review">@csrf
                        <input type="hidden" name="rating" id="rating" value="0">
                        <label for="review" class="form-label">Message:</label>
                        <textarea name="review" class="form-control bg-light mb-3" id="review" rows="5" cols="20"></textarea>
                        <button type="submit" class="btn btn-primary" value="Submit">Add review Now</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container">
            <div class="page-heading">
                <h4>Top Development Courses</h4>
                <a href="" class="text-underline ml-sm-auto">See Development Courses</a>
            </div>

            <div class="position-relative carousel-card">
                <div class="js-mdk-carousel row d-block" id="carousel-courses1">

                    <a class="carousel-control-next js-mdk-carousel-control mt-n24pt" href="#carousel-courses1"
                        role="button" data-slide="next">
                        <span class="carousel-control-icon material-icons"
                            aria-hidden="true">keyboard_arrow_right</span>
                        <span class="sr-only">Next</span>
                    </a>

                    <div class="mdk-carousel__content">

                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal "
                                data-partial-height="44" data-toggle="popover" data-trigger="click">


                                <a href="" class="js-image" data-position="">
                                    <img src="{{ asset('assets/img/paths/angular_430x168.png') }}" alt="course">
                                    <span class="overlay__content align-items-start justify-content-start">
                                        <span class="overlay__action card-body d-flex align-items-center">
                                            <i class="material-icons mr-4pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <span
                                    class="corner-ribbon corner-ribbon--default-right-top corner-ribbon--shadow bg-accent text-white">NEW</span>

                                <div class="mdk-reveal__content">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="">Learn Angular
                                                    fundamentals</a>
                                                <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                            </div>
                                            <a href="" data-toggle="tooltip"
                                                data-title="Add Favorite" data-placement="top" data-boundary="window"
                                                class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rating flex">
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star_border</span></span>
                                            </div>
                                            <small class="text-50">6 hours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('assets/img/paths/angular_40x40@2x.png') }}" width="40" height="40"
                                            alt="Angular" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Learn Angular fundamentals</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span class="text-black-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">Learn the fundamentals of working with Angular and how
                                    to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Fundamentals of working with
                                                Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Create complete Angular
                                                applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Working with the Angular
                                                CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Understanding Dependency
                                                Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
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
                                            <p class="flex text-black-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="" class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>



                            </div>

                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal "
                                data-partial-height="44" data-toggle="popover" data-trigger="click">


                                <a href="" class="js-image" data-position="">
                                    <img src="{{ asset('assets/img/paths/swift_430x168.png') }}" alt="course">
                                    <span class="overlay__content align-items-start justify-content-start">
                                        <span class="overlay__action card-body d-flex align-items-center">
                                            <i class="material-icons mr-4pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="mdk-reveal__content">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="">Build an iOS
                                                    Application in Swift</a>
                                                <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                            </div>
                                            <a href="" data-toggle="tooltip"
                                                data-title="Remove Favorite" data-placement="top" data-boundary="window"
                                                class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite</a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rating flex">
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star_border</span></span>
                                            </div>
                                            <small class="text-50">6 hours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('assets/img/paths/swift_40x40@2x.png') }}" width="40" height="40"
                                            alt="Angular" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Build an iOS Application in Swift</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span class="text-black-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">Learn the fundamentals of working with Angular and how
                                    to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Fundamentals of working with
                                                Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Create complete Angular
                                                applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Working with the Angular
                                                CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Understanding Dependency
                                                Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
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
                                            <p class="flex text-black-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="" class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>



                            </div>

                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal "
                                data-partial-height="44" data-toggle="popover" data-trigger="click">


                                <a href="" class="js-image" data-position="">
                                    <img src="{{ asset('assets/img/paths/wordpress_430x168.png') }}" alt="course">
                                    <span class="overlay__content align-items-start justify-content-start">
                                        <span class="overlay__action card-body d-flex align-items-center">
                                            <i class="material-icons mr-4pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="mdk-reveal__content">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="">Build a WordPress
                                                    Website</a>
                                                <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                            </div>
                                            <a href="" data-toggle="tooltip"
                                                data-title="Add Favorite" data-placement="top" data-boundary="window"
                                                class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rating flex">
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star_border</span></span>
                                            </div>
                                            <small class="text-50">6 hours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('assets/img/paths/wordpress_40x40@2x.png') }}" width="40" height="40"
                                            alt="Angular" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Build a WordPress Website</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span class="text-black-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">Learn the fundamentals of working with Angular and how
                                    to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Fundamentals of working with
                                                Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Create complete Angular
                                                applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Working with the Angular
                                                CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Understanding Dependency
                                                Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
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
                                            <p class="flex text-black-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="" class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>



                            </div>

                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal "
                                data-partial-height="44" data-toggle="popover" data-trigger="click">


                                <a href="" class="js-image" data-position="left">
                                    <img src="{{ asset('assets/img/paths/react_430x168.png') }}" alt="course">
                                    <span class="overlay__content align-items-start justify-content-start">
                                        <span class="overlay__action card-body d-flex align-items-center">
                                            <i class="material-icons mr-4pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="mdk-reveal__content">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="">Become a React
                                                    Native Developer</a>
                                                <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                            </div>
                                            <a href="" data-toggle="tooltip"
                                                data-title="Add Favorite" data-placement="top" data-boundary="window"
                                                class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rating flex">
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star_border</span></span>
                                            </div>
                                            <small class="text-50">6 hours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('assets/img/paths/react_40x40@2x.png') }}" width="40" height="40"
                                            alt="Angular" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Become a React Native Developer</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span class="text-black-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">Learn the fundamentals of working with Angular and how
                                    to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Fundamentals of working with
                                                Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Create complete Angular
                                                applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Working with the Angular
                                                CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Understanding Dependency
                                                Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
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
                                            <p class="flex text-black-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="" class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>



                            </div>

                        </div>

                        <div class="col-12 col-sm-6 col-md-4 col-xl-3">

                            <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay mdk-reveal js-mdk-reveal "
                                data-partial-height="44" data-toggle="popover" data-trigger="click">


                                <a href="" class="js-image" data-position="left">
                                    <img src="{{ asset('assets/img/paths/react_430x168.png') }}" alt="course">
                                    <span class="overlay__content align-items-start justify-content-start">
                                        <span class="overlay__action card-body d-flex align-items-center">
                                            <i class="material-icons mr-4pt">play_circle_outline</i>
                                            <span class="card-title text-white">Preview</span>
                                        </span>
                                    </span>
                                </a>

                                <div class="mdk-reveal__content">
                                    <div class="card-body">
                                        <div class="d-flex">
                                            <div class="flex">
                                                <a class="card-title" href="">Become a React
                                                    Native Developer</a>
                                                <small class="text-50 font-weight-bold mb-4pt">Elijah Murray</small>
                                            </div>
                                            <a href="" data-toggle="tooltip"
                                                data-title="Add Favorite" data-placement="top" data-boundary="window"
                                                class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                        </div>
                                        <div class="d-flex">
                                            <div class="rating flex">
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star</span></span>
                                                <span class="rating__item"><span
                                                        class="material-icons">star_border</span></span>
                                            </div>
                                            <small class="text-50">6 hours</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="popoverContainer d-none">
                                <div class="media">
                                    <div class="media-left mr-12pt">
                                        <img src="{{ asset('assets/img/paths/react_40x40@2x.png') }}" width="40" height="40"
                                            alt="Angular" class="rounded">
                                    </div>
                                    <div class="media-body">
                                        <div class="card-title mb-0">Become a React Native Developer</div>
                                        <p class="lh-1 mb-0">
                                            <span class="text-black-50 small">with</span>
                                            <span class="text-black-50 small font-weight-bold">Elijah Murray</span>
                                        </p>
                                    </div>
                                </div>

                                <p class="my-16pt text-black-70">Learn the fundamentals of working with Angular and how
                                    to create basic applications.</p>

                                <div class="mb-16pt">
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Fundamentals of working with
                                                Angular</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Create complete Angular
                                                applications</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Working with the Angular
                                                CLI</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Understanding Dependency
                                                Injection</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-8pt">check</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>Testing with Angular</small></p>
                                    </div>
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
                                            <p class="flex text-black-50 lh-1 mb-0"><small>12 lessons</small></p>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span
                                                class="material-icons icon-16pt text-black-50 mr-4pt">assessment</span>
                                            <p class="flex text-black-50 lh-1 mb-0"><small>Beginner</small></p>
                                        </div>
                                    </div>
                                    <div class="col text-right">
                                        <a href="" class="btn btn-primary">Watch trailer</a>
                                    </div>
                                </div>



                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

<input type="hidden" id="course_description" value="{{ $course->description }}">
<div id="course_editor" style="display:none;"></div>

@push('after-scripts')
<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    $(document).ready(function(e) {

        var json_description = JSON.parse($('#course_description').val());
        var course_quill = new Quill('#course_editor');
        course_quill.setContents(json_description);
        var description_html = course_quill.root.innerHTML;
        $('div.course-description').html(description_html);

        $('input[name="stars"]').on('click', function(){
            $('#rating').val($(this).val());
        });
    });

    $('#frm_review').on('submit', function(e) {
        e.preventDefault();
        $(this).ajaxSubmit({
            success: function(res) {
                console.log(res);

            }
        });
    });
</script>
@endpush

@endsection