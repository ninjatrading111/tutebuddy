@extends('layouts.app')

@push('after-styles')

<!-- Full Calendar -->
<link type="text/css" href="{{ asset('assets/plugin/fullcalendar-scheduler/main.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

<style>
    span.event-remove {
        position: absolute;
        z-index: 99;
        right: -6px;
        top: -6px;
        background: rgba(39,44,51,.5);
        display: block;
        width: 15px;
        height: 15px;
        border-radius: 10px;
        color: #f8f9fa;
        text-align: center;
    }
    .fc-event-title-container .badge {
        position: absolute;
        right: 0px;
        top: 1px;
        font-size: 10px;
        font-weight: 400;
    }
    [dir=ltr] .custom-tooltip {
        position: absolute;
        top: 0px;
        left: 0px;
        will-change: transform;
        max-width: 300px;
        width: auto;
    }
    [dir=ltr] .fc .fc-view-harness {
        padding-bottom: inherit !important; 
    }
    [dir=ltr] .fc .fc-view-harness-active > .fc-view {
        position: relative;
    }
    [dir=ltr] .fc .fc-scroller-liquid-absolute {
        position: inherit;
    }
    [dir=ltr] .fc-direction-ltr .fc-timegrid-slot-label-frame {
        text-transform: uppercase;
    }
</style>

@endpush

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.schedule.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            @lang('labels.backend.schedule.title')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.courses.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="alert alert-soft-primary alert-dismissible fade show" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <div class="d-flex flex-wrap align-items-start">
                    <div class="mr-8pt">
                        <i class="material-icons">notifications</i>
                    </div>
                    <div class="flex pt-1" style="min-width: 180px">
                        <small class="text-black-100">
                            <strong>Note - </strong> Please select timeslot to add live session!
                        </small>
                    </div>
                </div>
            </div>
            @if($courses->count() > 0)
            <div class="card p-4">
                <div id='calendar'></div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Schedule Modal for Courses -->
