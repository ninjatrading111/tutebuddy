@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Test Submitted</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Test Submitted
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Test Submitted</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">

                    <div class="col-auto">
                        <a href="{{ route('admin.admin.getSubmitedTestsByAjax', 'all') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-all">{{ $count['all'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">All</strong>
                                <small class="card-subtitle text-50">All Assignments</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.admin.getSubmitedTestsByAjax', 'marked') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-marked">{{ $count['marked'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Marked</strong>
                                <small class="card-subtitle text-50">Marked Assignments</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table" data-toggle="lists" data-lists-sort-by="js-lists-values-date">
                <table id="tbl_results" class="table mb-0 thead-border-top-0 table-nowrap">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>@lang('labels.backend.table.subject')</th>
                            <th>@lang('labels.backend.table.student')</th>
                            <th>@lang('labels.backend.table.attachment')</th>
                            <th>@lang('labels.backend.table.status')</th>
                            <th>@lang('labels.backend.table.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

    $(function() {

        var route = $('#tbl_selector a[aria-selected="true"]').attr('href');

        $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
            e.preventDefault();
            route = $(this).attr('href');
            table.ajax.url( route ).load();
        });

        var table = $('#tbl_results').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route,
                    complete: function(res) {
                        $.each(res.responseJSON.count, function(key, count){
                            $('#tbl_selector').find('span.count-' + key).text(count);
                        });

                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: [
                    { data: 'index' },
                    { data: 'subject' },
                    { data: 'student' },
                    { data: 'attachment' },
                    { data: 'status' },
                    { data: 'action' }
                ],
                oLanguage: {
                    sEmptyTable: "You have no test submitted"
                }
            }
        );
    });

</script>

@endpush

@endsection