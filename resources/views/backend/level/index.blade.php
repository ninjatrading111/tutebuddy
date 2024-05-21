@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

<style>
    div.dv {
        width: 30px;
        font-weight: 600;
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
                    <h2 class="mb-0">Levels</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Levels
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
                    <div class="page-separator__text">Add New Level</div>
                </div>
                <div class="flex" style="max-width: 100%">
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.levels.store'], 'id' => 'frm_level']) !!}

                        <div class="form-group">
                            <label class="form-label">Level Type:</label>
                            <select name="type" class="form-control" data-toggle="select">
                                @foreach($types as $type)
                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="name">Order:</label>
                            <input type="number" class="form-control" name="order"
                                placeholder="Level">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="name">Name:</label>
                            <input type="text" class="form-control" name="name"
                                placeholder="Level Name ..">
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

                            <table id="tbl_levels" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                                <thead>
                                    <tr>
                                        <th style="width: 18px;" class="pr-0"></th>
                                        <th style="width: 50px;"> Order </th>
                                        <th> Name </th>
                                        <th> Description </th>
                                        <th style="width: 60px;"> Action </th>
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

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<script>

$(document).ready(function() {

    var table = $('#tbl_levels').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            ajax: "{{ route('admin.getLevelsByAjax') }}",
            columns: [
                { data: 'index'},
                { data: 'order'},
                { data: 'name' },
                { data: 'description'},
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "You have no Levels"
            }
        }
    );

    $('select[name="type"]').select2({
        tags: true
    });

    $('#btn_add_new').on('click', function(e) {

        e.preventDefault();

        $('#frm_level').ajaxSubmit({
            success: function(res) {

                if(res.success) {
                    table.ajax.reload();
                    $(document).find('.tooltip.show').remove();
                    // Init Inputs
                    $('#name').val('');
                    $('#slug').val('');
                    $('#description').val('');

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

/**
 * Add new Row by position
 * @param {*} data - table data - jQuery element
 * @param {*} index - position - integter
 * @param {*} table - table element
 */
$.fn.dataTable.Api.register('row.addByPos()', function(data, index, table) {    
    var currentPage = this.page();

    //insert the row
    this.row.add(data);

    //move added row to desired index
    var rowCount = this.data().length - 1,
        insertedRow = this.row(rowCount).data(),
        tempRow;

    for (var i = rowCount; i >= index; i--) {
        tempRow = table.row(i - 1).data();
        tempRowNode = table.row(i - 1).node();
        tempval = parseInt($(tempRowNode).find('td.level-order').text());
        $(tempRowNode).find('td.level-order').text(tempval + 1);
        this.row(i).data(tempRow);
        this.row(i-1).data(insertedRow);
    }

    //refresh the current page
    this.page(currentPage).draw(false);
});

</script>

@endpush
