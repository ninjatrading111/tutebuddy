@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

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
                            <select id="parent" name="parent" class="form-control custom-select" data-toggle="select">
                                <option value="no">No</option>
                                @foreach ($parentCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @if ($category->children()->count() > 0 )
                                <?php $space = ''; ?>
                                @include('backend.category.sub.option', ['category' => $category, 'space' => $space, 'selected' => 0])
                                @endif
                                @endforeach
                            </select>
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
                                        <th style="width: 18px;" class="pr-0">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input js-toggle-check-all"
                                                    data-target="#toggle" id="customCheckAlltoggle">
                                                <label class="custom-control-label" for="customCheckAlltoggle"><span
                                                        class="text-hide">Toggle all</span></label>
                                            </div>
                                        </th>
                                        <th> Category Name </th>
                                        <th> Description </th>
                                        <th> Action </th>
                                        <th style="width: 24px;" class="pl-0"></th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="toggle">
                                    @foreach ($parentCategories as $category)
                                        <tr data-id="{{ $category->id }}">
                                            <td class="pr-0">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox"
                                                        class="custom-control-input js-check-selected-row"
                                                        id="customCheck1_toggle2">
                                                    <label class="custom-control-label" for="customCheck1_toggle2"><span
                                                            class="text-hide">Check</span></label>
                                                </div>
                                            </td>
                                            <td class="category-name">
                                                <div class="media flex-nowrap align-items-center"
                                                    style="white-space: nowrap;">
                                                    <div class="avatar avatar-sm mr-8pt">
                                                        @if($category->thumb == '')
                                                        <span
                                                            class="avatar-title rounded-circle"><?php echo mb_substr($category->name, 0, 2); ?></span>
                                                        @else
                                                        <img src="/storage/uploads/{{$category->thumb}}" alt="Avatar"
                                                            class="avatar-img rounded-circle">
                                                        @endif
                                                    </div>
                                                    <div class="media-body">
                                                        <div class="d-flex flex-column">
                                                            <p class="mb-0"><strong
                                                                    class="js-lists-values-category-name">{{ $category->name }}</strong>
                                                            </p>
                                                            <small
                                                                class="js-lists-values-category-slug text-50">{{ $category->slug }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="small category-description">
                                                <?php 
                                                    if (strlen($category->description) > 30)
                                                        $description = mb_substr($category->description, 0, 30) . '...'; 
                                                    else
                                                        $description = $category->description;
                                                ?>
                                                {{ $description }}
                                            </td>
                                            <td class="category-action">
                                                <?php
                                                    $edit_route = route('admin.categories.edit', $category->id);
                                                    $delete_route = route('admin.categories.destroy', $category->id);
                                                ?>
                                                @include('backend.buttons.edit', ['edit_route' => $edit_route])
                                                @include('backend.buttons.delete', ['delete_route' => $delete_route])
                                            </td>
                                            <td class="text-right pl-0">
                                                <a href="javascript:void(0)" class="text-50"><i
                                                        class="material-icons">more_vert</i></a>
                                            </td>
                                        </tr>

                                        @if ($category->children()->count() > 0 )
                                            <?php $space = ''; ?>
                                            @include('backend.category.sub.tr', ['category' => $category, 'space' => $space])
                                        @endif
                                    @endforeach
                                </tbody>
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
    $('#tbl_categories').DataTable(
        {
            lengthChange: false,
            searching: false,
            ordering:  false,
            info: false
        }
    );
});

$(function() {

    $('#btn_add_new').on('click', function(e) {

        $('#frm_category').ajaxSubmit({
            success: function(res) {
                if(res.success) {

                    var parent_tr, parent_option;

                    if(res.category.parent !== undefined) {
                        parent_tr = $('#tbl_categories').find('tr[data-id="' + res.category.parent + '"]');
                        parent_option = $('#parent').find('option[value="' + res.category.parent + '"]');
                    } else {
                        parent_tr = $($('#tbl_categories tbody').find('tr')[0]);
                        parent_option = $($('#parent').find('option')[0]);
                    }

                    
                    // Add row after the parent tr
                    var tr = parent_tr.clone();

                    tr.attr('data-id', res.category.id);
                    tr.css('background-color', '#e9edf2');
                    tr.addClass('fade-out');
                    if(res.category.thumb !== undefined)
                        tr.find('td.category-name div.avatar>img').attr('src', '/storage/uploads/' + res.category.thumb);
                    else tr.find('td.category-name div.avatar').html('<span class="avatar-title rounded-circle">' + res.category.name.slice(0, 2) + '</span>')

                    tr.find('td.category-name div.media-body p>strong').text(res.category.name);
                    tr.find('td.category-name div.media-body small').text(res.category.slug);

                    if(res.category.parent !== undefined)
                        tr.find('td.category-name div.media').prepend($('<div class="dv" style="width: 30px;font-weight: 600;">&nbsp;</div>'));
                    
                    tr.find('td.category-description').text(res.category.description);
                    tr.find('td.category-action a[data-action="edit"]').attr('href', '/dashboard/categories/' + res.category.id + '/edit');
                    tr.find('td.category-action button[data-action="delete"] form').attr('action', '/dashboard/categories/' + res.category.id);
                   
                    if(res.category.parent !== undefined)
                        tr.insertAfter(parent_tr);
                    else tr.insertBefore(parent_tr);


                    // Add option
                    var option = parent_option.clone();
                    option.attr('value', res.category.id);

                    var parent_space = (parent_option.text().match(/-/g) || []).length;
                    var space = '';
                    for(var i=0; i<= parent_space; i++) {
                        space += '-';
                    }
                    option.text(' ' + space + ' ' + res.category.name);
                    option.insertAfter(parent_option);


                    // Init Inputs
                    $('#name').val('');
                    $('#slug').val('');
                    $('#description').val();
                    $('#parent').val('no').change();
                    $('#thumb').val('');
                }
            }
        });
    });

    $(document).on('submit', 'form[name="delete_item"]', function(e) {
        e.preventDefault();
        var btn_delete = $(this);
        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    btn_delete.closest('tr').remove();
                } else {
                    swal("Warning!", res.message, "warning");
                }
            }
        });
    });
});

</script>

@endpush
