@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<style>
.modal .modal-body {
    max-height: 80vh;
    overflow: auto;
}

.accordion .btn-actions {
    margin: 0 10px;
}

.work-area.hidden {
    display: none;
}
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Question</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.questions.index') }}">Questions</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Question
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.questions.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            <div class="row align-items-start">
                <div class="col-md-8">

                    <div class="page-separator">
                        <div class="page-separator__text">Edit a Question</div>
                    </div>

                    {!! Form::open(['method' => 'PATCH', 'route' => ['admin.questions.update', $question->id], 'id' =>'frm_question']) !!}

                    <div class="card card-body">
                        <div class="form-group">
                            <label class="form-label">Question</label>
                            <div style="height: 150px;" class="mb-0" id="quiz_editor"></div>
                            <small class="form-text text-muted">Shortly describe the question.</small>
                            <textarea style="display: none;" id="content_quiz">{{ $question->question }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Question Type</label>
                            <select name="type" class="form-control custom-select">
                                <option value="0" @if($question->type == 0) selected @endif>Multiple Answer</option>
                                <option value="1" @if($question->type == 1) selected @endif>Single Answer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Completion Points</label>
                            <input name="score" type="text" class="form-control" value="{{ $question->score }}">
                        </div>
                    </div>

                    {!! Form::close() !!}

                    <div class="page-separator">
                        <div class="page-separator__text">Options</div>
                    </div>

                    <div id="options">

                    @if($question->options->count() > 0)

                    <ul class="list-group stack mb-40pt">

                        @foreach($question->options as $option)

                        <li class="list-group-item d-flex" data-option-id="{{ $option->id }}">
                            <div class="flex d-flex flex-column">
                                <div class="card-title mb-16pt">Option {{ $loop->iteration }}</div>
                                <div class="page-separator"></div>
                                <div class="form-group mb-0">
                                    <label class="form-label">Option Text*:</label>
                                    <div class="card-subtitle text-70 paragraph-max mb-16pt">
                                        <input type="text" class="form-control option-text" value="{{ $option->option_text }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Option Explain:</label>
                                    <textarea class="form-control option-textarea option-explanation" rows="3"
                                        width="100">{{ $option->explanation }}</textarea>
                                </div>

                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input class="custom-control-input option-correct" type="checkbox" value="" id="chk_{{ $option->id }}"
                                            @if($option->correct == 1) checked="" @endif>
                                        <label class="custom-control-label" for="chk_{{ $option->id }}">
                                            Correct Answer
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                        </li>

                        @endforeach

                    </ul>

                    @endif

                    </div>

                    <button id="btn_new_option" class="btn btn-block btn-outline-secondary">Add Option</button>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <a href="javascript:void(0);" class="btn btn-accent" id="btn_question_save">Save changes</a>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="#"><strong>Save Draft</strong></a>
                                <i class="material-icons text-muted">check</i>
                            </div>
                            <div class="list-group-item">
                                <a href="#" class="text-danger"><strong>Delete Quiz</strong></a>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Setting</div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label">course</label>
                                <select name="course" id="course" data-toggle="select" data-tags="true"
                                    data-multiple="true" multiple="multiple" data-minimum-results-for-search="-1"
                                    class="form-control" data-placeholder="Select course ...">
                                    @foreach($courses as $course)
                                    <option data-avatar-src="@if(!empty($course->course_image)) 
                                        {{asset('/storage/uploads' . $course->course_image ) }}
                                        @else 
                                            {{asset('/assets/img/no-image.jpg')}}
                                        @endif" @if($loop->iteration == 1) selected="" @endif value="{{$course->id}}">
                                        {{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label">Tests</label>
                                <select name="tests" id="tests" data-toggle="select" data-tags="true"
                                    data-multiple="true" multiple="multiple" data-minimum-results-for-search="-1"
                                    class="form-control" data-placeholder="Select course ...">
                                    @foreach($tests as $test)
                                    <option @if($question->test_id == $test->id) selected="" @endif
                                        value="{{$test->id}}">
                                        {{ $course->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add new option -->
<div id="mdl_option" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">

        {!! Form::open(['method' => 'POST', 'route' => ['admin.questions_options.store'], 'id' =>'frm_option']) !!}
            <div class="modal-header">
                <h5 class="modal-title">New Option</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-0">
                    <label class="form-label">Option Text*:</label>
                    <div class="card-subtitle text-70 paragraph-max mb-16pt">
                        <input type="text" name="option_text" class="form-control" value="" placeholder="Option title...">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Option Explain:</label>
                    <textarea name="explanation" class="form-control option-textarea" rows="3" placeholder="Explanation option"
                        width="100"></textarea>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" value="" id="chk_new">
                        <label class="custom-control-label" for="chk_new">
                            Correct Answer
                        </label>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
            </div>

        {!! Form::close() !!}
        </div>
    </div>
</div>


@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<!-- jQuery Form -->
<script src="{{ asset('assets/js/jquery.form.min.js') }}"></script>

<script>

var question_id = '{{ $question->id }}';
var quiz_quill;

$(document).ready(function() {

    quiz_quill = new Quill('#quiz_editor', {
        theme: 'snow',
        placeholder: 'Quiz'
    });

    var question = JSON.parse($('#content_quiz').val());
    quiz_quill.setContents(question);
});

// Add new Option
$('#btn_new_option').on('click', function() {
    $('#mdl_option').modal('toggle');
});

// Save new Option
$('#frm_option').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit({
        beforeSubmit: function(formData, formObject, formOptions) {
            var correct = ($('#chk_new').prop('checked') == true) ? 1 : 0;

            formData.push({
                name: 'question_id',
                type: 'int',
                value: question_id
            });

            formData.push({
                name: 'correct',
                type: 'int',
                value: correct
            });
        },
        success: function(res) {
            if(res.success) {
                if(res.success) {
                    
                    var ele_option = $('#options').find('ul');
                    $('#mdl_option').modal('toggle');

                    if(ele_option.length > 0) {

                        $(res.html).hide().appendTo(ele_option).toggle(500);
                    } else {
                        $('#options').html(`
                            <ul class="list-group stack mb-40pt">`+ res.html +`</ul>`
                        );
                    }

                    $('input[name="option_text"]').val('');
                    $('textarea[name="explanation"]').val('');
                }
            } else {
                swal('Warning!', res.message, 'warning');
            }
        }
    })
});

// Update Question Content
$('#btn_question_save').on('click', function(e) {

    e.preventDefault();

    $('#frm_question').ajaxSubmit({
        beforeSubmit: function(formData, formObject, formOptions) {

            // Append quill data
            formData.push({
                name: 'question',
                type: 'text',
                value: JSON.stringify(quiz_quill.getContents().ops)
            });

            formData.push({
                name: 'test_id',
                type: 'int',
                value: $('#tests').val()
            });
        },
        success: function(res) {
            
            if(res.success) {
                swal("Success!", "Successfully updated", "success");
            }
        }
    })
});

// Update Option Text
$('#options').on('change', '.option-text', function(e) {

    var option_id = $(this).closest('li').attr('data-option-id');
    var option_text = $(this).val();
    var data = {
        id: option_id,
        option_text: option_text
    };

    optionUpdate(data);

});

// Update Explanation
$('#options').on('change', '.option-explanation', function(e) {

    var option_id = $(this).closest('li').attr('data-option-id');
    var explanation = $(this).val();
    var data = {
        id: option_id,
        explanation: explanation
    };

    optionUpdate(data);

});

// Update Correct
$('#options').on('change', '.option-correct', function(e) {

    var option_id = $(this).closest('li').attr('data-option-id');
    var correct = ($(this).prop('checked') == true) ? 1 : 0;
    var data = {
        id: option_id,
        correct: correct
    };

    optionUpdate(data);

});

// Ajax Header for Ajax Call
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

function optionUpdate(data) {

    var route = '/dashboard/questions_options/' + data.id;

    $.ajax({
        method: 'PATCH',
        url: route,
        data: data,
        success: function(res) {
            console.log(res);
        }
    });
}

// ==== Delete Option ==== //

$('#options').on('click', 'button.close', function(e) {

    var option_item = $(this).closest('li');
    var option_id = option_item.attr('data-option-id');
    var route = '/dashboard/questions_options/delete/' + option_id;

    swal({
        title: "Are you sure?",
        text: "This Option will removed from this Question",
        type: 'warning',
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: 'Confirm',
        cancelButtonText: 'Cancel',
        dangerMode: false,

    }, function(val) {
        if (val) {
            $.ajax({
                method: 'GET',
                url: route,
                success: function(res) {
                    if (res.success) {
                        option_item.toggle( function() { 
                            $(this).remove();
                            adjustOrder();
                        });
                    }
                }
            });
        }
    });
});

// Adjust option order
function adjustOrder() {

    var ele_lis = $('#options').find('li');

    $.each(ele_lis, function(idx, item) {
        $(item).find('.card-title').text('Option ' + (idx + 1));
    });
}

</script>
@endpush

@endsection