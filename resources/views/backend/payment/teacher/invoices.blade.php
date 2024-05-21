@extends('layouts.app')

@section('content')

@push('after-styles')

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
                    <h2 class="mb-0">Invoices</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            Invoices
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.invoices.sales') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Sales Invoices</strong>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.invoices.service') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Service Invoices</strong>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date">
                <table id="tbl_invoices" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">@lang('labels.backend.table.no')</th>
                            <th> @lang('labels.backend.table.title') </th>
                            <th> @lang('labels.backend.table.course') </th>
                            <th> @lang('labels.backend.table.lesson') </th>
                            <th> @lang('labels.backend.table.status') </th>
                            <th> @lang('labels.backend.table.actions') </th>
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

    var table = $('#tbl_assignments').DataTable(
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
                { data: 'index'},
                { data: 'no'},
                { data: 'title' },
                { data: 'course'},
                { data: 'lesson'},
                { data: 'status'},
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "@lang('labels.backend.assignments.no_result')"
            }
        }
    );
});
</script>
@endpush
@endsection