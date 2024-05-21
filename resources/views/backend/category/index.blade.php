@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

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
                    <h2 class="mb-0">Categories</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Categories
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
                    <div class="page-separator__text">Add New Category</div>
                </div>
                <div class="flex" style="max-width: 100%">
                    <form action="{{ route('admin.categories.store') }}" id="frm_category" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Category Name ..">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="slug">Slug:</label>
                            <input type="text" class="form-control" id="slug" name="slug"
                                placeholder="Category Slug ..">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description:</label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                placeholder="Description .."></textarea>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="parent">Parent Category:</label>
                            <select id="parent" name="parent" class="form-control custom-select"></select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Related Level:</label>
                            <select name="level" class="form-control">
                                @foreach($levels as $level)
                                <option value="{{ $level->id }}">{{ $level->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="thumb">Thumbnail:</label>
                            <div class="custom-file">
                                <input type="file" id="thumb" name="thumb" class="custom-file-input">
                                <label for="thumb" class="custom-file-label">Choose file</label>
                            </div>
                        </div>
                        <button type="button" id="btn_add_new" class="btn btn-outline-secondary">Add New</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-8 d-flex">
                <div class="flex" style="max-width: 100%">
                    <div class="card m-0">
                        <div class="table-responsive" data-toggle="lists">

                            <table id="tbl_categories" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                                <thead>
                                    <tr>
                                        <th style="width: 18px;" class="pr-0"></th>
                                        <th> Category Name </th>
                                        <th> Description </th>
                                        <th> Action </th>
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
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script>

$(document).ready(function() {

    var table = $('#tbl_categories').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false,
            bStateSave: true,
            ajax: "{{ route('admin.table.getCategoriesByAjax') }}",
            columns: [
                { data: 'index'},
                { data: 'name' },
                { data: 'description'},
                { data: 'action' }
            ],
            oLanguage: {
                sEmptyTable: "You have no Categories"
            }
        }
    );

    var select = $('#parent').select2({
        ajax: {
            url: "{{ route('admin.select.getCategoriesByAjax') }}",
            dataType: 'json',
            delay: 250
        }
    });

    $('#btn_add_new').on('click', function(e) {

        e.preventDefault();

        $('#frm_category').ajaxSubmit({
            success: function(res) {
                if(res.success) {

                    table.ajax.reload(null, false);
                    
                    // Init Inputs
                    $('#name').val('');
                    $('#slug').val('');
                    $('#description').val('');
                    $('#thumb').val('');
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
                    table.ajax.reload(null, false);
                } else {
                    swal("Warning!", res.message, "warning");
                }
            }
        });
    });

});

</script>

@endpush
