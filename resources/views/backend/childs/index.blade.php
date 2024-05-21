@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
[dir=ltr] .table {
    width: 100% !important;
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
                    <h2 class="mb-0">Review for {{ $child->name }}</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            Review for {{ $child->name }}
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">Review for {{ $child->name }}</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.child.courses', $child->id) }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active" data-tab="course">
                            <span class="h2 mb-0 mr-3 count-course">{{ $count['courses'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Course</strong>
                                <small class="card-subtitle text-50">Child Courses</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.child.assignments', $child->id) }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start" data-tab="assignment">
                            <span class="h2 mb-0 mr-3 count-assignment">{{ $count['assignments'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Assignment</strong>
                                <small class="card-subtitle text-50">Child Assignments</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.child.tests', $child->id) }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start" data-tab="test">
                            <span class="h2 mb-0 mr-3 count-test">{{ $count['tests'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Test</strong>
                                <small class="card-subtitle text-50">Child Test</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.child.quizzes', $child->id) }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start" data-tab="quiz">
                            <span class="h2 mb-0 mr-3 count-quiz">{{ $count['quizzes'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Quiz</strong>
                                <small class="card-subtitle text-50">Child Quiz</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Table for Courses -->
            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date"
                data-lists-sort-desc="true"
                data-lists-values='["js-lists-values-no"]'>

                <table id="tbl_course" class="table mb-0 thead-border-top-0 table-nowrap " data-page-length='5'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">@lang('labels.backend.table.no')</th>
                            <th>@lang('labels.backend.table.title')</th>
                            <th>@lang('labels.backend.table.owner')</th>
                            <th>@lang('labels.backend.table.category')</th>
                            <th>@lang('labels.backend.table.progress_percent')</th>
                            <th>@lang('labels.backend.table.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>

                <!-- Table for Assignment -->
                <table id="tbl_assignment" class="table mb-0 thead-border-top-0 table-nowrap " data-page-length='5'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> @lang('labels.backend.table.title') </th>
                            <th> @lang('labels.backend.table.due_date') </th>
                            <th> @lang('labels.backend.table.marks') </th>
                            <th> @lang('labels.backend.table.actions') </th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle"></tbody>
                </table>

                <table id="tbl_test" class="table mb-0 thead-border-top-0 table-nowrap " data-page-length='5'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> @lang('labels.backend.table.title') </th>
                            <th> @lang('labels.backend.table.duration') </th>
                            <th> @lang('labels.backend.table.marks') </th>
                            <th> @lang('labels.backend.table.actions') </th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>

                <table id="tbl_quiz" class="table mb-0 thead-border-top-0 table-nowrap " data-page-length='5'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> @lang('labels.backend.table.title') </th>
                            <th> @lang('labels.backend.table.type') </th>
                            <th> @lang('labels.backend.table.duration') </th>
                            <th> @lang('labels.backend.table.due_date') </th>
                            <th> @lang('labels.backend.table.total_marks') </th>
                            <th style="width: 100px;"> @lang('labels.backend.table.actions') </th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(document).ready(function() {
        
        var col_course = [
            { data: 'index'},
            { data: 'no'},
            { data: 'title' },
            { data: 'name'},
            { data: 'category'},
            { data: 'progress'},
            { data: 'action' }
        ],
        col_assignment = [
            { data: 'index' },
            { data: 'title' },
            { data: 'due' },
            { data: 'mark' },
            { data: 'action' }
        ],
        col_test = [
            { data: 'index' },
            { data: 'title' },
            { data: 'duration' },
            { data: 'mark' },
            { data: 'action' }
        ],
        col_quiz = [
            { data: 'index' },
            { data: 'title' },
            { data: 'type' },
            { data: 'duration' },
            { data: 'due' },
            { data: 'mark' },
            { data: 'action' }
        ];

        var tabs = ['course', 'assignment', 'test', 'quiz'];

        var route_course = $('#tbl_selector a[data-tab="course"]').attr('href');
        var table_course = $('#tbl_course').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route_course,
                    complete: function(res) {
                        $('#tbl_selector').find('span.count-course').text(res.responseJSON.count);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: eval('col_course'),
                oLanguage: {
                    sEmptyTable: "You have no Results"
                }
            }
        );

        var route_assignment = $('#tbl_selector a[data-tab="assignment"]').attr('href');
        var table_assignment = $('#tbl_assignment').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route_assignment,
                    complete: function(res) {
                        $('#tbl_selector').find('span.count-assignment').text(res.responseJSON.count);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: eval('col_assignment'),
                oLanguage: {
                    sEmptyTable: "You have no Results"
                }
            }
        );

        var route_test = $('#tbl_selector a[data-tab="test"]').attr('href');
        var table_test = $('#tbl_test').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route_test,
                    complete: function(res) {
                        $('#tbl_selector').find('span.count-test').text(res.responseJSON.count);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: eval('col_test'),
                oLanguage: {
                    sEmptyTable: "You have no Results"
                }
            }
        );

        var route_quiz = $('#tbl_selector a[data-tab="quiz"]').attr('href');
        var table_quiz = $('#tbl_quiz').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route_quiz,
                    complete: function(res) {
                        $('#tbl_selector').find('span.count-quiz').text(res.responseJSON.count);
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: eval('col_quiz'),
                oLanguage: {
                    sEmptyTable: "You have no Results"
                }
            }
        );

        $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
            e.preventDefault();
            var tab = $(this).attr('data-tab');
            display_table(tab);
        });

        function display_table(tab) {
            $.each(tabs, function(idx, item) {
                $(document).find('#tbl_'+ item + '_wrapper').addClass('d-none');
            });

            $(document).find('#tbl_'+ tab + '_wrapper').removeClass('d-none');
        }

        var tab =  $('#tbl_selector a[aria-selected="true"]').attr('data-tab');
        display_table(tab);
    });

</script>

@endpush


@endsection