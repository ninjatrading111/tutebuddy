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
                    <button class="btn btn-outline-primary btn-add-new">@lang('labels.backend.buttons.add_new')</button>
                    <button class="btn btn-outline-secondary" data-dismiss="modal" aria-label="Close">@lang('labels.backend.buttons.cancel')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Schedule Modal for Lesson select -->
<div class="modal fade" id="scheduleLessonModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('labels.backend.schedule.course'): <span class="course-title"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group mb-0">
                    <div class="row">
                        <div class="col-md-6 pr-1">
                            <div class="form-group">
                                <label class="form-label"> @lang('labels.backend.schedule.start_time'):</label>
                                <input id="lesson_start_time" type="time" class="form-control"
                                    placeholder="Pick start time" value="">
                            </div>
                        </div>
                        <div class="col-md-6 pl-1">
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.end_time'):</label>
                                <input id="lesson_end_time" type="time" class="form-control" placeholder="Pick end time"
                                    value="">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="" class="form-label">@lang('labels.backend.schedule.lessons')</label>
                    <select name="lesson" class="form-control form-label"></select>
                </div>

                <div class="form-group mb-0">
                    <label for="" class="form-label">@lang('labels.backend.schedule.lesson_detail'):</label>
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.schedule.lesson_detail_title'): </label>
                                <span id="d_lesson_title" class="form-label text-muted"></span>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">@lang('labels.backend.schedule.lesson_detail_description'):</label>
                                <span id="d_lesson_description" class="form-label text-muted"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button class="btn btn-outline-primary btn-add-new">@lang('labels.backend.schedule.assign_lesson')</button>
                    <button class="btn btn-outline-primary btn-lesson-update">@lang('labels.backend.schedule.update_lesson')</button>
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
    var schedule_startStr, schedule_endStr, schedule_startTime, schedule_endTime, schedule_id;

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
            schedule_startStr = info.event.startStr;

            var startTimeObj = new Date(info.event.start);
            var endTimeObj = new Date(info.event.end);

            schedule_startTime = ("0" + startTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + startTimeObj.getUTCMinutes()).slice(-2);
            schedule_endStr = info.event.endStr;
            schedule_endTime = ("0" + endTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + endTimeObj.getUTCMinutes()).slice(-2);

            // Get Lessons by selected Course
            $.ajax({
                method: 'GET',
                url: "{{ route('admin.schedule.getLessonsByCourse') }}",
                data: {id: schedule_id},
                success: function(res) {
                    if (res.success) {

                        lesson_added = (res.lesson_id != null) ? true : false;

                        // Add information for ScheduleLessonModal
                        $('#scheduleLessonModal').find('span.course-title').text(res.course_title);
                        $('select[name="lesson"]').html(res.options);

                        display_lesson_detail($('#scheduleLessonModal'));

                        // Open Lesson Modal
                        $('#scheduleLessonModal').modal('toggle');
                    }
                },
                error: function(err) {
                    var errMsg = getErrorMessage(err);
                    console.log(errMsg);
                }
            });
        },
        select: function(info) {

            var startTimeObj = new Date(info.start);
            var endTimeObj = new Date(info.end);

            schedule_startStr = info.startStr;
            schedule_startTime = ("0" + startTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + startTimeObj.getUTCMinutes()).slice(-2);
            schedule_endStr = info.endStr;
            schedule_endTime = ("0" + endTimeObj.getUTCHours()).slice(-2) + ':' + ("0" + endTimeObj.getUTCMinutes()).slice(-2);

            display_course_detail($('#scheduleCourseModal'));

            // Open Course Modal
            $('#scheduleCourseModal').modal('toggle');
        },
        eventDragStart: function(info) {
            schedule_id = info.event.id;
            var dragStartTime = info.event.startStr.split('T')[1];
            var dragEndTime = info.event.endStr.split('T')[1];
            // if(!isTimeValid(dragStartTime, dragEndTime)) {
            //     swal('Information', 'Time is duplicated, Please review times', 'info');
            //     return false;
            // }
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
        var course_title = $('select[name="course"] option:selected').text();

        var start_time = $('#course_start_time').val();
        var end_time = $('#course_end_time').val();

        if(start_time > end_time) {
            swal('Error!', 'Please check start and end time. (start time is greater than end time)', 'error');
            return false;
        }

        if(!isTimeValid(start_time, end_time)) {
            swal('Information', 'Time is duplicated, Please review times', 'info');
            return false;
        }

        var start = schedule_startStr.substring(0, 11) + $('#course_start_time').val() + ':00Z';
        var end = schedule_endStr.substring(0, 11) + $('#course_end_time').val() + ':00Z';

        // Add new course schedule
        var send_data = {
            id: course_id,
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

    // Change Course
    $('select[name="course"]').on('change', function() {
        display_course_detail();
    });

    /** Add lesson to course time slot */
    $('#scheduleLessonModal').on('click', '.btn-add-new', function() {

        var lesson_id = $('select[name="lesson"]').val();

        var start_time = $('#lesson_start_time').val();
        var end_time = $('#lesson_end_time').val();

        if(start_time > end_time) {
            swal('Error!', 'Please check start and end time. (start time is greater than end time)', 'error');
            return false;
        }

        if(!isTimeValid(start_time, end_time)) {
            swal('Information', 'Time is duplicated, Please review times', 'info');
            return false;
        }

        var start = schedule_startStr.substring(0, 11) + $('#lesson_start_time').val() + ':00Z';
        var end = schedule_endStr.substring(0, 11) + $('#lesson_end_time').val() + ':00Z';

        var send_data = {
            id: schedule_id,
            lesson_id: lesson_id,
            start: start,
            end: end
        };

        $.ajax({
            method: 'POST',
            url: "{{ route('admin.addLesson') }}",
            data: send_data,
            success: function(res) {

                if (res.success) {
                    calendar.refetchEvents();
                    var alert = getAlert('Success', 'Successfully Added Lesson', 'primary');
                    $('#scheduleLessonModal .modal-body').prepend(alert);
                    $('#scheduleLessonModal').modal('toggle');
                } else {
                    var alert = getAlert('Error', 'Something went wrong, Please try again', 'error');
                    $('#scheduleLessonModal .modal-body').prepend(alert);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                var alert = getAlert('Error', errMsg, 'error');
                $('#scheduleLessonModal .modal-body').prepend(alert);
            }
        });

    });

    /** Update Lesson */
    $('#scheduleLessonModal').on('click', '.btn-lesson-update', function() {

        var start_time = $('#lesson_start_time').val();
        var end_time = $('#lesson_start_time').val();

        if(start_time > end_time) {
            swal('Error!', 'Please check start and end time. (start time is greater than end time)', 'error');
            return false;
        }

        if(!isTimeValid(start_time, end_time)) {
            swal('Information', 'Time is duplicated, Please review times', 'info');
            return false;
        }

        var send_data = {
            id: schedule_id,
            start: $('#lesson_start_time').val(),
            end: $('#lesson_end_time').val()
        };

        $.ajax({
            method: 'POST',
            url: "{{ route('admin.updateSchedule') }}",
            data: send_data,
            success: function(res) {

                if (res.success) {
                    calendar.refetchEvents();
                    $('#scheduleLessonModal').modal('toggle');
                }
            }
        });
    });

    $('#scheduleLessonModal').on('click', '.btn-delete', function(e) {

        $.ajax({
            method: 'GET',
            url: "{{ route('admin.removeSchedule') }}",
            data: { id: schedule_id },
            success: function(res) {
                if(res.success) {
                    calendar.refetchEvents();
                    $('#scheduleLessonModal').modal('toggle');
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                var alert = getAlert('Error', errMsg, 'error');
                $('#scheduleLessonModal .modal-body').prepend(alert);
            }
        });
    });

    /** Schedule Lesson Modal */
    $('select[name="lesson"]').on('change', function() {
        display_lesson_detail();
    });

    $('span.event-remove').on('click', function(e) {
        e.preventDefault();
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

    function isTimeValid(startTime, endTime) {

        var result = false;

        // Check if startTime is between events
        var currentDate = new Date(schedule_startStr);

        var startDate = new Date(currentDate.getTime());
        startDate.setHours(startTime.split(":")[0]);
        // startDate.setMinutes(startTime.split(":")[1]);

        var endDate = new Date(currentDate.getTime());
        endDate.setHours(endTime.split(":")[0]);
        // endDate.setMinutes(endTime.split(":")[1]);

        var events = calendar.getEvents();
        if(events.length == 0) {
            result = true;
        }
        $.each(events, function(idx) {
            var event = $(this);

            var eventStartDate = new Date(event[0]._instance.range.start);
            var eventStartHours = eventStartDate.getUTCHours();
            // var eventStartMins = eventStartDate.getUTCMinutes();
            eventStartDate.setHours(eventStartHours);
            // eventStartDate.setMinutes(eventStartHours);

            var eventEndDate = new Date(event[0]._instance.range.end);
            var eventEndHours = event[0]._instance.range.end.getUTCHours();
            // var eventEndMins = event[0]._instance.range.end.getUTCMinutes();
            eventEndDate.setHours(eventEndHours);
            // eventEndDate.setMinutes(eventEndHours);

            if(endDate <= eventStartDate || startDate >= eventEndDate) {
                result = true;
            } else {
                result = false;
            }
        });

        return result;
    }

    // Display details for Lesson Modal
    function display_lesson_detail(ele) {
        
        $('#lesson_start_time').val(schedule_startTime);
        $('#lesson_end_time').val(schedule_endTime);

        if(lesson_added) {
            $('#scheduleLessonModal').find('.btn-add-new').hide();
            $('#scheduleLessonModal').find('.btn-lesson-update').show();
        } else {
            $('#scheduleLessonModal').find('.btn-add-new').show();
            $('#scheduleLessonModal').find('.btn-lesson-update').hide();
        }
        $('#scheduleLessonModal').find('div.alert').remove();
        
        var option = ele ? $('select[name="lesson"] option:selected', ele) : $(
            'select[name="lesson"] option:selected');
        var description = option.attr('data-desc');
        var title = option.text();

        $('#d_lesson_title').text(title);
        $('#d_lesson_description').text(description);
    }

    // Display details for Course modal
    function display_course_detail(ele) {

        $('#course_start_time').val(schedule_startTime);
        $('#course_end_time').val(schedule_endTime);

        var option = ele ? $('select[name="course"] option:selected', ele) : $(
            'select[name="course"] option:selected');
        var start = option.attr('data-start');
        var end = option.attr('data-end');
        var repeat_value = option.attr('data-repeat-value');
        var repeat_type = option.attr('data-repeat-type');

        $('#d_timezone').val(my_timezone).change();
        
        $('#d_start').text(start);
        $('#d_end').text(end);
        $('#d_repeat_value').text(repeat_value);
        $('#d_repeat_type').text(repeat_type);
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
});
</script>

@endpush