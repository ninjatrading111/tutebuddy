@extends('layouts.app')

@section('content')

@push('after-styles')

<link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">

<style>
    [dir=ltr] .list-group-flush>.list-group-item {
        border-width: 0 0 5px;
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
        
    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.courses.my_favorites.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.courses.my_favorites.title')
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="page-section">
        <div class="container page__container" style="min-height: 50vh;">

            @if(count($courses) > 0)

            <div class="card">

                <div class="list-group list-group-flush">

                    @foreach($courses as $course)

                    <div class="list-group-item p-3">
                        <div class="align-items-start">
                            <div class="media">
                                <div class="media-left mr-12pt">
                                    <a href="{{ route('courses.show', $course->slug) }}" class="avatar avatar-xxl mr-3">
                                        @if(!empty($course->course_image))
                                        <img src="{{ asset('/storage/uploads/' . $course->course_image) }}" 
                                            alt="{{ $course->title }}" class="avatar-img rounded" >
                                        @else
                                        <img src="{{ asset('/assets/img/no-image.jpg') }}" 
                                            alt="{{ $course->title }}" class="avatar-img rounded" >
                                        @endif
                                    </a>
                                    <div class="d-flex p-1" style="white-space: nowrap;">
                                        <div class="rating mr-4pt">
                                            @if($course->reviews->count() > 0)
                                            @include('layouts.parts.rating', ['rating' => $course->reviews->avg('rating')])
                                            @else
                                                <small class="text-50">@lang('labels.backend.general.no_rating') </small>
                                            @endif
                                        </div>
                                        @if($course->reviews->count() > 0)
                                        <small class="text-50">{{ number_format($course->reviews->avg('rating'), 2) }}/5</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-body media-middle">
                                    <div class="d-flex">
                                        <div class="flex">
                                            <a href="{{ route('courses.show', $course->slug) }}" class="card-title">{{ $course->title }}</a>
                                        </div>
                                        <span class="card-title text-accent mr-16pt">
                                            {{ config('app.currency') . $course->group_price }} <small class="text-50">(@lang('labels.backend.general.group'))</small>
                                        </span>
                                        <span class="card-title text-primary mr-16pt">
                                            {{ config('app.currency') . $course->private_price }} <small class="text-50">(@lang('labels.backend.general.private'))</small>
                                        </span>
                                        
                                        <a href="{{ route('admin.course.removeFavorite', $course->id) }}" name="remove_favorite" data-toggle="tooltip" 
                                            data-title="Remove Favorite" data-placement="top" 
                                            data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite font-color-red" disabled
                                            data-original-title="" title="">favorite</a>
                                    </div>
                                    <div class="d-flex">
                                        <span class="text-70 text-muted mr-8pt"><strong>@lang('labels.backend.general.session_time'): {{ $course->duration() }},</strong></span>
                                        <span class="text-70 text-muted mr-8pt"><strong>@lang('labels.backend.general.sessions'): {{ $course->lessons->count() }},</strong></span>
                                        <span class="text-70 text-muted mr-8pt"><strong>@lang('labels.backend.general.category'): 
                                            @if(!empty($course->category))
                                            {{ $course->category->name }},
                                            @else
                                            No Category
                                            @endif
                                            </strong>
                                        </span>
                                        <span class="text-70 text-muted mr-8pt"><strong>@lang('labels.backend.general.level'): {{ $course->level->name }}</strong></span>
                                    </div>
                                    <div class="page-separator mb-0">
                                        <div class="page-separator__text bg-transparent">&nbsp;</div>
                                    </div>
                                    <div class="">
                                        <p class="text-muted">{{ $course->short_description }}</p>
                                    </div>
                                    <div class="d-flex align-items-end">
                                        <a href="" class="flex">
                                            <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                                <div class="avatar avatar-sm mr-8pt">
                                                    <img src="{{ asset('/storage/avatars/' . $course->teachers[0]->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                                </div>
                                                <div class="media-body">

                                                    <div class="d-flex align-items-center">
                                                        <div class="flex d-flex flex-column">
                                                            <p class="mb-0"><strong class="js-lists-values-lead">{{ $course->teachers[0]->name }}</strong></p>
                                                            <small class="js-lists-values-email text-50">
                                                                {{ $course->teachers[0]->headline }},
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                        <!-- <div class="flex">
                                            <span class="text-70 text-muted mr-8pt">Listed Courses: 12</span>
                                            <span class="text-70 text-muted mr-8pt">Total Courses Contucted: 102</span>
                                        </div> -->
                    
                                        @if(!$course->isEnrolled())
                                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-primary btn-md">@lang('labels.backend.buttons.enroll')</a>
                                        @else
                                        <a href="{{ route('courses.show', $course->slug) }}" class="btn btn-success btn-md">@lang('labels.backend.buttons.enrolled')</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endforeach

                </div>

                @if($courses->hasPages())
                <div class="card-footer p-8pt">
                    {{ $courses->links('layouts.parts.page') }}
                </div>
                @endif
            </div>

            @else
            <div class="card card-body">
                <p class="card-title">@lang('labels.backend.courses.my_favorites.no_results')</p>
            </div>
            @endif
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->
@include('layouts.parts.search-script');

@push('after-scripts')

<script>

$(function(e) {

    $('[rel=tooltip]').tooltip({ trigger: "hover" });

    $('a[name="remove_favorite"]').on('click', function(e) {
        e.preventDefault();
        var route = $(this).attr('href');
        var btn = $(this);

        $.ajax({
            method: 'GET',
            url: route,
            success: function(res) {
                if(res) {
                    var ele = btn.closest('div.list-group-item');
                    ele.toggle( function() { 
                        $(this).remove();
                        $('div[role="tooltip"]').remove();
                    });
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});

</script>

@endpush

@endsection