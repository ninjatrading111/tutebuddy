@extends('layouts.app')

@section('content')

@push('after-styles')
    <!-- jQuery Datatable CSS -->
    <link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">
    <link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.dashboard.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item active">
                        @lang('labels.backend.dashboard.title')
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">

        <!-- Search Section -->
        <div class="page-section pb-0">
            <div class="form-group" style="position: relative;">
                <div class="ui fluid category search course font-size-20pt">
                    <div class="ui icon input w-100">
                        <input class="prompt pb-16pt" type="text" placeholder="@lang('labels.backend.dashboard.search_teachers_placeholder')"
                        value="@if(isset($_GET['_q'])){{ $_GET['_q'] }}@endif">
                        <i class="search icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>
        </div>

        @if(count($purchased_courses) > 0)
        <div class="page-section">

            <!-- My Lessons Section -->
            @if(count($schedules) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.my_live_lessons')</strong></p>
                    <a href="{{ route('admin.student.liveSessions') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">
                        @lang('labels.backend.buttons.browse_all')
                    </a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-time"
                    data-lists-sort-desc="true">
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-time">Date</a></th>
                                <th>@lang('labels.backend.dashboard.table.start_time')</th>
                                <th>@lang('labels.backend.dashboard.table.end_time')</th>
                                <th>@lang('labels.backend.dashboard.table.course_title')</th>
                                <th>@lang('labels.backend.dashboard.table.lesson_title')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="schedule_list">

                            @foreach($schedules as $schedule)
                                <tr>
                                    <td></td>
                                    <td>
                                        <?php
                                            $new_date = new DateTime;
                                            $dayofweek = strtolower(App\Models\Schedule::WEEK_DAYS[\Carbon\Carbon::parse($schedule->date)->dayOfWeek]);
                                            $new_date->modify($dayofweek . ' this week');
                                        ?>
                                        <strong>
                                            {{ App\Models\Schedule::WEEK_DAYS[\Carbon\Carbon::parse($schedule->date)->dayOfWeek] }}, 
                                            {{ \Carbon\Carbon::parse($new_date)->toFormattedDateString() }}
                                        </strong>
                                    </td>
                                    <td>
                                        <strong>{{ timezone()->convertFromTimezone($schedule->start_time, $schedule->timezone, 'H:i:s') }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ timezone()->convertFromTimezone($schedule->end_time, $schedule->timezone, 'H:i:s') }}</strong>
                                    </td>
                                    <td>
                                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                            <div class="avatar avatar-sm mr-8pt">
                                                <span class="avatar-title rounded bg-primary text-white">
                                                    {{ mb_substr($schedule->course->title, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <small class="js-lists-values-project">
                                                        <strong>{{ $schedule->course->title }}</strong></small>
                                                    <small
                                                        class="js-lists-values-location text-50">{{ $schedule->course->teachers[0]->name }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                            <div class="avatar avatar-sm mr-8pt">
                                                <span class="avatar-title rounded bg-accent text-white">
                                                    {{ mb_substr($schedule->lesson->title, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <small class="js-lists-values-project">
                                                        <strong>{{ $schedule->lesson->title }}</strong></small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php
                                        if($schedule->lesson->lesson_type == 1) {
                                            $route = route('lessons.live', [$schedule->lesson->slug, $schedule->lesson->id, $schedule->id]);
                                        } else {
                                            $route = route('lessons.show', [$schedule->course->slug, $schedule->lesson->slug, 1]);
                                        }

                                        $result = live_schedule($schedule);
                                        ?>
                                        @if($result['status'])
                                        <a href="{{ $route }}" target="_blank" class="btn btn-primary btn-sm">Join</a>
                                        @else
                                        <button type="button" class="btn btn-md btn-outline-primary" disabled>Scheduled</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">@lang('labels.backend.dashboard.my_live_lessons')</small>
                </div>
            </div>
            @endif

            <!-- My Courses Section -->
            @if(count($purchased_courses) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.my_courses')</strong></p>
                    <a href="{{ route('admin.student.courses') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">
                        @lang('labels.backend.buttons.browse_all')
                    </a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-desc="true">
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>

                                <th>@lang('labels.backend.dashboard.table.title')</th>
                                <th>@lang('labels.backend.dashboard.table.admin')</th>
                                <th>@lang('labels.backend.dashboard.table.category')</th>
                                <th>@lang('labels.backend.dashboard.table.lessons')</th>
                                <th>@lang('labels.backend.dashboard.table.status')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="course_list">
                            @foreach($purchased_courses as $course)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ mb_substr($course->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong> {{ $course->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $course->slug }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded-circle">{{ mb_substr($course->teachers[0]->name, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">
                                                    {{ $course->teachers[0]->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">Teacher</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ $course->category->name }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $course->lessons->count() }}</strong>
                                </td>
                                <td>
                                    @if($course->published == 1)
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-status text-50 mb-4pt">Published</small>
                                            <span class="indicator-line rounded bg-primary"></span>
                                        </div>
                                    @else
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-status text-50 mb-4pt">Unpublished</small>
                                            <span class="indicator-line rounded bg-warning"></span>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    @if($loop->iteration > 1)
                                        @include('backend.buttons.show', ['show_route' => route('courses.show', $course->slug)])
                                    @else
                                        @include('backend.buttons.show', ['show_route' => route('courses.show', $course->slug), 'no_tooltip' => true])
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">@lang('labels.backend.dashboard.my_courses')</small>
                </div>
            </div>
            @endif

            <!-- My admins Section -->
            @if(count($teachers) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <span class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.my_admins')</strong></span>  
                    <a href="{{ route('admin.student.admins') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">
                        @lang('labels.backend.buttons.browse_all')
                    </a>
                </div>
                <div class="table-responsive" data-toggle="lists">
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>

                                <th>@lang('labels.backend.dashboard.table.name')</th>
                                <th>@lang('labels.backend.dashboard.table.email')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>
                        <tbody class="list" id="admin_list">
                            @foreach($teachers as $teacher)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            @if(empty($teacher->avatar))
                                            <span class="avatar-title rounded-circle">{{ mb_substr($teacher->name, 0, 2) }}</span>
                                            @else
                                            <img src="{{ asset('/storage/avatars/' . $teacher->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $teacher->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $teacher->headline }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $teacher->email }}</td>
                                <td>
                                    <!-- <a href="" target="_blank" class="btn btn-primary btn-sm">Follow</a> -->
                                    <a href="{{ route('profile.show', $teacher->uuid) }}" class="btn btn-accent btn-sm">View Profile</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">@lang('labels.backend.dashboard.my_admins')</small>
                </div>
            </div>
            @endif

            <!-- My Assignments Section -->
            @if(count($assignments) > 0)
            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.dashboard.my_assignments')</strong></p>
                    <a href="{{ route('admin.student.assignments') }}" class="btn btn-md btn-outline-accent-dodger-blue float-right">@lang('labels.backend.buttons.browse_all')</a>
                </div>
                <div class="table-responsive" data-toggle="lists" data-lists-sort-desc="true">
                    <table id="tbl_assignment" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>@lang('labels.backend.dashboard.table.subject')</th>
                                <th>@lang('labels.backend.dashboard.table.due_date')</th>
                                <th>@lang('labels.backend.dashboard.table.total_marks')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>

                        <tbody class="list" id="assignment">
                            @foreach($assignments as $assignment)
                            <tr>
                                <td></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">
                                                {{ mb_substr($assignment->title, 0, 2) }}
                                            </span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong> {{ $assignment->title }}</strong></small>
                                                <small class="text-70">
                                                    Course: {{ $assignment->lesson->course->title }} |
                                                    Lesson: {{ $assignment->lesson->title }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $assignment->due_date }}</strong></td>
                                <td><strong>{{ $assignment->total_mark }}</strong></td>
                                <td>
                                    @if($loop->iteration > 1)
                                        @include('backend.buttons.show', [
                                            'show_route' => route('student.assignment.show', [$assignment->lesson->slug, $assignment->id])
                                        ])
                                    @else
                                        @include('backend.buttons.show', [
                                            'show_route' => route('student.assignment.show', [$assignment->lesson->slug, $assignment->id]),
                                            'no_tooltip' => true
                                        ])
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <small class="text-muted">@lang('labels.backend.dashboard.my_assignments')</small>
                </div>
            </div>
            @endif

            <!-- My Paths -->
            @if(count($bundles) > 0)
            <div class="page-separator d-flex">
                <div class="page-separator__text">@lang('labels.backend.dashboard.my_paths')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.student.bundles') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">
                            @lang('labels.backend.buttons.browse_all')
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row mb-16pt">
                @foreach($bundles as $bundle)
                <div class="col-sm-4 card-group-row__col">
                    <div class="card card-sm stack stack--1 card-group-row__card">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center">
                                <div class="flex">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded mr-12pt z-0 o-hidden">
                                            <div class="overlay">
                                                <a href="{{ route('bundles.show', $bundle->slug) }}" target="_blank">
                                                @if(!empty($bundle->bundle_image))
                                                <img src="{{ asset('/storage/uploads/thumb/'. $bundle->bundle_image) }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @else
                                                <img src="{{ asset('/assets/img/no-image-thumb.jpg') }}" width="40" height="40" alt="{{ $bundle->title }}" class="rounded">
                                                @endif
                                                </a>
                                            </div>
                                        </div>
                                        <div class="flex">
                                            <a href="{{ route('bundles.show', $bundle->slug) }}" target="_blank">
                                                <div class="card-title">{{ $bundle->title }}</div>
                                                <p class="flex text-black-50 lh-1 mb-0"><small>{{ $bundle->courses->count() }} courses</small></p>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <a href="" data-toggle="tooltip" data-title="Add Favorite" data-placement="top" data-boundary="window" class="ml-4pt material-icons text-20 card-course__icon-favorite">favorite_border</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- My Tests -->
            @if(count($testResults) > 0)
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.my_tests')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.student.tests') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">
                            @lang('labels.backend.buttons.browse_all')
                        </a>
                    </div>
                </div>
            </div>

            <div class="row card-group-row mb-16pt">

                @foreach($testResults as $testResult)

                    @if(!empty($testResult->test->course))

                    <div class="card-group-row__col col-md-6">

                        <div class="card card-group-row__card card-sm">
                            <div class="card-body d-flex align-items-center">
                                <a href="{{ route('student.test.show', [ $testResult->test->lesson->slug, $testResult->test->id]) }}"
                                    class="avatar overlay overlay--primary avatar-4by3 mr-12pt">
                                    <img src="{{ asset('/storage/uploads/thumb/' . $testResult->test->course->course_image ) }}"
                                        alt="{{ $testResult->test->title }}" class="avatar-img rounded">
                                </a>
                                <div class="flex mr-12pt">
                                    <a class="card-title" href="{{ route('student.test.show', [ $testResult->test->lesson->slug, $testResult->test->id]) }}">{{ $testResult->test->title }}</a>
                                    <div class="card-subtitle text-50">
                                        {{ Carbon\Carbon::parse($testResult->updated_at)->diffForHumans() }}
                                    </div>
                                </div>
                                <div class="d-flex flex-column align-items-center">
                                    <span class="lead text-headings lh-1">{{ $testResult->test_result }}</span>
                                    <small class="text-50 text-uppercase text-headings">Score</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    @endif

                @endforeach

            </div>
            @endif

            <!-- My Discussions Section -->
            @if(count($discussions) > 0)
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.discussions')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div style="padding-left: 8px; background-color: #f5f7fa;">
                        <a href="{{ route('admin.discussions.topics') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">
                            @lang('labels.backend.buttons.browse_all')
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                @foreach($discussions as $discussion)
                <div class="list-group-item p-3">
                    <div class="row align-items-start">
                        <div class="col-md-3 mb-8pt mb-md-0">
                            <div class="media align-items-center">
                                <div class="media-left mr-12pt">
                                    <a href="" class="avatar avatar-sm">
                                        @if(!empty($discussion->user->avatar))
                                        <img src="{{ asset('/storage/avatars/' . $discussion->user->avatar) }}" alt="{{ $discussion->user->avatar }}"
                                        class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ mb_substr($discussion->user->name, 0, 2) }}</span>
                                        @endif
                                    </a>
                                </div>
                                <div class="d-flex flex-column media-body media-middle">
                                    <a href="" class="card-title">{{ $discussion->user->name }}</a>
                                    <small class="text-muted">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->updated_at))->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col mb-8pt mb-md-0">
                            <p class="mb-8pt">
                                <a href="{{ route('admin.discussions.show', $discussion->id) }}" class="text-body">
                                    <strong>{{ $discussion->title }}</strong></a>
                            </p>

                            <?php $topics = json_decode($discussion->topics); ?>
                            @foreach($topics as $topic)
                            <a href="{{ route('admin.discussions.show', $discussion->id) }}" class="chip chip-outline-secondary">
                                {{ $discussion->topic($topic) }}
                            </a>
                            @endforeach
                        </div>
                        <div class="col-auto d-flex flex-column align-items-center justify-content-center">
                            <h5 class="m-0">{{ $discussion->results->count() }}</h5>
                            <p class="lh-1 mb-0"><small class="text-70">answers</small></p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

        </div>
        @endif

        @if(count($purchased_courses) < 1)
        <div class="page-section card card-body mt-64pt">
            <div class="row align-items-center">
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">1</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.backend.dashboard.select_course')</div>
                        <p class="card-subtitle text-black-70">Wide selection of subjects you can learn from expert
                            tutors.</p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">2</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.backend.dashboard.find_an_expert')</div>
                        <p class="card-subtitle text-black-70">Select from the most experienced &amp; requted admins.
                        </p>
                    </div>
                </div>
                <div class="d-flex col-md align-items-center">
                    <div class="rounded-circle bg-primary w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        <span class="h3 text-white m-0">3</span>
                    </div>
                    <div class="flex">
                        <div class="card-title mb-4pt">@lang('labels.backend.dashboard.start_learning')</div>
                        <p class="card-subtitle text-black-70">Get personal instruction on your chosen course.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories -->
        <div class="page-section">

            <div class="row card-group-row">
                @foreach($parentCategories as $category)
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
                                <a href="/search/courses?_q={{ $category->name }}&_t=category&_k={{ $category->id }}" class="card-title mr-3">{{ $category->name }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        @endif
    </div>
</div>
<!-- // END Header Layout Content -->

@include('layouts.parts.search-script')

@push('after-scripts')

    <script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

    <script>
        $(function () {
            //
        });
    </script>

@endpush

@endsection
