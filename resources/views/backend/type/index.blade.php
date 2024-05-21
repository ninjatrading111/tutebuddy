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
                    <h2 class="mb-0">Course Types</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Course Types
                        </li>
                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="row mb-32pt">
            <div class="col-lg-4">
                <div class="page-separator">
                    <div class="page-separator__text">Add New Type</div>
                </div>
                <div class="flex" style="max-width: 100%">
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.types.store'], 'id' => 'frm_types']) !!}

                        <div class="form-group">
                            <label class="form-label" for="name">Name:</label>
                            <input type="text" class="form-control" name="name"
                                placeholder="Type Name ..">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                placeholder="Description .."></textarea>
                        </div>
                        <button type="button" id="btn_add_new" class="btn btn-outline-secondary">Add New</button>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="col-lg-8 d-flex">
                <div class="flex" style="max-width: 100%">
                    <div class="card m-0">
                        <div class="table-responsive" data-toggle="lists">

                            <table id="tbl_types" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                                <thead>
                                    <tr>
                                        <th style="width: 18px;" class="pr-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                                    data-target="#toggle" id="customCheckAlltoggle">
                                                <label class="custom-control-label" for="customCheckAlltoggle"><span
                                                        class="text-hide">Toggle all</span></label>
                                            </div>
                                        </th>
                                        <th> Name </th>
                                        <th> Description </th>
                                        <th style="width: 80px;"> Action </th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="toggle"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

<!-- List.js -->
<script src="{{ asset('assets/js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/list.js') }}"></script>

<!-- Tables -->
<script src="{{ asset('assets/js/toggle-check-all.js') }}"></script>
<script src="{{ asset('assets/js/check-selected-row.js') }}"></script>

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>

$(document).ready(function() {
    
    var table = $('#tbl_types').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: "{{ route('admin.getTypesByAjax') }}",
            columns: [
                { data: 'index'},
                { data: 'name' },
                { data: 'description'},
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "You have no Types"
            }
        }
    );

    $('#btn_add_new').on('click', function(e) {

        e.preventDefault();

        $('#frm_types').ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                } else {
                    swal("Error!", res.message, "error");
                }
            },
            error: function(err) {
                var errors = JSON.parse(err.responseText).errors;
                var msg = '';
                $.each(errors, function(key, item){
                    msg += item[0] + '\n';
                });

                swal("Error!", msg, "error");
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