<div class="modal fade" id="scheduleCourseModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('labels.backend.schedule.create_course_schedule')</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div id="alert_wrap"></div>

                <div class="form-group mb-0">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.start_time'):</label>
                                <input id="course_start_time" type="time" class="form-control"
                                    placeholder="Pick start time" value="">
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.end_time'):</label>
                                <input id="course_end_time" type="time" class="form-control" placeholder="Pick end time"
                                    value="">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">@lang('labels.backend.schedule.courses')</label>
                    <select name="course" class="form-control form-label">
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" data-start="{{ $course->start_date }}"
                            data-end="{{ $course->end_date }}"
                            data-repeat-value="{{ $course->repeat_value }}"
                            data-repeat-type="{{ $course->repeat_type }}"> {{ $course->title }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="" class="form-label">@lang('labels.backend.schedule.lessons')</label>
                    <select name="lesson" class="form-control form-label"></select>
                </div>

                <div class="form-group mb-0">
                    <label for="" class="form-label">@lang('labels.backend.schedule.detail'):</label>
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="form-group form-inline">
                                <label class="form-label">@lang('labels.backend.schedule.timezone'): </label>
                                <select id="d_timezone" name="timezone" class="form-control"></select>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.start_date'):</label>
                                <span id="d_start" class="form-label text-muted">2020-07-06</span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.end_date'):</label>
                                <span id="d_end" class="form-label text-muted">2020-10-06</span>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">@lang('labels.backend.schedule.repeat'):</label>
                                <span id="d_repeat_value" class="form-label text-muted">2</span>
                                <span id="d_repeat_type" class="form-label text-muted">@lang('labels.backend.schedule.week')</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button class="btn btn-outline-primary btn-add-new">@lang('labels.backend.buttons.save')</button>
                    <button class="btn btn-outline-danger btn-delete">@lang('labels.backend.schedule.remove_schedule')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal for Lesson select -->
<div class="modal fade" id="scheduleUpdateModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('labels.backend.schedule.update_condition'): <span class="course-title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-0">
                    <div class="p-3">
                        <div class="custom-control custom-radio mb-2">
                            <input id="update_this" name="update-cond" type="radio" class="custom-control-input" checked="">
                            <label for="update_this" class="form-label custom-control-label">@lang('labels.backend.schedule.update_this')</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="update_all" name="update-cond" type="radio" class="custom-control-input">
                            <label for="update_all" class="form-label custom-control-label">@lang('labels.backend.schedule.update_all')</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button class="btn btn-outline-primary btn-update">@lang('labels.backend.buttons.update')</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('after-scripts')

<!-- Full Calendar -->
<script src="{{ asset('assets/plugin/fullcalendar-scheduler/main.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
$(document).ready(function() {

    var lesson_added = false;
    var schedule_startStr, schedule_endStr, schedule_startTime, schedule_endTime, schedule_id, selected_course_id;

    var schedule_data = $('#schedule_data').val();
    var my_timezone = '{{ auth()->user()->timezone }}';

    $('#d_timezone').timezones();

    if('{{ $courses->count() }}' < 1) {
        swal({
            title: "You have no courses",
            text: "Please add a course to schedule first",
            type: 'info',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            dangerMode: false,
        }, function (val) {
            if(val) {
                location.href = '/dashboard/courses/create';
            } else {
                location.href = '/dashboard';
            }
        });
    }

    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
            right: 'prev,today,timeGridWeek,dayGridMonth,next'
        },
        initialView: 'timeGridWeek',
        firstDay: 1,
        timeZone: '{{ auth()->user()->timezone }}',
        allDaySlot: false,
        slotMinTime: '00:00:00',
        selectable: true,
        eventSources: [{
            url: '{{ route("admin.getScheduleData") }}',
            editable: true,
            success: function(content, xhr) {
                return content.data;
            }
        }],
        eventContent: function(info) {
            var html = `<div class="fc-event-time">` + info.timeText + `</div>
                        <div class="fc-event-time mb-8pt"> `+ info.event._def.extendedProps.timezone +`</div>`;
            html += `<div class="fc-event-title-container">
                        <div class="fc-event-title">` + info.event.title + `</div>
                    </div>`;
            if(info.event._def.extendedProps.lesson !== undefined) {
                html += `<div class="fc-event-title-container">
                            <div class="fc-event-desc fc-sticky">@lang('labels.backend.general.lesson'): ` + info.event._def.extendedProps.lesson + `</div>
                            <span class="badge badge-notifications badge-accent">Ready</span>
                        </div>`;               
            }
            return { html: html};
        },
        eventResize: function(info) {
            schedule_id = info.event.id;
            schedule_startStr = info.event.startStr;
            schedule_endStr = info.event.endStr;

            $('#scheduleUpdateModal').modal('toggle');
        },
        eventClick: function(info) {
            schedule_id = info.event.id;
            selected_course_id = info.event._def.extendedProps.course_id;
            schedule_startStr = info.event.startStr;

            var startTimeObj = new Date(info.event.start);
            var endTimeObj = new Date(info.event.end);

            schedule_startTime = ("0" + startTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + startTimeObj.getUTCMinutes()).slice(-2);
            schedule_endStr = info.event.endStr;
            schedule_endTime = ("0" + endTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + endTimeObj.getUTCMinutes()).slice(-2);

            display_course_detail('update');
        },
        select: function(info) {

            var startTimeObj = new Date(info.start);
            var endTimeObj = new Date(info.end);

            schedule_startStr = info.startStr;
            schedule_startTime = ("0" + startTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + startTimeObj.getUTCMinutes()).slice(-2);
            schedule_endStr = info.endStr;
            schedule_endTime = ("0" + endTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + endTimeObj.getUTCMinutes()).slice(-2);

            display_course_detail('new');
        },
        eventDragStart: function(info) {
            schedule_id = info.event.id;
            var dragStartTime = info.event.startStr.split('T')[1];
            var dragEndTime = info.event.endStr.split('T')[1];
        },
        eventDrop: function(info) {
            schedule_id = info.event.id;
            schedule_startStr = info.event.startStr;
            schedule_endStr = info.event.endStr;
            $('#scheduleUpdateModal').modal('toggle');
        },
        eventMouseEnter: function(info) {
            var time = info.timeText;
            var timezone = info.event._def.extendedProps.timezone;
            var course_title = info.event._def.extendedProps.full_course_title;
            var lesson_title = info.event._def.extendedProps.full_lesson_title;

            var template = `<div class="custom-tooltip">
                                <div class="card card-body">
                                    <div class="card-title">Course: `+ course_title +`</div>
                                    <div class="card-title">Lesson: `+ lesson_title +`</div>
                                </div>
                            </div>`;

            $('body').find('div.custom-tooltip').remove();
            $('body').append(template);

            var width = $('body').find('div.custom-tooltip').width();
            var height = $('body').find('div.custom-tooltip').height();

            var x = $(info.el).offset().left - (width - $(info.el).width()) / 2;
            var y = $(info.el).offset().top - height + 10;

            $('body').find('div.custom-tooltip').css('transform', 'translate3d('+ x +'px, '+ y +'px, 0px)');
        },
        eventMouseLeave: function(info) {
            $('body').find('div.custom-tooltip').remove();
        }
    });
    calendar.render();

    // Ajax header for Ajax POST
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    // Add new schedule Event
    $('#scheduleCourseModal').on('click', '.btn-add-new', function(e) {

        var course_id = $('select[name="course"]').val();
        var lesson_id = $('select[name="lesson"]').val();
        var course_title = $('select[name="course"] option:selected').text();

        var start_time = $('#course_start_time').val();
        var end_time = $('#course_end_time').val();

        if(start_time > end_time) {
            show_alert('Start time is greater than end time!');
            return false;
        }

        if(!isTimeValid()) {
            show_alert('Time is duplicated, Please review times!');
            return false;
        }

        var start = schedule_startStr.substring(0, 11) + $('#course_start_time').val() + ':00Z';
        var end = schedule_endStr.substring(0, 11) + $('#course_end_time').val() + ':00Z';

        // Add new course schedule
        var send_data = {
            course_id: course_id,
            lesson_id: lesson_id,
            start: start,
            end: end,
            timezone: $('#d_timezone').val()
        };

        $.ajax({
            method: 'POST',
            url: "{{ route('admin.storeSchedule') }}",
            data: send_data,
            success: function(res) {

                if (res.success) {
                    calendar.refetchEvents();
                    $('#scheduleCourseModal').modal('toggle');
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                var alert = getAlert('Error', errMsg, 'error');
                $('#scheduleCourseModal .modal-body').prepend(alert);
            }
        });
    });

    // Remove schedule Event
    $('#scheduleCourseModal').on('click', '.btn-delete', function(e) {

        $.ajax({
            method: 'GET',
            url: "{{ route('admin.removeSchedule') }}",
            data: { id: schedule_id },
            success: function(res) {
                if(res.success) {
                    calendar.refetchEvents();
                    $('#scheduleCourseModal').modal('toggle');
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                var alert = getAlert('Error', errMsg, 'error');
                $('#scheduleCourseModal .modal-body').prepend(alert);
            }
        });
    });

    // Change Course
    $('select[name="course"]').on('change', function() {
        var course_id = $('select[name="course"]').val();
        load_lessons(course_id);
        display_course_detail('display');
    });

    $('#scheduleUpdateModal').on('click', '.btn-update', function(e){
        var send_data = {
            id: schedule_id,
            start: schedule_startStr,
            end: schedule_endStr
        };

        $.ajax({
            method: 'POST',
            url: "{{ route('admin.updateSchedule') }}",
            data: send_data,
            success: function(res) {

                if (res.success) {
                    calendar.refetchEvents();
                    $('#scheduleUpdateModal').modal('toggle');
                }
            }
        });
    });

    $('#course_start_time').on('change', function() {
        schedule_startStr = schedule_startStr.substring(0, 11) + $(this).val() + ':00';
    });

    $('#course_end_time').on('change', function() {
        schedule_endStr = schedule_endStr.substring(0, 11) + $(this).val() + ':00';
    });

    // Load Lessons by selected Course ID
    function load_lessons(course_id) {
        
        // Get lessons by Course ID
        $.ajax({
            method: 'GET',
            url: "{{ route('admin.lessons.getLessonsByCourse') }}",
            data: {course_id: course_id},
            success: function(res) {
                if (res.success) {
                    if (res.options != "") {
                        $('select[name="lesson"]').html(res.options);
                    } else {
                        $('select[name="course"]').val($("select[name='course'] option:first").val());
                        swal("Error!", "You cannot add any Live Lessons to this course because your course has no lessons. Add a lesson first.", "error");
                    }
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                console.log(errMsg);
            }
        });
    }

    // Display details for Course modal
    function display_course_detail(type) {

        $('#alert_wrap').empty();

        $('#course_start_time').val(schedule_startTime);
        $('#course_end_time').val(schedule_endTime);

        if(type == 'update') {
            $('#scheduleCourseModal').find('select[name="course"]').val(selected_course_id).change();
        }

        if(type == 'new') {
            var first_course_id = $('select[name="course"] option:first').val();
            $('select[name="course"]').val(first_course_id).change();
        }

        var option = $('#scheduleCourseModal') ? $('select[name="course"] option:selected', $('#scheduleCourseModal')) : $('select[name="course"] option:selected');
        var start = option.attr('data-start');
        var end = option.attr('data-end');
        var repeat_value = option.attr('data-repeat-value');
        var repeat_type = option.attr('data-repeat-type');

        $('#d_timezone').val(my_timezone).change();
        
        $('#d_start').text(start);
        $('#d_end').text(end);
        $('#d_repeat_value').text(repeat_value);
        $('#d_repeat_type').text(repeat_type);

        if(type != 'display') {
            $('#scheduleCourseModal').modal('toggle'); 
        }    
    }

    function isTimeValid() {

        var count = 0;

        var startTime = new Date(schedule_startStr);
        var endTime = new Date(schedule_endStr);
        var startTime_utc = new Date(startTime.getTime() - startTime.getTimezoneOffset() * 60000).toUTCString();
        var endTime_utc = new Date(endTime.getTime() - endTime.getTimezoneOffset() * 60000).toUTCString();

        var events = calendar.getEvents();
        if(events.length == 0) {
            return true;
        }

        $.each(events, function(idx) {
            var event = $(this);
            var eventStartTime = event[0]._instance.range.start.toUTCString();
            var eventEndTime = event[0]._instance.range.end.toUTCString();

            if(startTime_utc >= eventEndTime || endTime_utc <= eventStartTime) {
                // results.push(true);
            } else {
                count++;
            }
        });

        return (count < 1);
    }

    // Convert time
    function convert_time(time, timezone) {
        var hours = parseInt(time.mb_substr(0, time.indexOf(':')));
        var mins = parseInt(time.mb_substr(time.indexOf(':') + 1));

        var d_obj = new Date();
        d_obj.setHours(hours);
        d_obj.setMinutes(mins);

        var timezone_prev_time = d_obj.toLocaleString("en-US", {timeZone: my_timezone});
        var timezone_prev_time_obj = new Date(timezone_prev_time);

        var utc_prev_hours = timezone_prev_time_obj.getUTCHours();
        var utc_prev_minutes = timezone_prev_time_obj.getUTCMinutes();

        var timezone_time = d_obj.toLocaleString("en-US", {timeZone: timezone});
        var timezone_time_obj = new Date(timezone_time);

        var utc_hours = timezone_time_obj.getUTCHours();
        var utc_minutes = timezone_time_obj.getUTCMinutes();

        var diff_hours = utc_hours - utc_prev_hours;
        var diff_minutes = utc_minutes - utc_prev_minutes;

        var new_hours = hours + diff_hours;
        var new_minutes = mins + diff_minutes;

        if(new_hours < 10) {
            new_hours = '0' + new_hours;
        }

        if(new_minutes < 10) {
            new_minutes = '0' + new_minutes;
        }

        return new_hours + ':' + new_minutes;
    }

    // Show Alert
    function show_alert(str) {
        var ele = `<div id="mdl_alert" class="alert alert-soft-accent alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <div class="d-flex flex-wrap align-items-start">
                <div class="mr-8pt">
                    <i class="material-icons">access_time</i>
                </div>
                <div class="flex" style="min-width: 180px">
                    <small class="text-black-100">
                        <strong>Alert - </strong> ${str}
                    </small>
                </div>
            </div>
        </div>`;

        $('#alert_wrap').empty();
        $('#alert_wrap').append(ele);
    }
});
</script>

@endpush