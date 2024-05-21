@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

@php $lesson = $test->lesson; @endphp

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
                    <a href="{{ route('admin.tests.edit', $test->id) }}" class="btn btn-accent">@lang('labels.frontend.buttons.edit')</a>
                    @if($test->published == 0)
                    <a href="{{ route('admin.test.publish', $test->id) }}" id="btn_publish" class="btn btn-primary">@lang('labels.frontend.buttons.publish')</a>
                    @else
                    <a href="{{ route('admin.test.publish', $test->id) }}" id="btn_publish" class="btn btn-info">@lang('labels.frontend.buttons.unpublish')</a>
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
                        <p class="h1 text-white-50 font-weight-light m-0">{{ $test->title }}</p>
                    </div>
                    <p class="hero__lead measure-hero-lead text-white-50">{{ $test->description }}</p>
                </div>
                <div class="col-4">
                    <div class="d-flex flex-wrap align-items-end mb-16pt float-right">
                        <?php
                            $hours = floor($test->duration / 60);
                            if($hours < 10) {
                                $hours = '0' . $hours;
                            }
                            $mins = $test->duration % 60;
                            if($mins < 10) {
                                $mins = '0' . $mins;
                            }
                            $seconds = '00';
                        ?>
                        <p id="time" class="h1 text-white-50 font-weight-light m-0">{{ $hours }} : {{ $mins }} : {{ $seconds }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="navbar navbar-expand-md navbar-list navbar-light bg-white border-bottom-2" style="white-space: nowrap;">
        <div class="container page__container">
            <ul class="nav navbar-nav flex navbar-list__item">
                <li class="nav-item">
                    <i class="material-icons text-50 mr-8pt">tune</i>
                    @lang('labels.frontend.test.click')
                </li>
            </ul>
            <div class="nav navbar-nav ml-sm-auto navbar-list__item">
                <div class="nav-item d-flex flex-column flex-sm-row ml-sm-16pt">
                    <a href="javascript:void(0)" id="btn_start"
                        class="btn justify-content-center btn-accent w-100 w-sm-auto mb-16pt mb-sm-0 ml-sm-16pt">
                            @lang('labels.frontend.test.button.start')
                        <i class="material-icons icon--right">keyboard_arrow_right</i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section tute-questions d-none">

        @foreach($test->questions as $question)
        <div class="border-left-2 pl-32pt pb-64pt">
            <div class="group-wrap py-32pt mb-16pt border-bottom-1">

                <div class="d-flex align-items-center page-num-container">
                    <div class="page-num">{{ $loop->iteration }}</div>
                    <div class="flex">
                        <div class="d-flex">
                            <h4 class="flex mb-0">Q : {{ $loop->iteration }}</h4>
                            <h5 class="badge badge-pill font-size-16pt badge-accent">{{ $question->score }}</h4>
                        </div>
                    </div>
                </div>

                <div class="font-size-16pt text-black-100">{!! $question->question !!}</div>

                @if(!empty($question->attachment))
                <div class="form-group mb-24pt">
                    <label class="form-label">@lang('labels.frontend.test.attached_doc'):</label>
                    <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                        <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                            @php $ext = pathinfo($question->attachment, PATHINFO_EXTENSION); @endphp
                            @if($ext == 'pdf')
                            <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                            @else
                            <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                            @endif
                        </div>
                        <div class="flex">
                            <a href="{{ asset('/storage/attachments/' . $question->attachment) }}">
                                <div class="form-label mb-4pt">{{ $question->attachment }}</div>
                                <p class="card-subtitle text-black-70">@lang('labels.frontend.test.attached_doc').</p>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
        @endforeach

    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">Submit</div>
        </div>

        <form id="frm_test" method="POST" action="{{ route('student.test.save') }}" enctype="multipart/form-data">@csrf

            <div class="form-group">
                <div id="submit_content" style="min-height: 300px;">@if(!empty($test->result)){!! $test->result->content !!}@endif</div>
            </div>

            @if(!empty($test->result->attachment))
            <div class="form-group mb-24pt">
                <label class="form-label">Attached Document:</label>
                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                    <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                        @php $ext = pathinfo($test->result->attachment, PATHINFO_EXTENSION); @endphp
                        @if($ext == 'pdf')
                        <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                        @else
                        <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                        @endif
                    </div>
                    <div class="flex">
                        <a href="{{ asset('/storage/attachments/' . $test->result->attachment) }}">
                            <div class="form-label mb-4pt">{{ $test->result->attachment }}</div>
                            <p class="card-subtitle text-black-70">Click to See Attached Document.</p>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <div class="form-group">
                <label class="form-label">@lang('labels.frontend.test.upload_doc')</label>
                <div class="custom-file">
                    <input type="file" id="file_doc" name="doc_file" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                    <label for="file_doc" class="custom-file-label">@lang('labels.frontend.general.choose_file')</label>
                </div>
            </div>
            @if(auth()->user()->hasRole('Student') || auth()->user()->hasRole('Child'))
            <div class="form-group">
                <button id="btn_submit" type="submit" class="btn btn-primary d-none">@lang('labels.frontend.buttons.submit')</button>
            </div>
            @endif
            <input type="hidden" name="test_id" value="{{ $test->id }}">

        </form>

    </div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    var timer;
    var time = '{{ $duration }}'; // Min
    var is_timer = false;
    var current_time;

    $(function() {

        var toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],  
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
        ];

        // Set Submitted tests if it is exist
        var s_quill = new Quill('#submit_content', {
            theme: 'snow',
            placeholder: "@lang('labels.frontend.test.answer_placeholder')",
            modules: {
                toolbar: toolbarOptions
            },
        });

        $('#btn_start').on('click', function(e) {
            if(!is_timer) {
                getTimer(is_timer);
                is_timer = true;
            }
            $('div.tute-questions').removeClass('d-none');
            $('#btn_submit').removeClass('d-none');
            $(this).html('Finish Quiz <i class="material-icons icon--right">keyboard_arrow_right</i>');
        });

        $('#frm_test').on('submit', function(e){

            e.preventDefault();

            $(this).ajaxSubmit({
                beforeSubmit: function(formData, formObject, formOptions) {

                    formData.push({
                        name: 'content',
                        type: 'text',
                        value: s_quill.root.innerHTML
                    });

                    formData.push({
                        name: 'due_time',
                        type: 'integer',
                        value: time - current_time
                    });
                },
                success: function(res) {
                    if(res.success) {
                        swal('Success!', res.message, 'success');
                        setTimeout(() => {
                            window.location.href = "{{ route('admin.student.tests') }}";
                        }, 1500);
                    }
                }
            })
        });

        function getTimer(status) {

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
                        title: "@lang('labels.frontend.test.alert.time_up')",
                        text: "@lang('labels.frontend.test.alert.time_up_text')",
                        type: 'warning',
                        showCancelButton: false,
                        showConfirmButton: true,
                        confirmButtonText: "@lang('labels.frontend.alert.button.confirm')",
                        dangerMode: false,
                    }, function (val) {
                        if(val) {
                            load_question(t_step);
                        }
                    });
                }

                current_time = x;

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
                    console.log(res);
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