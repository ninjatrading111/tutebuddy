@extends('layouts.app')

@section('content')

@push('after-styles')

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Demo Request</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            Demo
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.dashboard') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">

        <div class="page-section border-bottom-2">

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-header">
                    <p class="page-separator__text bg-white mb-0"><strong>Demo Request</strong></p>
                </div>

                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-time"
                    data-lists-sort-desc="true">

                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>Course</th>
                                <th>Date Time</th>
                                <th>Timezone</th>
                                <th>Setup Status</th>
                                <th>Join Call</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($demos as $demo)
                                @if($demo->course)
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                            <div class="avatar avatar-sm mr-8pt">
                                                <span class="avatar-title rounded bg-primary text-white">
                                                    {{ mb_substr($demo->course->title, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <small class="js-lists-values-project">
                                                        <strong> {{ $demo->course->title }}</strong></small>
                                                    <small class="js-lists-values-location text-50">{{ $demo->course->slug }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <small class="js-lists-values-date"><strong>{{ $demo->date }}</strong></small>
                                            <small class="text-50">{{ $demo->start_time }}</small>
                                        </div>
                                    </td>
                                    <td><strong>{{ $demo->timezone }}</strong></td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($demo->status != 0)
                                            <small class="js-lists-values-date">
                                                <strong>{{ $demo->date }}</strong>
                                            </small>
                                            <small class="text-50">{{ \Carbon\Carbon::parse($demo->start_time)->format('g:i A') }}</small>
                                            @else
                                            <small class="js-lists-values-status text-50 mb-4pt">Not Ready</small>
                                            <span class="indicator-line rounded bg-primary"></span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($demo->status == 1)
                                            @php
                                                $demo_date = $demo->date . ' ' . $demo->start_time;
                                                $scheduled_time = \Carbon\Carbon::parse(timezone()->convertFromTimezone($demo_date, $demo->timezone, 'Y-m-d, H:i'))->addMinutes(30);
                                                $current_time = \Carbon\Carbon::parse(timezone()->convertToLocal(\Carbon\Carbon::now(), 'Y-m-d, H:i'));
                                            @endphp

                                            @if ($scheduled_time < $current_time)
                                            <button class="btn btn-primary btn-sm" disabled>Finished</button>
                                            @else
                                            <a href="{{ route('demo.live', $demo->id) }}" target="_blank" class="btn btn-primary btn-sm">Join</a>
                                            @endif
                                        @else
                                            @if($demo->status == 2)
                                            <button class="btn btn-primary btn-sm" disabled>Completed</button>
                                            @else
                                            <button class="btn btn-primary btn-sm" disabled>Join</button>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="card-footer">
                    @if($demos->hasPages())
                    {{ $demos->links('layouts.parts.page') }}
                    @else
                    <ul class="pagination justify-content-start pagination-xsm m-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true" class="material-icons">chevron_left</span>
                                <span>@lang('labels.backend.general.prev')</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Page 1">
                                <span>1</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Next">
                                <span>@lang('labels.backend.general.next')</span>
                                <span aria-hidden="true" class="material-icons">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>

    </div>

</div>
@push('after-scripts')

@endpush

@endsection