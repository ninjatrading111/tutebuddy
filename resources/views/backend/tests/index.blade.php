@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>

</style>
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Tests</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Tests
                        </li>

                    </ol>

                </div>
            </div>

            @can('test_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.tests.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">Tests</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.getTestsByAjax', 'all') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-all">{{ $count['all'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">All</strong>
                                <small class="card-subtitle text-50">All Tests</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getTestsByAjax', 'published') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-published">{{ $count['published'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Published</strong>
                                <small class="card-subtitle text-50">Published to Students</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getTestsByAjax', 'pending') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-pending">{{ $count['pending'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Pending</strong>
                                <small class="card-subtitle text-50">Draft Saved</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getTestsByAjax', 'deleted') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-deleted">{{ $count['deleted'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Archived</strong>
                                <small class="card-subtitle text-50">Deleted Tests</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date">
                <table id="tbl_tests" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th> @lang('labels.backend.table.no') </th>
                            <th> @lang('labels.backend.table.title') </th>
                            <th> @lang('labels.backend.table.course') </th>
                            <th> @lang('labels.backend.table.lesson') </th>
                            <th> @lang('labels.backend.table.status') </th>
                            <th> @lang('labels.backend.table.actions') </th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
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

    var table = $('#tbl_tests').DataTable(
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
                { data: 'status' },
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "You have no Tests"
            }
        }
    );

    $('#tbl_tests').on('click', 'a[data-action="publish"]', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                console.log(res);
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", 'Published successfully', "success");
                    } else {
                        swal("Success!", 'Unpublished successfully', "success");
                    }
                    
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                }
            }
        });
    });

    $('#tbl_tests').on('click', 'a[data-action="restore"]', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');

        swal({
            title: "Are you sure?",
            text: "This course will recovered",
            type: 'info',
            showCancelButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
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

    $(document).on('submit', 'form[name="delete_item"]', function(e) {

        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                } else {
                    swal("Warning!", res.message, "warning");
                }
            }
        });
    });

});
</script>
@endpush
@endsection