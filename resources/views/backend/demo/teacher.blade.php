@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

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
                                <th>Student</th>
                                <th>Date Time</th>
                                <th>Timezone</th>
                                <th>Setup Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($demos as $demo)
                            <tr>
                                <td></td>
                                <td>
                                    <a href="{{ route('admin.courses.edit', $demo->course->id) }}">
                                        <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                            <div class="avatar avatar-sm mr-8pt">
                                                <span class="avatar-title rounded bg-primary text-white">
                                                    {{ mb_substr($demo->course->title, 0, 2) }}
                                                </span>
                                            </div>
                                            <div class="media-body">
                                                <div class="d-flex flex-column">
                                                    <small class="js-lists-values-project">
                                                        <strong class="course-title">{{ $demo->course->title }}</strong></small>
                                                    <small class="js-lists-values-location text-50">{{ $demo->course->slug }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            @if(!empty($demo->user->avatar))
                                            <img src="{{ asset('/storage/avatars/' . $demo->user->avatar) }}" alt="Avatar" class="avatar-img rounded-circle">
                                            @else
                                            <span class="avatar-title rounded-circle">{{ mb_substr($demo->user->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-name">{{ $demo->user->name }}</strong></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="js-lists-values-date demo-date"><strong>{{ $demo->date }}</strong></small>
                                        <small class="text-50 demo-time">{{ $demo->start_time }}</small>
                                    </div>
                                </td>
                                <td class="timezone"><strong>{{ $demo->timezone }}</strong></td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($demo->status != 0)
                                        <small class="js-lists-values-date">
                                            <strong>{{ $demo->date }}</strong>
                                        </small>
                                        <small class="text-50">{{ \Carbon\Carbon::parse($demo->start_time)->format('g:i A') }}</small>
                                        @else
                                        <small class="js-lists-values-status text-50 mb-4pt">Not Ready</small>
                                        <span class="indicator-line rounded bg-accent"></span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($demo->status == 1)
                                        <button class="btn btn-accent btn-sm btn_demo_setup" data-id="{{ $demo->id }}">
                                            Update
                                        </button>
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
                                        @if ($demo->status == 2)
                                        <button class="btn btn-primary btn-sm" disabled>Completed</button>
                                        @else
                                        <button class="btn btn-accent btn-sm btn_demo_setup" data-id="{{ $demo->id }}">
                                            Schedule Demo
                                        </button>
                                        <button class="btn btn-primary btn-sm" disabled>Join</button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
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

<!-- Schedule Modal for Demo -->
<div class="modal fade" id="scheduleDemoModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Demo Schedule</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="form-group mb-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label pr-3">Course Title: </label>
                                <input id="demo_course" name="demo_course" class="form-control font-size-16pt" value="Course Title">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label pr-3">Session Title: </label>
                                <input id="demo_title" name="demo_title" class="form-control font-size-16pt" value="Demo - Course name" max="50">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.timezone'): </label>
                                <select id="demo_timezone" name="demo_timezone" class="form-control" disabled></select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Requested Date:</label>
                                <input id="demo_date" type="hidden" class="form-control" value="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Requested Time:</label>
                                <select id="demo_time" class="form-control">
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
                    <button id="btn_demo_save" class="btn btn-outline-primary">@lang('labels.backend.buttons.save')</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
    $(function() {

        let demo_id = '';
        
        $('#demo_timezone').timezones();
        $('#demo_timezone').select2();
        $('#demo_time').select2();
        $('#demo_date').flatpickr({
            minDate: "today"
        });

        $('button.btn_demo_setup').on('click', (e) => {
            demo_id = $(e.target).attr('data-id');
            let timezone = $(e.target).closest('tr').find('td.timezone').text();
            let date = $(e.target).closest('tr').find('.demo-date').text();
            let time_str = $(e.target).closest('tr').find('.demo-time').text();
            let course_title = $(e.target).closest('tr').find('.course-title').text();

            $('#demo_title').val('Demo - ' + course_title);
            $('#demo_course').val(course_title);
            $('#demo_timezone').val(timezone).prop('selected', true);
            $('#demo_date').val(date);
            $('#demo_time').val(time_str).change();
            $('#scheduleDemoModal').modal('toggle');
        });

        $('#btn_demo_save').on('click', (e) => {

            btnLoading($('#btn_demo_save'), true);

            let data = {
                id: demo_id,
                title: $('#demo_title').val(),
                date: $('#demo_date').val(),
                start_time: $('#demo_time').val()
            }

            $.ajax({
                method: 'POST',
                url: '{{ route("admin.ajax.demo.setup") }}',
                data: data,
                success: (res) => {
                    btnLoading($('#btn_demo_save'), false);
                    if (res.success) {
                        $('#scheduleDemoModal').modal('toggle');
                        location.reload();
                    } else {
                        swal('Error!', res.message, 'error');
                    }
                },
                error: (err) => {
                    console.log(res);
                }
            });
        });
    });
</script>
    
@endpush

@endsection