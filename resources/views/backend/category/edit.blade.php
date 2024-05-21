@extends('layouts.app')

@push('after-styles')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

@endpush

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Category</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.categories.index') }}">Categories</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Category
                        </li>

                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="row mb-32pt">
            <div class="col-lg-7">
                <div class="page-separator">
                    <div class="page-separator__text">Edit Category</div>
                </div>
                <div class="flex" style="max-width: 100%">

                    {!! Form::model($category, ['method' => 'PATCH', 'id' => 'frm_edit', 'files' => true, 'route' => ['admin.categories.update', $category->id]]) !!}

                        <div class="form-group">
                            <label class="form-label" for="name">Name:</label>
                            {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="slug">Slug:</label>
                            {!! Form::text('slug', null, array('placeholder' => 'Category Slug ..','class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="description">Description:</label>
                            {!! Form::textarea('description', null, array('placeholder' => 'Description ..','class' => 'form-control')) !!}
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="parent">Parent Category:</label>
                            <select id="parent" name="parent" class="form-control custom-select" data-toggle="select">
                                <option value="0">No</option>
                                @foreach ($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" @if($parentCategory->id == $category->parent) selected @endif>
                                    {{ $parentCategory->name }}
                                </option>
                                @if ($parentCategory->children()->count() > 0 )
                                <?php $space = ''; ?>
                                @include('backend.category.sub.option', ['category' => $parentCategory, 'space' => $space, 'selected' => $category->parent])
                                @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Related Level:</label>
                            <select name="level" class="form-control">
                                @foreach($levels as $level)
                                <option value="{{ $level->id }}" @if($level->id == $category->level_id) selected @endif>{{ $level->name }}</option>
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
                        <button type="submit" class="btn btn-outline-secondary">Save Changes</button>
                    </form>
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

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script>

$(function() {

    $('#frm_edit').on('submit', function(e) {
        e.preventDefault();

        $('#frm_edit').ajaxSubmit({
            success: function(res) {
                swal("Success!", "Successfully updated", "success");
            }
        });

    });
});

</script>

@endpush
