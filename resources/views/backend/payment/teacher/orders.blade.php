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
                    <h2 class="mb-0">@lang('labels.backend.payment.sales.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.payment.sales.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        {{-- @if(!$withdraw_able)
        <div class="alert alert-soft-accent alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            <div class="d-flex flex-wrap align-items-start">
                <div class="flex" style="min-width: 180px">
                    <small class="text-black-100">
                        <strong>Info - </strong> You can withdraw after <a href="{{ route('admin.myaccount') }}">
                            <b class="text-primary">KYC verification</b></a>
                    </small>
                </div>
            </div>
        </div>
        @endif --}}

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.payment.sales.account_balance')</div>
        </div>

        <div class="row mb-32pt">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card border-1 border-left-3 border-left-accent-yellow text-center mb-lg-0">
                    <div class="card-body">
                        <h4 class="h2 mb-0">
                            {{-- {{ getCurrency(config('app.currency'))['symbol'] . number_format($total, 2) }} --}}
                            $64.90
                        </h4>
                        <div>@lang('labels.backend.payment.sales.total_earning')</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                    <div class="card-body">
                        <h4 class="h2 mb-0">
                            {{-- {{ getCurrency(config('app.currency'))['symbol'] . number_format($earned_this_month, 2) }} --}}
                            $0.00
                        </h4>
                        <div>@lang('labels.backend.payment.sales.revenue_completed')</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card border-1 border-left-3 border-left-primary text-center mb-lg-0">
                    <div class="card-body">
                        <h4 class="h2 mb-0">
                            {{-- {{ getCurrency(config('app.currency'))['symbol'] . number_format($balance, 2) }} --}}
                            $60.36
                        </h4>
                        <div>@lang('labels.backend.payment.sales.revenue_pending')</div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <div class="card border-1 border-left-3 border-left-secondary text-center mb-lg-0">
                    <div class="card-body">
                        <h4 class="h2 mb-0">
                            {{-- {{ getCurrency(config('app.currency'))['symbol'] . number_format($available, 2) }} --}}
                            $0.00
                        </h4>
                        <div>@lang('labels.backend.payment.sales.affiliate_earning')</div>
                    </div>
                </div>
            </div>
            
        </div>

        {{-- <div class="card border-left-3 border-left-accent mb-32pt">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6">
                        <label for="">@lang('labels.backend.payment.sales.account_balance')</label>
                        <h4 class="mb-0">{{ getCurrency(config('app.currency'))['symbol'] . number_format($balance, 2) }}</h4>
                    </div>
                    <div class="col-md-6">
                        <label for=""></label>
                        <p><button id="btn_withdraw" class="btn btn-md btn-primary"
                            @if(!$withdraw_able) disabled @endif>@lang('labels.backend.payment.withdraw')</button></p>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="page-separator">
            <div class="page-separator__text">Search</div>
        </div>
        <div class="row justify-center item-center">
                        <div class="col-md-1 pt-2">
                <label class="form-label">Order Title</label>
            </div>
            <div class="col-md-3 mb-24pt">
                <input type="text" id="phone" class="form-control" placeholder="Order Title">
            </div>
            
            <div class="col-md-1 pt-2">
                <label class="form-label">Order Status</label>
            </div>
            <div class="col-md-3 form-group">
                <select name="level" class="form-control">
                    <option class="" value="all" selected>All Orders</option>
                    <option value="completed">Completed Orders</option>
                    <option value="pending">Pending Orders</option>
                    <option value="deleted">Deleted Order</option>
                </select>
            </div>
            <div class="col-md-1 pt-2">
                <label class="form-label">Ordered Date:</label>
            </div>
            <div class="col-md-3 pr-1">
                <div class="form-group mb-0">
                    <input type="date" name="date" class="form-control flatpickr-input" data-toggle="flatpickr">
                </div>
            </div>
        </div>
        <div class="row justify-center item-center">
            <div class="col-md-1 pt-2">
                <label class="form-label">Orderer name</label>
            </div>
            <div class="col-md-3 mb-24pt">
                <input type="text" id="name" class="form-control" placeholder="User name">
            </div>
            <div class="col-md-1 pt-2">
                <label class="form-label">Email</label>
            </div>
            <div class="col-md-3 mb-24pt">
                <input type="text" id="email" class="form-control" placeholder="Email">
            </div>
            <div class="col-md-1 pt-2">
                <label class="form-label">Subject</label>
            </div>
            <div class="col-md-3 form-group">
                <input type="text" id="subject" class="form-control" placeholder="Subject">
            </div>
        </div>
        <div class="row justify-center item-center mb-4">
            <div class="col-md-4"></div>
            <div class="col-md-4 panel-body demo-nifty-btn">
                <button class="btn btn-block btn-primary">Search</button>
            </div>
        </div>

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.payment.sales.revenue_per')</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.getCoursesByAjax', 'all') }}" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-all">
                                {{-- {{ $count['all'] }} --}}
                                5
                            </span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.all')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.orders.all')</small>
                            </span>
                        </a>
                    </div>
                    
                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'completed') }}" id="tab_completed" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-published">
                                {{-- {{ $count['completed'] }} --}}
                                4
                            </span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.completed')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.orders.completed')</small>
                            </span>
                        </a>
                    </div>
                    
                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'pending') }}" id="tab_pending" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-pending">
                                {{-- {{ $count['pending'] }} --}}
                                1
                            </span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.pending')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.orders.pending')</small>
                            </span>
                        </a>
                    </div>

                    {{-- <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'draft') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-draft">{{ $count['draft'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.draft')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.orders.draft')</small>
                            </span>
                        </a>
                    </div> --}}

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.getCoursesByAjax', 'deleted') }}" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-deleted">
                                {{-- {{ $count['deleted'] }} --}}
                                0
                            </span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.achieved')</strong>
                                <small class="card-subtitle text-50">@lang('labels.backend.orders.achieved')</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists">

                <table id="tbl_courses" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length="10">
                    <thead>
                        <tr>
                            <th class="pr-0"></th>
                            <th>
                                @lang('labels.backend.table.no')
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-title">Order Title</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-owner">Orderer Name</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-category">
                                    @lang('labels.backend.table.writer_level')
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-category">Started Date</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-category">
                                    @lang('labels.backend.table.deadline')
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-title">
                                    @lang('labels.backend.table.amount')
                                </a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">
                                    @lang('labels.backend.table.progress')
                                </a>
                            </th>
                            <th>
                                @lang('labels.backend.table.actions')
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
    function getUrlParameter(sParam) {
        let sPageURL = window.location.search.substring(1),
            sURLVariables = sPageURL.split('&'),
            sParameterName,
            i;

        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');

            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
            }
        }
    };

    var tab = getUrlParameter('tab');

    if (tab) {
        $('#tbl_selector').find('a[data-toggle="tab"]').removeAttr('aria-selected');
        $('#tbl_selector').find('a[data-toggle="tab"]').removeClass('active');

        if(tab == 'pending') {
            $('#tab_pending').attr('aria-selected', 'true');
            $('#tab_pending').addClass('active');
        }

        if(tab == 'completed') {
            $('#tab_completed').attr('aria-selected', 'true');
            $('#tab_completed').addClass('active');
        }
    }

    var route = $('#tbl_selector a[aria-selected="true"]').attr('href');
    // console.log(route, 'route');

    $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
        e.preventDefault();
        route = $(this).attr('href');
        table.ajax.url( route ).load();
    });

    var table = $('#tbl_courses').DataTable(
        {
            lengthChange: true,
            searching: true,
            ordering:  true,
            info: false,
            processing: true,
            serverSide: true,
            ajax: {
                url: route,
                complete: function(res) {
                    console.log(res, "res");
                    $.each(res.responseJSON.count, function(key, count){
                        $('#tbl_selector').find('span.count-' + key).text(count);
                    });

                    $('[data-toggle="tooltip"]').tooltip();
                }
            },
            rowReorder: true,
            columnDefs: [
                { orderable: false, targets: 0 },
                { orderable: false, targets: 1 },
                { orderable: true, targets: 2 },
                { orderable: true, targets: 3 },
                { orderable: true, targets: 4 },
                { orderable: true, targets: 5 },
                { orderable: false, targets: 6 }
            ],
            columns: [
                { data: 'index'},
                { data: 'no'},
                { data: 'title' },
                { data: 'name'},
                { data: 'writer' },
                { data: 'start_date' },
                { data: 'deadline'},
                { data: 'amount'},
                { data: 'status'},
                { data: 'detail'}
            ],
            oLanguage: {
                sEmptyTable: "You have no Courses"
            }
        }
    );

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

    $('#tbl_courses').on('click', 'a[data-action="restore"], a[data-action="delete"]', function(e) {

        e.preventDefault();
        var url = $(this).attr('href');

        var swal_text = "@lang('labels.backend.swal.paths.description.delete')";

        if($(this).attr('data-action') == 'restore') {
            swal_text = "@lang('labels.backend.swal.paths.description.restore')";
        }

        swal({
            title: "Are you sure?",
            text: swal_text,
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

    $('#tbl_courses').on('click', 'a[data-action="publish"]', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
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
</script>

@endpush

@endsection