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
                    <h2 class="mb-0">@lang('labels.backend.courses.my_courses')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.courses.my_courses')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.courses.title')</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">

                    <div class="col-auto">
                        <a href="{{ route('admin.student.getMyCoursesByAjax', 'actived') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-actived">{{ $count['actived'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.actived')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.actived_my_courses')</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.student.getMyCoursesByAjax', 'deleted') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-deleted">{{ $count['deleted'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.achieved')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.courses.outdated')</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-date"
                data-lists-sort-desc="true"
                data-lists-values='["js-lists-values-no"]'>

                <table id="tbl_courses" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>

                            <th style="width: 40px;">
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-no">@lang('labels.backend.table.no')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-title">@lang('labels.backend.table.title')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                    data-sort="js-lists-values-title">@lang('labels.backend.table.owner')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort"
                                data-sort="js-lists-values-lead">@lang('labels.backend.table.category')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">@lang('labels.backend.table.progress_percent')</a>
                            </th>

                            <th>
                                <a href="javascript:void(0)" class="sort desc" data-sort="js-lists-values-date">@lang('labels.backend.table.actions')</a>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="list" id="projects"></tbody>
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

    var table = $('#tbl_courses').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: {
                url: route,
                complete: function(res) {
                    console.log(res);
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
                { data: 'name'},
                { data: 'category'},
                { data: 'progress'},
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "You have no enrolled Courses"
            }
        }
    );

    $('#tbl_courses').on('click', '.btn-pending', function(e) {
        swal('Information for you', 'The admin has made some changed and the changes are pending review and will be available shortly.', 'info');
    });

});

</script>

@endpush

@endsection