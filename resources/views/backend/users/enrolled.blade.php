@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
    /* tr[role="row"]:hover {
        box-shadow: 0px 0px 3px 0px black;
        cursor: pointer;
    } */
</style>

@endpush


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.my_students.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.my_students.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="card-header">
                <span class="page-separator__text bg-white mb-0"><strong>@lang('labels.backend.my_students.title')</strong></span>
            </div>
            <div class="table-responsive" data-toggle="lists">
                <table id="tbl_students" class="table mb-0 thead-border-top-0 table-nowrap border-bottom-2" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>@lang('labels.backend.table.name')</th>
                            <th>@lang('labels.backend.table.course')</th>
                            <th>@lang('labels.backend.table.enrolled_date')</th>
                            <th>@lang('labels.backend.table.process')</th>
                            <th>@lang('labels.backend.table.actions')</th>
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
    $(function () {
        
        var table = $('#tbl_students').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                ajax: "{{ route('admin.getEnrolledStudentsByAjax') }}",
                columns: [
                    { data: 'index'},
                    { data: 'name' },
                    { data: 'course'},
                    { data: 'enrolled_date' },
                    { data: 'status' },
                    { data: 'actions'}
                ],
                oLanguage: {
                    sEmptyTable: "You have no enrolled students"
                }
            }
        );
    });
</script>


@endpush

@endsection