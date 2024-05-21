@extends('layouts.app')

@section('content')

<div class="mdk-header-layout__content page-content">

    <div class="mdk-box bg-primary mdk-box--bg-gradient-primary2 js-mdk-box mb-0" data-effects="blend-background">
        <div class="mdk-box__content">
            <div class="hero py-64pt text-center text-sm-left">
                <div class="container page__container">
                    <h1 class="text-white">{{ $bundle->title }}</h1>
                    <p class="lead text-white-50 measure-hero-lead mb-24pt">{{ $bundle->description }}</p>

                    @if(auth()->check())
                        <a href="#" class="btn btn-outline-white mr-12pt"><i
                                class="material-icons icon--left">favorite_border</i> @lang('labels.frontend.buttons.add_favorite')</a>
                        <a href="#" class="btn btn-outline-white mr-12pt"><i class="material-icons icon--left">share</i>
                            @lang('labels.frontend.buttons.share')</a>
                    @endif
                </div>
            </div>

            <div
                class="navbar navbar-expand-sm navbar-light bg-white border-bottom-2 navbar-list p-0 m-0 align-items-center">
                <div class="container page__container">
                    <ul class="nav navbar-nav flex align-items-sm-center">
                        <li class="nav-item navbar-list__item">
                            <div class="media align-items-center">
                                <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                                    @if(empty($bundle->user->avatar))
                                        <span
                                            class="avatar-title rounded-circle">{{ mb_substr($bundle->user->name, 0, 2) }}</span>
                                    @else
                                        <img src="{{ asset('/storage/avatars/' . $bundle->user->avatar) }}"
                                            alt="{{ $bundle->user->name }}" class="avatar-img rounded-circle">
                                    @endif
                                </div>
                                <div class="media-body">
                                    <a class="card-title m-0"
                                        href="{{ route('profile.show', $bundle->user->uuid) }}">{{ $bundle->user->name }}</a>
                                    <p class="text-50 lh-1 mb-0">@lang('labels.frontend.bundle.instructor')</p>
                                </div>
                            </div>
                        </li>
                        <li class="nav-item navbar-list__item">
                            <i class="material-icons text-muted icon--left">assessment</i>
                            @if($bundle->category)
                            {{ $bundle->category->name }}
                            @else
                            No Category
                            @endif
                        </li>
                        <li class="nav-item ml-sm-auto text-sm-center flex-column navbar-list__item">
                            <div class="rating rating-24">
                                @include('layouts.parts.rating', ['rating' => $bundle_rating])
                            </div>
                            <p class="lh-1 mb-0"><small class="text-muted">{{ $total_ratings }} @lang('labels.frontend.general.ratings')</small></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.bundle.about_bundle')</div>
            </div>

            <div class="row">
                <div class="col-lg-7">

                    @if(isset($bundle->mediaVideo))

                    <div class="mb-32pt">
                        <div class="bg-primary embed-responsive embed-responsive-16by9"
                            data-domfactory-upgraded="player">
                            <div class="player embed-responsive-item">
                                <div class="player__content">
                                    <div class="player__image"
                                        style="--player-image: url({{ asset('storage/uploads/' . $bundle->bundle_image) }})">
                                    </div>
                                    <a href="" class="player__play bg-primary">
                                        <span class="material-icons">play_arrow</span>
                                    </a>
                                </div>
                                <div class="player__embed d-none">
                                    <?php
                                        $embed = Embed::make($bundle->mediaVideo->url)->parseUrl();
                                        $embed->setAttribute([
                                            'id'=>'display_bundle_video',
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

                    <div class="description font-size-16pt">
                        {{ $bundle->description }}
                    </div>
                </div>
                <div class="col-lg-5 justify-content-center">

                    <div class="card">
                        <div class="card-body py-16pt">

                            <div class="text-center">
                                <span
                                    class="icon-holder icon-holder--outline-secondary rounded-circle d-inline-flex mb-8pt">
                                    <i class="material-icons">timer</i>
                                </span>
                                <h4 class="card-title"><strong>@lang('labels.frontend.bundle.unlock')</strong></h4>
                                <p class="card-subtitle text-70 mb-24pt">@lang('string.frontend.bundle.unlock_description')</p>

                                @if(!auth()->check())
                                <a href="{{ route('register') }}" class="btn btn-accent mb-8pt">@lang('labels.frontend.bundle.signup')</a>
                                <p class="mb-0">@lang('string.frontend.bundle.have_account') <a href="{{ route('login') }}">@lang('labels.frontend.bundle.login')</a></p>
                                @endif

                            </div>

                            @if(auth()->check())
                            <div class="pl-5 pr-5">

                                <div class="form-group mb-32pt">
                                    <div class="custom-controls-stacked">
                                        <div class="custom-control custom-radio mb-16pt">
                                            <input id="enroll_group" name="enroll_type" type="radio" enroll-type="group"
                                                data-amount="{{ $bundle->group_price }}" class="custom-control-input" checked="">
                                            <label for="enroll_group" class="card-title custom-control-label">
                                                @lang('labels.frontend.bundle.group'): {{ $bundle->group_price . config('app.currency') }}
                                            </label>
                                        </div>
                                        <div class="custom-control custom-radio">
                                            <input id="enroll_private" name="enroll_type" type="radio" enroll-type="private"
                                                data-amount="{{ $bundle->private_price }}" class="custom-control-input">
                                            <label for="enroll_private" class="card-title custom-control-label">
                                                @lang('labels.frontend.bundle.individual'): {{ $bundle->private_price . config('app.currency') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->hasRole('Student'))
                                <form action="{{ route('cart.checkout') }}" method="POST" id="frm_checkout">@csrf
                                    <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                                    <input type="hidden" name="amount" value="{{ $bundle->group_price }}">
                                    <input type="hidden" name="type" value="group">
                                    <button class="btn btn-primary btn-block mb-8pt">@lang('labels.frontend.bundle.buy_now')</button>
                                </form>

                                <form action="{{ route('cart.addToCart') }}" method="POST" id="frm_cart">@csrf
                                    <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                                    <input type="hidden" name="amount" value="{{ $bundle->group_price }}">
                                    <input type="hidden" name="type" value="group">
                                    <button type="submit" class="btn btn-accent btn-block mb-8pt">
                                        @lang('labels.frontend.bundle.add_to_cart')</button>
                                </form>
                                @endif

                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2 bg-alt">
        <div class="container page__container">

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.bundle.courses')</div>
            </div>

            <div class="row card-group-row">
                @foreach($bundle->courses as $course)
                    <div class="col-lg-3 card-group-row__col">
                        <div class="card card-sm card--elevated p-relative o-hidden overlay overlay--primary-dodger-blue js-overlay card-group-row__card"
                            data-toggle="popover" data-trigger="click">
                            <a href="{{ route('courses.show', $course->slug) }}"
                                class="card-img-top js-image" data-position="" data-height="140">
                                @if(!empty($course->course_image))
                                    <img src="{{ asset('/storage/uploads/' . $course->course_image) }}"
                                        alt="course">
                                @else
                                    <img src="{{ asset('/assets/img/no-image.jpg') }}"
                                        alt="course">
                                @endif
                                <span class="overlay__content">
                                    <span class="overlay__action d-flex flex-column text-center">
                                        <i class="material-icons icon-32pt">play_circle_outline</i>
                                        <span class="card-title text-white">Resume</span>
                                    </span>
                                </span>
                            </a>

                            <div class="card-body flex">
                                <div class="d-flex">
                                    <div class="flex">
                                        <a class="card-title"
                                            href="{{ route('courses.show', $course->slug) }}">{{ $course->title }}</a>
                                        <small
                                            class="text-50 font-weight-bold mb-4pt">{{ $course->teachers[0]->name }}</small>
                                    </div>
                                    <a href="{{ route('courses.show', $course->slug) }}"
                                        data-toggle="tooltip" data-title="Add Favorite" data-placement="top"
                                        data-boundary="window"
                                        class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                                </div>
                                <div class="d-flex">
                                    <div class="rating flex">
                                        @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                    </div>
                                    <!-- <small class="text-50">6 hours</small> -->
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row justify-content-between">
                                    <div class="col-auto d-flex align-items-center">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                        <p class="flex text-black-50 lh-1 mb-0"><small>{{ $course->duration() }}
                                            </small></p>
                                    </div>
                                    <div class="col-auto d-flex align-items-center">
                                        <span
                                            class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                        <p class="flex text-black-50 lh-1 mb-0">
                                            <small>{{ $course->lessons->count() }} @lang('labels.frontend.bundle.lessons')</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="popoverContainer d-none">
                            <div class="media">
                                <div class="media-left mr-12pt">
                                    @if(!empty($course->course_image))
                                        <img src="{{ asset('/storage/uploads/' . $course->course_image) }}"
                                            width="40" height="40" alt="Angular" class="rounded">
                                    @else
                                        <img src="{{ asset('/assets/img/no-image.jpg') }}"
                                            width="40" height="40" alt="Angular" class="rounded">
                                    @endif
                                </div>
                                <div class="media-body">
                                    <div class="card-title mb-0">{{ $course->title }}</div>
                                    <p class="lh-1 mb-0">
                                        <span class="text-black-50 small">@lang('labels.frontend.bundle.with')</span>
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
                                        <p class="flex text-black-50 lh-1 mb-0">
                                            <small>{{ $lesson->title }}</small></p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="my-32pt">
                                <div class="d-flex align-items-center mb-8pt justify-content-center">
                                    <div class="d-flex align-items-center mr-8pt">
                                        <span class="material-icons icon-16pt text-black-50 mr-4pt">access_time</span>
                                        <p class="flex text-black-50 lh-1 mb-0">
                                            <small>{{ $course->duration() }}</small></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <span
                                            class="material-icons icon-16pt text-black-50 mr-4pt">play_circle_outline</span>
                                        <p class="flex text-black-50 lh-1 mb-0">
                                            <small>{{ $course->lessons->count() }} @lang('labels.frontend.bundle.lessons')</small></p>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-center">
                                    <a href="{{ route('courses.show', $course->slug) }}"
                                        class="btn btn-primary mr-8pt">Resume</a>
                                    <a href="{{ route('courses.show', $course->slug) }}"
                                        class="btn btn-outline-secondary ml-0">
                                        Start over</a>
                                </div>
                            </div>

                            <div class="d-flex align-items-center">
                                <small class="text-black-50 mr-8pt">@lang('labels.frontend.bundle.your_rating')</small>
                                <div class="rating mr-8pt">
                                    @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                </div>
                                <small
                                    class="text-black-50">{{ $course->reviews->avg('rating') }}/5</small>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    <div class="page-section border-bottom-2">

        <div class="container page__container">
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.bundle.student_feedback')</div>
            </div>
            <div class="row mb-32pt">
                <div class="col-md-3 mb-32pt mb-md-0">
                    <div class="display-1">{{ number_format($bundle_rating, 1) }}</div>
                    <div class="rating rating-24">
                        @include('layouts.parts.rating', ['rating' => $bundle_rating])
                    </div>
                    <p class="text-muted mb-0">{{ $total_ratings }} @lang('labels.frontend.general.ratings')</p>
                </div>
                <div class="col-md-9">

                    <?php
                        
                        if($total_ratings > 0) {
                            $ratings_5 = $bundle->reviews()->where('rating', '=', 5)->get()->count();
                            $percent_5 = number_format(($ratings_5 / $total_ratings) * 100, 1);
                            $ratings_4 = $bundle->reviews()->where('rating', '=', 4)->get()->count();
                            $percent_4 = number_format(($ratings_4 / $total_ratings) * 100, 1);
                            $ratings_3 = $bundle->reviews()->where('rating', '=', 3)->get()->count();
                            $percent_3 = number_format(($ratings_3 / $total_ratings) * 100, 1);
                            $ratings_2 = $bundle->reviews()->where('rating', '=', 2)->get()->count();
                            $percent_2 = number_format(($ratings_2 / $total_ratings) * 100, 1);
                            $ratings_1 = $bundle->reviews()->where('rating', '=', 1)->get()->count();
                            $percent_1 = number_format(($ratings_1 / $total_ratings) * 100, 1);
                        } else {
                            $percent_5 = 0;
                            $percent_4 = 0;
                            $percent_3 = 0;
                            $percent_2 = 0;
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
                        @for($r = 1; $r <= $review->rating; $r++)
                            <span class="rating__item">
                                <span class="material-icons">star</span>
                            </span>
                            @endfor

                            @if($review->rating > ($r-1))
                            <span class="rating__item"><span class="material-icons">star_half</span></span>
                            @else
                            <span class="rating__item"><span class="material-icons">star_border</span></span>
                            @endif

                            @for($r_a = $r; $r < 5; $r++) <span class="rating__item">
                                <span class="material-icons">star_border</span>
                                </span>
                                @endfor
                    </div>
                    <p class="text-70 mb-0">{{ $review->content }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@push('after-scripts')

<script>
    $(function() {
        $('.player__play').on('click', function(e) {
            e.preventDefault();
            $(this).closest('.player').find('.player__embed').removeClass('d-none');
        });
    });
</script>
@endpush

@endsection
