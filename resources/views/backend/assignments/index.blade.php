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
                    <h2 class="mb-0">@lang('labels.backend.assignments.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.assignments.title')
                        </li>

                    </ol>

                </div>
            </div>

            @can('assignment_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.assignments.create') }}" class="btn btn-outline-secondary">@lang('labels.backend.buttons.add_new')</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.assignments.title')</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.getAssignmentsByAjax', 'all') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-all">{{ $count['all'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.all')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.assignments.all_assignments')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getAssignmentsByAjax', 'published') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-published">{{ $count['published'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.published')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.assignments.published')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getAssignmentsByAjax', 'pending') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-pending">{{ $count['pending'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.pending')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.general.draft_saved')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getAssignmentsByAjax', 'deleted') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-deleted">{{ $count['deleted'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.achieved')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.assignments.achived')</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date">
                <table id="tbl_assignments" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
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

    $('#tbl_assignments').on('click', 'a[data-action="publish"]', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                console.log(res);
                if(res.success) {
                    if(res.published == 1) {
                        swal("@lang('labels.backend.swal.success.title')", "@lang('labels.backend.swal.successfully_published')", "success");
                    } else {
                        swal("@lang('labels.backend.swal.success.title')", "@lang('labels.backend.swal.successfully_unpublished')", "success");
                    }
                    
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                }
            }
        });
    });

    $('#tbl_assignments').on('click', 'a[data-action="restore"]', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');

        swal({
            title: "@lang('labels.backend.swal.title.are_you_sure')",
            text: "@lang('labels.backend.swal.assignment.description.restore')",
            type: 'info',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: "@lang('labels.backend.general.confirm')",
            cancelButtonText: "@lang('labels.backend.general.cancel')",
            dangerMode: false,
        }, function (val) {
            if(val) {
                $.ajax({
                    method: 'GET',
                    url: url,
                    success: function(res) {
                        if(res.success) {
                            table.ajax.reload();
                            $(document).find('.tooltip.show').remove();
                        }
                    }
                });
            }
        });
    });

    $('#tbl_assignments').on('click', 'a[data-action="forever-delete"]', function(e) {
        e.preventDefault();
        var route = $(this).attr('href');
        swal({
            title: "@lang('labels.backend.swal.title.are_you_sure')",
            text: "@lang('labels.backend.swal.assignment.description.forever_delete')",
            type: 'warning',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: "@lang('labels.backend.general.confirm')",
            cancelButtonText: "@lang('labels.backend.general.cancel')",
            dangerMode: false,

        }, function(val) {
            if (val) {
                $.ajax({
                    method: 'GET',
                    url: route,
                    success: function(res) {
                        if (res.success) {
                            table.ajax.reload();
                            $(document).find('.tooltip.show').remove();
                        }
                    }
                });
            }
        });
    });

    $(document).on('submit', 'form[name="delete_item"]', function(e) {

        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                } else {
                    swal("@lang('labels.backend.general.warning')", res.message, "warning");
                }
            }
        });
    });

});
</script>
@endpush
@endsection