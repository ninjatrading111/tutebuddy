@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="navbar navbar-list navbar-light border-bottom navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container">
            <nav class="nav navbar-nav">
                <div class="nav-item navbar-list__item">
                    @if(auth()->user()->hasRole('Student'))
                    <a href="{{ route('admin.student.tests') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Instructor'))
                    <a href="{{ route('admin.tests.index') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                    @endif
                </div>
                <div class="nav-item navbar-list__item">
                    <div class="d-flex align-items-center flex-nowrap">
                        <div class="mr-16pt">
                            <a href="{{ route('courses.show', $test->lesson->course->slug) }}">
                                @if(!empty($test->course->course_image))
                                <img src="{{ asset('storage/uploads/thumb/' . $test->lesson->course->course_image) }}"
                                    width="40" alt="Angular" class="rounded">
                                @else
                                <div class="avatar avatar-sm mr-8pt">
                                    <span class="avatar-title rounded bg-primary text-white">
                                        {{ mb_substr($test->course->title, 0, 2) }}
                                    </span>
                                </div>
                                @endif
                            </a>
                        </div>
                        <div class="flex">
                            <a href="{{ route('courses.show', $test->lesson->course->slug) }}"
                                class="card-title text-body mb-0">
                                {{ $test->title }}
                            </a>
                            <p class="lh-1 d-flex align-items-center mb-0">
                                <span class="text-50 small font-weight-bold mr-8pt">
                                    {{ $test->course->teachers[0]->name }},
                                </span>
                                <span class="text-50 small">{{ $test->course->teachers[0]->headline }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="mdk-box bg-info mdk-box--bg-gradient-primary2 js-mdk-box mb-0" data-effects="blend-background">
        <div class="mdk-box__content">
            <div class="py-64pt text-center text-sm-left">
                <div class="container d-flex flex-column justify-content-center align-items-center">
                    <h3 class="text-white-70">{{ $test->title }}</h3>
                    <p class="lead text-white-50 measure-lead-max mb-0">@lang('labels.frontend.test.submit_on')
                        {{ Carbon\Carbon::parse($test->result->updated_at)->diffForHumans() }}</p>
                    <h1 class="text-white mb-24pt">@lang('labels.frontend.test.your_score') : {{ $test->result->mark }}</h1>
                </div>
            </div>
        </div>
    </div>

    <?php
        $hours = floor($test->result->duration / 60);
        $mins = $test->result->duration % 60;

        $duration = $hours . ' Hours ' . $mins . ' Mins';
    ?>

    <div class="navbar navbar-expand-sm navbar-light navbar-submenu navbar-list p-0 m-0 align-items-center">
        <div class="container page__container">
            <ul class="nav navbar-nav flex align-items-sm-center">
                <li class="nav-item navbar-list__item">{{ $test->result->mark }} @lang('labels.frontend.general.score')</li>
                <li class="nav-item navbar-list__item">
                    <i class="material-icons text-muted icon--left">schedule</i>
                    {{ $duration }}
                </li>
                <li class="nav-item navbar-list__item">
                    <i class="material-icons text-muted icon--left">assessment</i>
                    {{ $test->course->category->name }}
                </li>
            </ul>
        </div>
    </div>

    <div class="container page__container">
        <div class="border-left-2 page-section pl-32pt">

            @foreach($test->questions as $question)

            <div class="d-flex align-items-center page-num-container mb-16pt">
                <div class="page-num">{{ $loop->iteration }}</div>
                <h4>Question {{ $loop->iteration }} of {{ $test->questions->count() }}</h4>
            </div>

            <p class="text-70 measure-lead mb-32pt mb-lg-48pt font-size-16pt" id="question_wrap__{{ $question->id }}">{!! $question->question !!}</p>

            <ul class="list-quiz">
                @foreach($question->options as $option)
                <li class="list-quiz-item">

                    @if($option->correct == 1)
                    <span class="list-quiz-badge bg-primary text-white"><i class="material-icons">check</i></span>
                    @else
                        @if($option->correct == 0 && !empty($test->result->answers->where('option_id', $option->id)->all()))
                        <span class="list-quiz-badge bg-accent text-white"><i class="material-icons">clear</i></span>
                        @else
                        <span class="list-quiz-badge">{{ $loop->iteration }}</span>
                        @endif
                    @endif

                    <span class="list-quiz-text">{{ $option->option_text }}</span>
                </li>
                @endforeach
            </ul>

            @endforeach

        </div>
    </div>

    @if($test->result->status > 0)

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.frontend.test.teacher_summary')</div>
        </div>

        <div class="font-size-16pt text-black-100 mb-32pt">{!! $test->result->answer !!}</div>
        <div class="font-size-16pt text-black-100 mb-32pt"><strong>@lang('labels.frontend.general.mark')</strong>: {{ $test->result->mark }}</div>

        @if(!empty($test->result->answer_attach))
        <div class="form-group mb-24pt card card-body">
            <label class="form-label">@lang('labels.frontend.test.answer_attach'):</label>
            <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                    @php $ext = pathinfo($test->result->answer_attach, PATHINFO_EXTENSION); @endphp
                    @if($ext == 'pdf')
                    <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                    @else
                    <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                    @endif
                </div>
                <div class="flex">
                    <a href="{{ asset('/storage/attachments/' . $test->result->answer_attach) }}">
                        <div class="form-label mb-4pt">{{ $test->result->answer_attach }}</div>
                        <p class="card-subtitle text-black-70">@lang('labels.frontend.test.attach_click').</p>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    @endif

</div>

@endsection