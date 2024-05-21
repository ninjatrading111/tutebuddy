@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="py-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.assignments.result.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.assignments.result.title')
                        </li>

                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="row">
            <div class="col-lg-8">
                <div class="page-separator">
                    <div class="page-separator__text">{{ $result->assignment->title }}</div>
                </div>

                <div class="pb-32pt">
                    <div id="assignment_content" class="font-size-16pt text-black-100">{!! $result->assignment->content !!}</div>
                </div>

                <div class="page-separator">
                    <div class="page-separator__text">@lang('labels.backend.assignments.result.submitted_content')</div>
                </div>

                <div class="pb-32pt">
                    <div id="submited_content" class="font-size-16pt text-black-100">{!! $result->content !!}</div>

                    @if(!empty($result->attachment_url))
                    <div class="form-group mb-24pt card card-body">
                        <label class="form-label">@lang('labels.backend.assignments.result.attached_document'):</label>
                        <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                            <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                @php $ext = pathinfo($result->attachment_url, PATHINFO_EXTENSION); @endphp
                                @if($ext == 'pdf')
                                <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                @else
                                <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                                @endif
                            </div>
                            <div class="flex">
                                <a href="{{ asset('/storage/attachments/' . $result->attachment_url) }}">
                                    <div class="form-label mb-4pt">{{ $result->attachment_url }}</div>
                                    <p class="card-subtitle text-black-70">
                                    @lang('labels.backend.assignments.result.see_attach_note')</p>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-4">
                <div class="page-separator">
                    <div class="page-separator__text">@lang('labels.backend.general.information')</div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form id="frm_a_result" method="POST" action="{{ route('admin.assignments.result_answer') }}" enctype="multipart/form-data">@csrf
                            
                            <div class="form-group">
                                <label for="" class="form-label">Total Mark: {{ $result->assignment->total_mark }}</label>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">@lang('labels.backend.assignments.result.assignment_mark')</label>
                                <input type="number" name="mark" class="form-control" max="{{ $result->assignment->total_mark }}" 
                                    value="{{ $result->mark }}" tute-no-empty>
                            </div>

                            <div class="form-group">
                                <label for="" class="form-label">@lang('labels.backend.assignments.result.summary')</label>
                                <textarea name="answer" rows="10" class="form-control" tute-no-empty>{{ $result->answer }}</textarea>
                            </div>

                            @if(!empty($result->answer_attach))
                            <div class="form-group mb-24pt card card-body">
                                <label class="form-label">@lang('labels.backend.assignments.result.attached_document'):</label>
                                <div class="d-flex col-md align-items-center border-bottom border-md-0 mb-16pt mb-md-0 pb-16pt pb-md-0">
                                    <div class="w-64 h-64 d-inline-flex align-items-center justify-content-center mr-16pt">
                                        @php $ext = pathinfo($result->answer_attach, PATHINFO_EXTENSION); @endphp
                                        @if($ext == 'pdf')
                                        <img class="img-fluid rounded" src="{{ asset('/images/pdf.png') }}" alt="image">
                                        @else
                                        <img class="img-fluid rounded" src="{{ asset('/images/docx.png') }}" alt="image">
                                        @endif
                                    </div>
                                    <div class="flex">
                                        <a href="{{ asset('/storage/attachments/' . $result->answer_attach) }}">
                                            <div class="form-label mb-4pt">{{ $result->answer_attach }}</div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="form-group">
                                <label for="" class="form-label">@lang('labels.backend.assignments.result.attach')</label>
                                <div class="custom-file">
                                    <input type="file" id="file_doc" name="answer_attach" class="custom-file-input" accept=".doc, .docx, .pdf, .txt" tute-file>
                                    <label for="file_doc" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                                </div>
                            </div>

                            <input type="hidden" name="result_id" value="{{ $result->id }}">
                            <input type="hidden" id="result_status" name="status" value="1">

                            @if($result->status != 1)
                            <div class="form-group">
                                <button type="button" id="btn_resubmit" class="btn btn-primary">@lang('labels.backend.buttons.resubmit')</button>
                                <button type="button" id="btn_complete" class="btn btn-accent">@lang('labels.backend.buttons.complete')</button>
                            </div>
                            @endif
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>
<div class="d-none">
    <textarea id="a_text">{{ $result->assignment->content }}</textarea>
    <textarea id="s_text">{{ $result->content }}</textarea>
    <div id="a_editor"></div>
    <div id="s_editor"></div>
</div>


@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

    $(function() {

        $('#frm_a_result').on('submit', function(e) {
            e.preventDefault();

            if(!checkValidForm($(this))) {
                return false;
            }

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        swal({
                            title: "@lang('labels.backend.swal.success.title')",
                            text: "@lang('labels.backend.swal.successfully_submitted')",
                            type: 'success',
                            showConfirmButton: true,
                            confirmButtonText: "@lang('labels.backend.general.confirm')",
                            dangerMode: false,

                        }, function(val) {
                            if (val) {
                                var url = "{{ route('admin.admin.submitedAssignments') }}";
                                window.location.href = url;
                            }
                        });
                    }
                }
            });
        });

        $('#btn_resubmit').on('click', function(e) {
            e.preventDefault();
            $('#result_status').val(2);

            $('#frm_a_result').submit();
        });

        $('#btn_complete').on('click', function(e) {
            e.preventDefault();
            $('#result_status').val(1);
            
            $('#frm_a_result').submit();
        });

        $('input[name="mark"]').on('keyup', function(e) {
            if($(this).val() > parseInt($(this).attr('max'))) {

                $(this).val('100');

                if($(this).siblings('.invalid-feedback').length < 1) {
                    var err_msg = $('<div class="invalid-feedback" style="display: block;">Marks is greater than total marks.</div>');
                    err_msg.insertAfter($(this));
                }
            } else {
                $(this).siblings('.invalid-feedback').remove();
            }
        });

    });
</script>

@endpush

@endsection