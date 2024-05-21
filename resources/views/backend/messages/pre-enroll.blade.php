@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
    /* Button used to open the chat form - fixed at the bottom of the page */
    .open-button {
        background-color: #0085eb;
        color: white;
        padding: 12px;
        border: none;
        opacity: 0.8;
        position: fixed;
        bottom: 23px;
        right: 28px;
        border-radius: 100% !important;
        z-index: 99;
    }

    /* The popup chat - hidden by default */
    .chat-popup {
        display: none;
        position: fixed;
        bottom: 15px;
        right: 15px;
        z-index: 100;
        box-shadow: 0px 0 2px 0px black;
        border-radius: 5px;
    }

    /* Add styles to the form container */
    .form-container {
        max-width: 300px;
        padding: 10px;
        background-color: white;
        border-radius: 5px;
    }

    /* Full-width textarea */
    .form-container textarea {
        width: 100%;
        padding: 15px;
        margin: 5px 0 15px 0;
        border: none;
        background: #f1f1f1;
        resize: none;
        min-height: 100px;
    }

    /* When the textarea gets focus, do something */
    .form-container textarea:focus {
        background-color: #ddd;
        outline: none;
    }

    /* Add some hover effects to buttons */
    .form-container .btn:hover, .open-button:hover {
        opacity: 1;
    }

    #messages_content ul {
        background: #f1f1f1;
    }
</style>

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.pre_enrolled.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.pre_enrolled.title')
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.pre_enrolled.title')</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date">
                <table id="tbl_results" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> @lang('labels.backend.table.name') </th>
                            <th> @lang('labels.backend.table.course') </th>
                            <th> @lang('labels.backend.table.last_message')</th>
                            <th> @lang('labels.backend.table.message_time') </th>
                            <th> @lang('labels.backend.table.actions') </th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="chat-popup" id="dv_enroll_chat">
    <form method="POST" action="{{ route('admin.messages.sendEnrollChat') }}" class="form-container">@csrf
        <div class="media align-items-center mt-8pt mb-16pt">
            <div class="avatar avatar-sm avatar-online media-left mr-16pt">
                <span
                    class="avatar-title rounded-circle">ST</span>
            </div>
            <div class="media-body">
                <a class="card-title m-0" href="javascript:void(0)">@lang('labels.backend.general.student')</a>
            </div>
        </div>
        <div id="messages_content"></div>
        <textarea placeholder="Type message.." name="message" required></textarea>
        <input type="hidden" name="user_id" value="">
        <input type="hidden" name="course_id" value="">
        <input type="hidden" name="thread_id" value="">
        <button type="submit" class="btn btn-primary btn-block">@lang('labels.backend.pre_enrolled.send')</button>
        <button type="button" id="btn_enroll_end" class="btn btn-accent btn-block">@lang('labels.backend.pre_enrolled.close')</button>
    </form>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(function() {

    var table = $('#tbl_results').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: "{{ route('admin.ajax.getPreEnrolledStudentsData') }}",
            columns: [
                { data: 'index'},
                { data: 'name' },
                { data: 'course'},
                { data: 'last'},
                { data: 'time' },
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "@lang('labels.backend.pre_enrolled.no_result')"
            }
        }
    );

    var timer;

    // Pre Enroll Chat
    $('#tbl_results').on('click', 'button.start-chat', function() {
        var course_id = $(this).attr('data-course');
        var user_id = $(this).attr('data-user');
        timer = setInterval(loadMessage, 2000, course_id, user_id);
        $('#dv_enroll_chat').toggle('medium');
    });

    $('#dv_enroll_chat form').on('submit', function(e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    $('#messages_content').append()
                    if(res.action == 'send') {
                        $('#messages_content').append($('<ul class="d-flex flex-column list-unstyled p-2"></ul>'));
                    }
                    $(res.html).hide().appendTo('#messages_content ul').toggle(500);
                    $('textarea[name="message"]').val('');
                }
            }
        });
    });

    $('#btn_enroll_end').on('click', function() {
        clearInterval(timer);
        $('#dv_enroll_chat').toggle('medium');
    });

    function loadMessage(course_id, user_id) {

        $.ajax({
            method: 'GET',
            url: '{{ route("admin.messages.getEnrollThread") }}',
            data: {
                course_id: course_id,
                user_id: user_id,
                type: 'teacher'
            },
            success: function(res) {
                if(res.success) {
                    chat_status = true;
                    $('#dv_enroll_chat').find('input[name="thread_id"]').val(res.thread_id);
                    $('#messages_content').html(res.html);
                    // $(res.html).hide().appendTo('#messages_content').toggle(500);
                }
            }
        });
    };

});

</script>
@endpush
@endsection