@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<?php

function get_badge($percent) {
    if($percent >= 70 && $percent < 80 ) {
        return 'bronze-badge.png';
    }

    if($percent >= 80 && $percent < 90 ) {
        return 'silver-badge.png';
    }

    if($percent >= 90 && $percent <= 100 ) {
        return 'gold-badge.png';
    }

    return false;
}

function get_result($percent) {
    if($percent >= 35) {
        return '<span class="text-success text-bold">PASS</span>';
    } else {
        return 'FAIL';
    }
}

?>

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.results.performance_detail.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.results.performance_detail.title')
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.students.enrolled') }}" class="btn btn-outline-secondary">
                        @lang('labels.general.back')
                    </a>
                </div>
            </div>
            <div class="row" role="tablist">
                <div class="col-auto ml-2">
                    @if(!$user->certificates()->where('course_id', '=', $course->id)->first())
                    <form method="post" action="{{route('admin.certificates.generate')}}" style="display: inline-block;">
                        @csrf
                        <input type="hidden" value="{{$course->id}}" name="course_id">
                        <input type="hidden" value="{{$user->id}}" name="user_id">
                        <button class="btn btn-primary">
                            <i class="material-icons icon--left">done</i>
                            @lang('labels.backend.course.generate_certificate')
                        </button>
                    </form>
                    @else
                    <button disabled="disabled" class="btn btn-primary">
                        <i class="material-icons icon--left">done</i> @lang('labels.frontend.course.certified')
                    </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="form-group mb-32pt">
            <p class="font-size-16pt mb-8pt"><strong>@lang('labels.backend.general.course'):</strong> {{ $course->title }}</p>
            <p class="font-size-16pt mb-8pt"><strong>@lang('labels.backend.general.student'):</strong> {{ $user->name }}</p>
            <p class="font-size-16pt"><strong>@lang('labels.backend.general.course_progress'):</strong> {{ $course->progress($user) }}%</p>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists">

                <table id="tbl_result" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th>@lang('labels.backend.table.lesson')</th>
                            <th>@lang('labels.backend.table.type')</th>
                            <th>@lang('labels.backend.table.date')</th>
                            <th>@lang('labels.backend.table.status')</th>
                        </tr>
                    </thead>

                    <tbody class="list">
                        @foreach($course->lessons as $lesson)
                        <tr>
                            <td>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">

                                        @if(!empty($lesson->image))
                                        <img src="{{ asset('storage/uploads/' . $lesson->image) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @else
                                        <span class="avatar-title rounded-circle">{{ mb_substr($lesson->title, 0, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="media-body">

                                        @php
                                            $lesson_title = $lesson->title;
                                            if ($lesson_title > 50) {
                                                $lesson_title = mb_substr($lesson->title, 0, 50) . '...';
                                            }
                                        @endphp

                                        <div class="d-flex align-items-center">
                                            <div class="flex d-flex flex-column">
                                                <p class="mb-0"><strong class="js-lists-values-lead">{{ $lesson_title }}</strong></p>
                                                <small class="js-lists-values-email text-50">{{ mb_substr($lesson->short_text, 0, 50) }}...</small>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </td>
                                
                            <td>
                                @if($lesson->lesson_type == 1)
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-warning"><i class="material-icons mr-8pt">star</i></a>
                                    <a href="#" class="text-70"><span class="js-lists-values-employer-name">Live Lesson</span></a>
                                </div>
                                @else
                                <div class="d-flex align-items-center">
                                    <a href="#" class="text-primary"><i class="material-icons mr-8pt">star</i></a>
                                    <a href="#" class="text-70"><span class="js-lists-values-employer-name">General Lesson</span></a>
                                </div>
                                @endif
                            </td>
                            <td>
                                <?php 
                                    $chapter = \App\Models\ChapterStudent::where('model_type', \App\Models\Lesson::class)
                                        ->where('model_id', $lesson->id)
                                        ->where('user_id', $user->id)
                                        ->where('course_id', $course->id)
                                        ->first();
  
                                    if($chapter) {
                                        $date = $chapter->created_at->format('m/d/Y');
                                        $date_human = $chapter->created_at->diffForHumans();
                                    } else {
                                        $date = \Carbon\Carbon::parse($lesson->created_at)->format('m/d/Y');
                                        $date_human = \Carbon\Carbon::parse($lesson->created_at)->diffForHumans();
                                    }
                                    
                                ?>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-date"><strong>{{ $date }}</strong></small>
                                    <small class="text-50">{{ $date_human }}</small>
                                </div>
                            </td>
                            <td>
                                @if($lesson->isCompleted($user->id))
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Completed</small>
                                    <span class="indicator-line rounded bg-success"></span>
                                </div>
                                @else
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Not Taken</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists">

                <table id="tbl_result" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th>@lang('labels.backend.table.type')</th>
                            <th>@lang('labels.backend.table.title')</th>
                            <th>@lang('labels.backend.table.date')</th>
                            <th>@lang('labels.backend.table.score')</th>
                            <th>@lang('labels.backend.table.percentage')</th>
                            <th>@lang('labels.backend.table.grade')</th>
                            <th>@lang('labels.backend.table.badge')</th>
                            <th>@lang('labels.backend.table.result')</th>
                        </tr>
                    </thead>

                    <tbody class="list">

                        <!-- Assignments -->
                    
                        @if(count($assignments) > 0)

                            @foreach($assignments as $assignment)

                            <tr>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-accent text-white">AS</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $assignment->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $assignment->lesson->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>
                                        @if($assignment->result($user->id))
                                        {{ $assignment->result($user->id)->submit_date }}
                                        @else
                                        N/A
                                        @endif
                                    </strong>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    @if($assignment->result($user->id))
                                                    <strong>{{ (int)$assignment->result($user->id)->mark }} / {{ $assignment->total_mark }}</strong>
                                                    @else
                                                    <strong>(Not Taken) / {{ $assignment->total_mark }}</strong>
                                                    @endif
                                                </small>
                                                <small class="js-lists-values-location text-50">
                                                    Marks Scored / Total Marks
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">
                                        @if($assignment->result($user->id))
                                        {{ round($assignment->result($user->id)->mark / $assignment->total_mark * 100) }}% 
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td>
                                    @if($assignment->result($user->id))
                                        <?php $badge = get_badge(round($assignment->result($user->id)->mark / $assignment->total_mark * 100)) ?>
                                        @if($badge)
                                        <div class="avatar avatar-sm mr-8pt">
                                            <img src="{{ asset('/images/' . $badge) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->result($user->id))
                                    <strong><?php echo get_result(round($assignment->result($user->id)->mark / $assignment->total_mark * 100)); ?></strong>
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            
                            @endforeach

                        @endif

                        <!-- Tests -->

                        @if(count($tests) > 0)

                            @foreach($tests as $test)

                            <tr>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-info text-white">TE</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $test->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $test->lesson->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>
                                    @if($test->result($user->id))
                                        {{ $test->result($user->id)->submit_date }}
                                        @else
                                        N/A
                                    @endif
                                    </strong>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    @if($test->result($user->id))
                                                    <strong>{{ (int)$test->result($user->id)->mark }} / {{ $test->score }}</strong>
                                                    @else
                                                    <strong>(Not Taken) / {{ $test->score }}</strong>
                                                    @endif
                                                </small>
                                                <small class="js-lists-values-location text-50">
                                                    Marks Scored / Total Marks
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">
                                        @if($test->result($user->id))
                                        {{ round($test->result($user->id)->mark / $test->score * 100) }}%
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td>
                                    @if($test->result($user->id))
                                        <?php $badge = get_badge(round($test->result($user->id)->mark / $test->score * 100)) ?>
                                        @if($badge)
                                        <div class="avatar avatar-sm mr-8pt">
                                            <img src="{{ asset('/images/' . $badge) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($test->result($user->id))
                                    <strong><?php echo get_result(round($test->result($user->id)->mark / $test->score * 100)) ?></strong>
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            
                            @endforeach

                        @endif

                        <!-- Quiz -->

                        @if(count($quizs) > 0)

                            @foreach($quizs as $quiz)

                            <tr>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">QU</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $quiz->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $quiz->lesson->title }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <strong>
                                    @if($quiz->result($user->id))
                                        {{ $quiz->result($user->id)->updated_at }}
                                        @else
                                        N/A
                                    @endif
                                    </strong>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    @if($quiz->result($user->id))
                                                    <strong>{{ (int)$quiz->result($user->id)->quiz_score }} / {{ $quiz->score }}</strong>
                                                    @else
                                                    <strong>(Not Taken) / {{ $quiz->score }}</strong>
                                                    @endif
                                                </small>
                                                <small class="js-lists-values-location text-50">
                                                    Marks Scored / Total Marks
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-status text-50 mb-4pt">
                                        @if($quiz->result($user->id))
                                        {{ $quiz->result($user->id)->quiz_result }}% 
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td>
                                    @if($quiz->result($user->id))
                                        <?php $badge = get_badge($quiz->result($user->id)->quiz_result) ?>
                                        @if($badge)
                                        <div class="avatar avatar-sm mr-8pt">
                                            <img src="{{ asset('/images/' . $badge) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($quiz->result($user->id))
                                    <strong><?php echo get_result($quiz->result($user->id)->quiz_result) ?></strong>
                                    @else
                                    N/A
                                    @endif
                                </td>
                            </tr>
                            
                            @endforeach

                        @endif

                        @if(count($assignments) < 1 &&  count($tests) < 1 && count($quizs) < 1)

                            <tr>
                                <td colspan="8" class="text-center">
                                    <strong>No Tests created for this Course</strong>
                                </td>
                            </tr>

                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(function() {

    // 
});

</script>
@endpush

@endsection