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
                    <a href="{{ route('admin.results.student') }}" class="btn btn-outline-secondary">
                        @lang('labels.general.back')
                    </a>
                </div>
            </div>

        </div>
    </div>

    <div class="container page__container page-section">

        <div class="form-group mb-32pt">
            <p class="font-size-16pt mb-8pt"><strong>@lang('labels.backend.general.course'):</strong> {{ $course->title }}</p>
            <p class="font-size-16pt"><strong>@lang('labels.backend.general.student'):</strong> {{ auth()->user()->name }}</p>
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
                                        @if($assignment->result)
                                        {{ $assignment->result->submit_date }}
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
                                                    @if($assignment->result)
                                                    <strong>{{ (int)$assignment->result->mark }} / {{ $assignment->total_mark }}</strong>
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
                                        @if($assignment->result)
                                        {{ round($assignment->result->mark / $assignment->total_mark * 100) }}% 
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td>
                                    @if($assignment->result)
                                        <?php $badge = get_badge(round($assignment->result->mark / $assignment->total_mark * 100)) ?>
                                        @if($badge)
                                        <div class="avatar avatar-sm mr-8pt">
                                            <img src="{{ asset('/images/' . $badge) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($assignment->result)
                                    <strong><?php echo get_result(round($assignment->result->mark / $assignment->total_mark * 100)); ?></strong>
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
                                    @if($test->result)
                                        {{ $test->result->submit_date }}
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
                                                    @if($test->result)
                                                    <strong>{{ (int)$test->result->mark }} / {{ $test->score }}</strong>
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
                                        @if($test->result)
                                        {{ round($test->result->mark / $test->score * 100) }}%
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td>
                                    @if($test->result)
                                        <?php $badge = get_badge(round($test->result->mark / $test->score * 100)) ?>
                                        @if($badge)
                                        <div class="avatar avatar-sm mr-8pt">
                                            <img src="{{ asset('/images/' . $badge) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($test->result)
                                    <strong><?php echo get_result(round($test->result->mark / $test->score * 100)) ?></strong>
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
                                    @if($quiz->result)
                                        {{ $quiz->result->updated_at }}
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
                                                    @if($quiz->result)
                                                    <strong>{{ (int)$quiz->result->quiz_score }} / {{ $quiz->score }}</strong>
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
                                        @if($quiz->result)
                                        {{ $quiz->result->quiz_result }}% 
                                        @else
                                        N/A
                                        @endif
                                        </small>
                                        <span class="indicator-line rounded bg-primary"></span>
                                    </div>
                                </td>
                                <td>N/A</td>
                                <td>
                                    @if($quiz->result)
                                        <?php $badge = get_badge($quiz->result->quiz_result) ?>
                                        @if($badge)
                                        <div class="avatar avatar-sm mr-8pt">
                                            <img src="{{ asset('/images/' . $badge) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        </div>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    @if($quiz->result)
                                    <strong><?php echo get_result($quiz->result->quiz_result) ?></strong>
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