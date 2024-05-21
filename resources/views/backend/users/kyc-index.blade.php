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
                    <h2 class="mb-0">KYC Verifications</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            KYC Verifications
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Go To Home</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">
                    <div class="col-auto">
                        <a href="{{ route('admin.kyc.getKycByAjax', 'pending') }}" id="tab_pending" 
                            data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-pending">{{ $count['pending'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Pending</strong>
                                <small class="card-subtitle text-50">KYC Verification is pending.</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.kyc.getKycByAjax', 'approved') }}" id="tab_approved" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-approved">{{ $count['approved'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Approved</strong>
                                <small class="card-subtitle text-50">KYC Verification is Approved</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.kyc.getKycByAjax', 'rejected') }}" id="tab_rejected" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-rejected">{{ $count['rejected'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Rejected</strong>
                                <small class="card-subtitle text-50">KYC Verification is Rejected</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="{{ route('admin.kyc.getKycByAjax', 'unverified') }}" id="tab_unverified" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-unverified">{{ $count['unverified'] }}</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Unverified</strong>
                                <small class="card-subtitle text-50">Not Submitted KYC</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-name", "js-lists-values-email"]'>

                <table id="tbl_users" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a></th>
                            <th>Document Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

    $(function() {
        var route = $('#tbl_selector a[aria-selected="true"]').attr('href');

        $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
            e.preventDefault();
            route = $(this).attr('href');
            console.log(route);
            table.ajax.url( route ).load();
        });

        var table = $('#tbl_users').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: {
                    url: route,
                    complete: function(res) {
                        $.each(res.responseJSON.count, function(key, count) {
                            $('#tbl_selector').find('span.count-' + key).text(count);
                        });

                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                columns: [
                    { data: 'index'},
                    { data: 'name' },
                    { data: 'document'},
                    { data: 'status'},
                    { data: 'actions' }
                ],
                oLanguage: {
                    sEmptyTable: "You have no any registered users"
                }
            }
        );

    });
</script>

@endpush