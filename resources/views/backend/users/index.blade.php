@extends('layouts.admin.app')

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
                    <h2 class="mb-0">User Management</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>

                        <li class="breadcrumb-item active">
                            User Management
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="#" class="btn btn-outline-secondary">Go To Home</a>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="#" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">Search</div>
        </div>
        <div class="row justify-center item-center">
            <div class="col-md-1 pt-2">
                <label class="form-label">User Role</label>
            </div>
            <div class="col-md-3 form-group">
                <select name="level" class="form-control">
                    <option class="" value="all" selected>Super Admin</option>
                    <option value="completed">Admin</option>
                    <option value="pending">User</option>
                    <option value="deleted">Deleted User</option>
                </select>
            </div>
            <div class="col-md-1 pt-2">
                <label class="form-label">Registered Date:</label>
            </div>
            <div class="col-md-3 pr-1">
                <div class="form-group mb-0">
                    <input type="date" name="date" class="form-control flatpickr-input" data-toggle="flatpickr">
                </div>
            </div>
            <div class="col-md-1 pt-2">
                <label class="form-label">User Status</label>
            </div>
            <div class="col-md-3 form-group">
                <select name="level" class="form-control">
                    <option value="yesterday">Verified Users</option>
                    <option value="">Unverified Users</option>
                </select>
            </div>
        </div>
        <div class="row justify-center item-center">
            <div class="col-md-1 pt-2">
                <label class="form-label">User name</label>
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
                <label class="form-label">Phone Number</label>
            </div>
            <div class="col-md-3 mb-24pt">
                <input type="text" id="phone" class="form-control" placeholder="Email">
            </div>
        </div>
        <div class="row justify-center item-center mb-4">
            <div class="col-md-4"></div>
            <div class="col-md-4 panel-body demo-nifty-btn">
                <button class="btn btn-block btn-primary">Search</button>
            </div>
        </div>


        <div class="page-separator">
            <div class="page-separator__text">Users</div>
        </div>

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">

            <div class="card-header p-0 nav">
                <div id="tbl_selector" class="row no-gutters" role="tablist">

                    {{-- @can('user_create') --}}
                    {{-- <div class="col-auto">
                        <a href="#" id="tab_admin" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-admins">Superadmins</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Superadmins</strong>
                                <small class="card-subtitle text-50">Site Superadmins</small>
                            </span>
                        </a>
                    </div> --}}

                    <div class="col-auto border-left border-right">
                        <a href="#" id="tab_teacher" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-teachers">Admin</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Admins</strong>
                                <small class="card-subtitle text-50">Registered Admins</small>
                            </span>
                        </a>
                    </div>
                    {{-- @endcan --}}

                    <div class="col-auto border-left border-right">
                        <a href="#" id="tab_student" data-toggle="tab" role="tab" aria-selected="true"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                            <span class="h2 mb-0 mr-3 count-students">Users</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">Users</strong>
                                <small class="card-subtitle text-50">Registered Users</small>
                            </span>
                        </a>
                    </div>

                    <div class="col-auto border-left border-right">
                        <a href="#" id="tab_deleted" data-toggle="tab" role="tab"
                            class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                            <span class="h2 mb-0 mr-3 count-deleted">Deleted</span>
                            <span class="flex d-flex flex-column">
                                <strong class="card-title">@lang('labels.backend.general.achieved')</strong>
                                <small class="card-subtitle text-50">Deleted Users By Admin</small>
                            </span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="table-responsive" data-toggle="lists">

                <table id="tbl_users" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'
                    data-lists-sort-by="js-lists-values-name" 
                    data-lists-sort-desc="true"
                    data-lists-values="['js-lists-values-name', 'js-lists-values-email']">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-email">Email</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Register Date</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-status">Status</a>
                            </th>
                            <th>Role</th>
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

            if(tab == 'student') {
                $('#tab_student').attr('aria-selected', 'true');
                $('#tab_student').addClass('active');
            }

            if(tab == 'teacher') {
                $('#tab_teacher').attr('aria-selected', 'true');
                $('#tab_teacher').addClass('active');
            }
        }

        var route = $('#tbl_selector a[aria-selected="true"]').attr('href');

        $('#tbl_selector').on('click', 'a[role="tab"]', function(e) {
            e.preventDefault();
            route = $(this).attr('href');
            table.ajax.url( route ).load();
        });

        var table = $('#tbl_users').DataTable(
            {
                lengthChange: true,
                searching: true,
                ordering:  true,
                processing: true,
                serverSide: true,
                info: false,
                ajax: {
                    url: route,
                    complete: function(res) {
                        console.log(res,'res');
                        $.each(res.responseJSON.count, function(key, count) {
                            $('#tbl_selector').find('span.count-' + key).text(count);
                        });

                        $('[data-toggle="tooltip"]').tooltip();
                    }
                },
                rowReorder: true,
                columnDefs: [
                    { orderable: false, targets: 0 },
                    { orderable: true, targets: 1 },
                    { orderable: true, targets: 2 },
                    { orderable: true, targets: 3 },
                    { orderable: true, targets: 4 },
                    { orderable: false, targets: '_all' }
                ],
                columns: [
                    { data: 'index'},
                    { data: 'name' },
                    { data: 'email'},
                    { data: 'date' },
                    { data: 'status'},
                    { data: 'roles' },
                    { data: 'actions' }
                ],
                oLanguage: {
                    sEmptyTable: "You have no any registered users"
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

        $('#tbl_users').on('click', 'a[data-action="restore"], a[data-action="delete"]', function(e) {

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
                            if (res.success) {
                                table.ajax.reload();
                                $(document).find('.tooltip.show').remove();
                            } else {
                                swal("Warning!", res.message, "warning");
                            }
                        }
                    });
                }
            });
        });
    });
</script>

@endpush