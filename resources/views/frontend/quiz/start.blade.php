@extends('layouts.app')

@section('content')

@push('after-styles')
<style>
[dir=ltr] .course-nav a.active {
    background-color: #5567ff;
    border: 2px solid #fff;
}

[dir=ltr] .course-nav a.active .material-icons {
    font-weight: bold;
    color: #fff;
}
</style>
@endpush

<?php
    $lesson = $quiz->lesson;
?>

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="navbar navbar-list navbar-light border-bottom navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container">
            <nav class="nav navbar-nav">
                <div class="nav-item navbar-list__item">
                    @if(auth()->user()->hasRole('Student'))
                    <a href="{{ route('admin.student.quizs') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Instructor'))
                    <a href="{{ route('admin.quizs.index') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                    @endif
                </div>
                <div class="nav-item navbar-list__item">
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="mr-16pt">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}">
                                @if(!empty($lesson->course->course_image))
                                <img src="{{ asset('storage/uploads/thumb/' . $lesson->course->course_image) }}"
                                    width="40" alt="Angular" class="rounded">
                                @else
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">
                                        {{ mb_substr($lesson->course->title, 0, 2) }}
                                    </span>
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="flex">
                            <a href="{{ route('courses.show', $lesson->course->slug) }}"
                                class="card-title text-body mb-0">
                                {{ $lesson->course->title }}
                            </a>
                            <p class="lh-1 d-flex align-items-center mb-0">
                                <span class="text-50 small font-weight-bold mr-8pt">
                                    {{ $lesson->course->teachers[0]->name }},
                                </span>
                                <span class="text-50 small">{{ $lesson->course->teachers[0]->headline }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </nav>

            <nav class="nav navbar-nav ml-sm-auto align-items-center align-items-sm-end d-none d-lg-flex">
                @if(auth()->user()->hasRole('Instructor'))
                <div class="">
                    <a href="{{ route('admin.quizs.edit', $quiz->id) }}" class="btn btn-accent">@lang('labels.frontend.buttons.edit')</a>
                    @if($quiz->published == 0)
                    <a href="{{ route('admin.quizs.publish', $quiz->id) }}" id="btn_publish" class="btn btn-primary">@lang('labels.frontend.buttons.publish')</a>
                    @else
                    <a href="{{ route('admin.quizs.publish', $quiz->id) }}" id="btn_publish" class="btn btn-info">@lang('labels.frontend.buttons.unpublish')</a>
                    @endif
                </div>
                @endif
            </nav>
        </div>
    </div>

    <div class="bg-info py-32pt">
        <div class="container page__container">
            <div class="row">
                <div class="col-8">
                    <div class="d-flex flex-wrap align-items-end mb-16pt">
                        <p class="h1 text-white-50 font-weight-light m-0">{{ $quiz->title }}</p>
                    </div>
                    <p class="hero__lead measure-hero-lead text-white-50">{{ $quiz->description }}</p>
                </div>
                <div class="col-4">
                    <div class="d-flex flex-wrap align-items-end mb-16pt float-right">
                        <?php
                            $hours = floor($quiz->duration / 60);
                            if($hours < 10) {
                                $hours = '0' . $hours;
                            }
                            $mins = $quiz->duration % 60;
                            if($mins < 10) {
                                $mins = '0' . $mins;
                            }
                            $seconds = '00';
                        ?>
                        <p id="time" class="h1 text-white-50 font-weight-light m-0 flex">{{ $hours }} : {{ $mins }} : {{ $seconds }}</p>
                    </div>
                    @if($duration > 0)
                    <p class="text-white-50 hero__lead measure-hero-lead float-right" style="clear: both;">
                        @lang('labels.frontend.general.since'): {{ timezone()->convertFromTimezone($quiz->start_date, $quiz->timezone, 'D M j H:i:s') }}
                    </p>
                    @else
                    <p class="text-white-50 hero__lead measure-hero-lead float-right mb-0" style="clear: both;">
                        @lang('labels.frontend.quiz.unavailable')
                    </p>
                    <p class="text-white-50 font-size-16pt float-right" style="clear: both;">
                        @lang('labels.frontend.general.start_time'): {{ timezone()->convertFromTimezone($quiz->start_date, $quiz->timezone, 'D M j H:i:s') }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="navbar navbar-expand-md navbar-list navbar-light bg-white border-bottom-2 "
        style="white-space: nowrap;">
        <div class="container page__container">
            <ul class="nav navbar-nav flex navbar-list__item">
                <li class="nav-item">
                    <i class="material-icons text-50 mr-8pt">tune</i>
                    @lang('string.frontend.quiz.see_quizzes_description'):
                </li>
            </ul>
            <div class="nav navbar-nav ml-sm-auto navbar-list__item">
                <div class="nav-item d-flex flex-column flex-sm-row ml-sm-16pt">
                    @if($quiz->type == 1)
                    <a href="javascript:void(0)" id="btn_start"
                        class="btn justify-content-center btn-accent w-100 w-sm-auto mb-16pt mb-sm-0 ml-sm-16pt">
                            @lang('labels.frontend.quiz.start')
                        <i class="material-icons icon--right">keyboard_arrow_right</i></a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.frontend.quiz.your_answer')</div>
            </div>
            <p class="text-50 mb-0">@lang('string.frontend.quiz.answer_notes')</p>
        </div>

        <div class="border-left-2 pl-32pt pb-64pt tute-questions">

            <form id="frm_quiz" method="POST" action="{{ route('student.quiz.save') }}" class="d-none">@csrf

                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                @foreach($quiz->question_groups as $group)

                <div class="group-wrap py-32pt mb-16pt border-bottom-1" group-id="{{ $group->id }}">

                    <div class="d-flex align-items-center page-num-container">
                        <div class="page-num">{{ $loop->iteration }}</div>
                        <div class="flex">
                            <div class="d-flex">
                                <h4 class="flex mb-0">{{ $group->title }}</h4>
                                <h5 class="badge badge-pill font-size-16pt badge-accent">{{ $group->score }}</h4>
                            </div>
                        </div>
                    </div>

                    @if($group->questions->count() > 0)

                    <ul class="list-group stack mb-40pt">

                        @foreach($group->questions as $question)

                        <li class="list-group-item d-flex quiz-item" data-id="{{ $question->id }}">

                            <div class="flex d-flex flex-column">
                                <div class="card-title mb-16pt">{{ $loop->iteration }}. {{ $question->question }}</div>
                                
                                <div class="text-right">
                                    <div class="chip chip-outline-secondary">
                                        @if($question->type == 0)
                                            @lang('labels.frontend.quiz.single_answer')
                                        @else
                                            @lang('labels.frontend.quiz.multi_answer')
                                        @endif
                                    </div>
                                    <div class="chip chip-outline-secondary">@lang('labels.frontend.general.score'): {{ $question->score }}</div>
                                </div>

                                <div class="options-wrap">
                                    <div class="form-group">
                                        <div class="custom-controls-stacked">
                                        @if($question->type == 0)
                                            @foreach($question->options as $option)
                                            <div class="custom-control custom-radio mb-8pt">
                                                <input id="option_s{{$option->id}}_q{{$question->id}}" name="option_single_q{{$question->id}}"
                                                    type="radio" class="custom-control-input" value="{{ $option->id }}">
                                                <label for="option_s{{$option->id}}_q{{$question->id}}" class="custom-control-label">{{ $option->option_text }}</label>
                                            </div>
                                            @endforeach
                                        @endif

                                        @if($question->type == 1)
                                            @foreach($question->options as $option)
                                            <div class="custom-control custom-checkbox mb-8pt">
                                                <input id="option_m{{$option->id}}_q{{$question->id}}" name="option_multi_q{{$question->id}}[]" 
                                                    type="checkbox" class="custom-control-input" value="{{ $option->id }}">
                                                <label for="option_m{{$option->id}}_q{{$question->id}}" class="custom-control-label">{{ $option->option_text }}</label>
                                            </div>
                                            @endforeach
                                        @endif

                                        @if($question->type == 2)
                                            @foreach($question->options as $option)
                                                @if($option->correct == 1)
                                                <input type="text" name="option_blank_q{{$question->id}}__option{{$option->id}}" class="form-control form-control-flush font-size-16pt text-70 border-bottom-1 inline pl-8pt" 
                                                style="width: fit-content; display: inline; border-color: #333;" placeholder="Add Correct Word"
                                                value="">
                                                @else
                                                <label class="text-70 font-size-16pt">{{ $option->option_text }}</label>
                                                @endif
                                            @endforeach
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
                @endforeach

                @if((auth()->user()->hasRole('Student') || auth()->user()->hasRole('Child')) && $duration > 0)
                <div class="form-group text-right">
                    <button type="button" id="btn_complete" class="btn btn-primary mb-16pt mb-sm-0 ml-sm-16pt">@lang('labels.frontend.buttons.complete') 
                        <i class="material-icons icon--right">keyboard_arrow_right</i></a>
                    </button>
                </div>
                @endif
            </form>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

var timer;
var time = '{{ $duration }}'; // Min
var take_type = '{{ $quiz->take_type }}';
var type = '{{ $quiz->type }}';
var status = '{{ $status }}'

$(function() {

    // Ajax Header for Ajax Call
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    });

    $('#btn_complete').on('click', function() {

        $('#frm_quiz').ajaxSubmit({
            success: function(res) {
                
                if(res.success) {
                    swal({
                        title: "@lang('labels.frontend.quiz.alert.completed')",
                        text: "@lang('labels.frontend.quiz.alert.description')",
                        type: 'warning',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: "@lang('labels.frontend.alert.button.confirm')",
                        dangerMode: false,
                    }, function (val) {
                        if(val) {
                            window.location.href = '/quiz-result/{{$quiz->lesson->slug}}/{{ $quiz->id }}';
                        }
                    });
                }
            },
        });
    });

    if(type == 2 && status == 'started') {
        getTimer(true);
    }

    $('#btn_start').on('click', function(e) {

        $('#frm_quiz').removeClass('d-none');

        if(take_type == '1') { // Allow pause in middle of take quiz
            if(timer == undefined) {
                getTimer(true);
                $(this).html('Stop Quiz <i class="material-icons icon--right">keyboard_arrow_right</i>');
            } else {
                clearInterval(timer);
                timer = undefined
                $(this).html('Start Quiz <i class="material-icons icon--right">keyboard_arrow_right</i>');
            }
        } else {
            if(timer == undefined) {
                getTimer(true);
                $(this).html('Finish Quiz <i class="material-icons icon--right">keyboard_arrow_right</i>');
            } else {
                clearInterval(timer);
                $('#btn_complete').trigger('click');
            }
        }
    });

    function getTimer(status = true) {

        var x = time;

        timer = setInterval(function() {
            x--;
            var hours = Math.floor( x / 3600 );
            var minutes = Math.floor( ( x - 3600 * hours ) / 60 );
            var seconds = x - hours * 3600 - minutes * 60;

            if (hours < 10) {hours = "0" + hours;}
            if (minutes < 10) {minutes = "0" + minutes;}
            if (seconds < 10) {seconds = "0" + seconds;}

            $('#time').html(hours + ':' + minutes + ':' + seconds);

            if(x == 0) {
                clearInterval(timer);

                swal({
                    title: "@lang('labels.frontend.quiz.alert.time_up')",
                    text: " @lang('labels.frontend.quiz.alert.time_up_text')",
                    type: 'warning',
                    showCancelButton: false,
                    showConfirmButton: true,
                    confirmButtonText: "@lang('labels.frontend.alert.button.confirm')",
                    dangerMode: false,
                }, function (val) {
                    if(val) {
                        $('#btn_complete').trigger('click');
                        // window.location.href = "{!! route('admin.student.quizs') !!}";
                    }
                });
            }

        }, 1000);
    }

    $('#btn_publish').on('click', function(e) {

        e.preventDefault();
        var button = $(this);

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", "@lang('labels.frontend.alert.publish_success')", "success");
                        button.text('Unpublish');
                        button.removeClass('btn-primary').addClass('btn-info');
                    } else {
                        swal("Success!", "@lang('labels.frontend.alert.unpublish_success')", "success");
                        button.text("@lang('labels.frontend.buttons.publish')");
                        button.removeClass('btn-info').addClass('btn-primary');
                    }

                }
            }
        });
    });
});

</script>

@endpush

@endsection