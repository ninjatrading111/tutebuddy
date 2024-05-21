@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="navbar navbar-list navbar-light border-bottom navbar-expand-sm" style="white-space: nowrap;">
        <div class="container page__container">
            <nav class="nav navbar-nav">
                <div class="nav-item navbar-list__item">
                    @if(auth()->user()->hasRole('Student'))
                    <a href="{{ route('admin.student.assignments') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                    @endif

                    @if(auth()->user()->hasRole('Instructor'))
                    <a href="{{ route('admin.assignments.index') }}" class="nav-link h-auto">
                        <i class="material-icons icon--left">keyboard_backspace</i> @lang('labels.frontend.general.back')
                    </a>
                    @endif
                </div>
                <div class="nav-item navbar-list__item">
                    <div class="d-flex align-items-center flex-nowrap">

                        <?php
                            if($assignment->lesson->steps->count() > 0) {
                                $route = route('lessons.show', [$assignment->course->slug, $assignment->lesson->slug, $assignment->lesson->steps[0]->step]);
                            } else {
                                $route = route('courses.show', [$assignment->course->slug]);
                            }
                        ?>

                        <div class="mr-16pt">
                            <a href="{{ $route }}">
                                @if(!empty($assignment->course->course_image))
                                <img src="{{ asset('storage/uploads/thumb/' . $assignment->course->course_image) }}"
                                    width="40" alt="Angular" class="rounded">
                                @else
                                <div class="avatar avatar-sm">
                                    <span class="avatar-title rounded bg-primary text-white">
                                        {{ mb_substr($assignment->course->title, 0, 2) }}
                                    </span>
                                </div>
                                @endif
                            </a>
                        </div>

                        <div class="flex">
                            <a href="{{ $route }}" class="card-title text-body mb-0">
                                {{ $assignment->course->title }} | {{ $assignment->lesson->title }}
                            </a>
                            <p class="lh-1 d-flex align-items-center mb-0">
                                <span class="text-50 small font-weight-bold mr-8pt">
                                    {{ $assignment->course->teachers[0]->name }},
                                </span>
                                <span class="text-50 small">{{ $assignment->course->teachers[0]->headline }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </nav>
            
            <nav class="nav navbar-nav ml-sm-auto align-items-center align-items-sm-end d-none d-lg-flex">
                @if(auth()->user()->hasRole('Instructor'))
                <div class="">
                    <a href="{{ route('admin.assignments.edit', $assignment->id) }}" class="btn btn-accent">
                        @lang('labels.frontend.buttons.edit') </a>
                    @if($assignment->published == 0)
                    <a href="{{ route('admin.assignment.publish', $assignment->id) }}" id="btn_publish" class="btn btn-primary">
                        @lang('labels.frontend.buttons.publish')</a>
                    @else
                    <a href="{{ route('admin.assignment.publish', $assignment->id) }}" id="btn_publish" class="btn btn-info">
                        @lang('labels.frontend.buttons.unpublish')</a>
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
                        <p class="h1 text-white-50 font-weight-light m-0">{{ $assignment->title }}</p>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex flex-wrap align-items-end mb-16pt float-right">
                        <p class="h1 text-white-50 font-weight-light m-0">{{ $assignment->due_date }}</p>
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
                        @lang('labels.frontend.assignment.include_doc')
                </li>
            </ul>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.frontend.assignment.title')</div>
        </div>

        <div class="font-size-16pt text-black-100">{!! $assignment->content !!}</div>

        @if(!empty($assignment->attachment))
        <div class="form-group mb-24pt card card-body">
            <label class="form-label">@lang('labels.frontend.assignment.attached_doc'):</label>
            <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                    @php $ext = pathinfo($assignment->attachment, PATHINFO_EXTENSION); @endphp
                    @if($ext == 'pdf')
                    <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                    @else
                    <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                    @endif
                </div>
                <div class="flex">
                    <a href="{{ asset('/storage/attachments/' . $assignment->attachment) }}">
                        <div class="form-label mb-4pt">{{ $assignment->attachment }}</div>
                        <p class="card-subtitle text-black-70">@lang('labels.frontend.assignment.attached_doc_click')</p>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    @if(!empty($assignment->result) && $assignment->result->status > 0)

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.frontend.assignment.teacher_summary')</div>
        </div>

        <div class="font-size-16pt text-black-100 mb-32pt">{!! $assignment->result->answer !!}</div>
        <div class="font-size-16pt text-black-100 mb-32pt"><strong>@lang('labels.frontend.assignment.mark')</strong>: {{ $assignment->result->mark }}</div>

        @if(!empty($assignment->result->answer_attach))
        <div class="form-group mb-24pt card card-body">
            <label class="form-label">@lang('labels.frontend.assignment.answer_doc')</label>
            <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                    @php $ext = pathinfo($assignment->result->answer_attach, PATHINFO_EXTENSION); @endphp
                    @if($ext == 'pdf')
                    <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                    @else
                    <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                    @endif
                </div>
                <div class="flex">
                    <a href="{{ asset('/storage/attachments/' . $assignment->result->answer_attach) }}">
                        <div class="form-label mb-4pt">{{ $assignment->result->answer_attach }}</div>
                        <p class="card-subtitle text-black-70">@lang('labels.frontend.assignment.attached_doc_click')</p>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    @endif

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.frontend.assignment.submit_answer')</div>
        </div>

        <div class="pb-32pt">
            <form id="frm_assignment" method="POST" action="{{ route('student.assignment.save') }}" enctype="multipart/form-data">@csrf
                <div class="form-group">
                    <div id="submit_content" style="min-height: 300px;">@if(!empty($assignment->result)){!! $assignment->result->content !!}@endif</div>
                </div>

                @if(!empty($assignment->result->attachment_url))
                <div class="form-group mb-24pt card card-body">
                    <label class="form-label">@lang('labels.frontend.assignment.attached_doc'):</label>
                    <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                        <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                            @php $ext = pathinfo($assignment->result->attachment_url, PATHINFO_EXTENSION); @endphp
                            @if($ext == 'pdf')
                            <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                            @else
                            <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                            @endif
                        </div>
                        <div class="flex">
                            <a href="{{ asset('/storage/attachments/' . $assignment->result->attachment_url) }}">
                                <div class="form-label mb-4pt">{{ $assignment->result->attachment_url }}</div>
                                <p class="card-subtitle text-black-70">@lang('labels.frontend.assignment.attached_doc_click')</p>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                <div class="form-group">
                    <label class="form-label">@lang('labels.frontend.assignment.upload_doc')</label>
                    <div class="custom-file">
                        <input type="file" id="file_doc" name="doc_file" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                        <label for="file_doc" class="custom-file-label">Choose file @lang('labels.frontend.general.choose_file')</label>
                    </div>
                </div>
                @if(auth()->user()->hasRole('Student') || auth()->user()->hasRole('Child'))
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">@lang('labels.frontend.buttons.submit')</button>
                </div>
                @endif
                <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
            </form>
        </div>
    </div>

</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    $(function() {

        var toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],  
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
        ];

        // Set Submitted Assignments if it is exist
        var s_quill = new Quill('#submit_content', {
            theme: 'snow',
            placeholder: "@lang('labels.frontend.assignment.answer_placeholder')",
            modules: {
                toolbar: toolbarOptions
            },
        });

        $('#frm_assignment').on('submit', function(e){
            e.preventDefault();
            $(this).ajaxSubmit({
                beforeSubmit: function(formData, formObject, formOptions) {

                    formData.push({
                        name: 'content',
                        type: 'text',
                        value: s_quill.root.innerHTML
                    });
                },
                success: function(res) {
                    if(res.success) {
                        swal('Success!', res.message, 'success');
                        swal({
                            title: "Success!",
                            text: res.message,
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: true,
                            confirmButtonText: "@lang('labels.backend.general.confirm')",
                            cancelButtonText: "@lang('labels.backend.general.cancel')",
                            dangerMode: false,

                        }, function(val) {
                            if (val) {
                                var url = "{{ route('admin.student.assignments') }}";
                                window.location.href = url;
                            }
                        });
                    }
                }
            })
        });

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
                            swal("Success!", 'Published successfully', "success");
                            button.text('Unpublish');
                            button.removeClass('btn-primary').addClass('btn-info');
                        } else {
                            swal("Success!", 'Unpublished successfully', "success");
                            button.text('Publish');
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