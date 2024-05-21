@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
    div.dataTables_wrapper div.dataTables_filter {
        margin: 15px 15px 0;
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
                    <h2 class="mb-0">Access History</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Access History
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="card mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists">
                <table id="tbl_history" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Access Time</th>
                            <th>IP address</th>
                            <th>Location</th>
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
        var table = $('#tbl_history').DataTable(
            {
                lengthChange: false,
                ordering:  false,
                info: false,
                bStateSave: true,
                serverSide: true,
                serverMethod: 'get',
                ajax: "{{ route('admin.ajax.getAccessHistoryByAjax') }}",
                columns: [
                    { data: 'index'},
                    { data: 'name'},
                    { data: 'email'},
                    { data: 'role'},
                    { data: 'access_time'},
                    { data: 'access_ip'},
                    { data: 'location'}
                ],
                oLanguage: {
                    sEmptyTable: "You have no History"
                }
            }
        );
    });
</script>

@endpush

@endsection