@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit page</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.pages.index') }}">pages</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Page
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.pages.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.pages.update', $page->id], 'files' => true, 'id' => 'frm_page']) !!}

            <div class="row">
                <div class="col-md-8">

                    <label class="form-label">Title</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            placeholder="title" value="{{ $page->title }}" required>
                        @error('title')
                        <div class="invalid-feedback">Title is required field.</div>
                        @enderror
                    </div>

                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control mb-24pt" value="{{ $page->slug }}">

                    <label class="form-label">Content</label>
                    <div class="form-group mb-48pt">
                        <!-- quill editor -->
                        <div id="page_editor" class="mb-0" style="min-height: 50vh;">{!! $page->content !!}</div>
                        <small class="form-text text-muted">Edit page</small>
                    </div>

                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control mb-24pt" value="{{ $page->meta_title }}">

                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control mb-24pt" rows="5">{{ $page->meta_description }}</textarea>

                    <label class="form-label">Meta Keywords</label>
                    <textarea name="meta_keywords" class="form-control mb-24pt">{{ $page->meta_keywords }}</textarea>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="submit" id="btn_save_bundle" class="btn btn-accent">Save Draft</button>
                            <button type="submit" id="btn_publish_bundle" class="btn btn-primary">Publish</button>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Save Draft</strong></a>
                                <i class="material-icons text-muted draft">clear</i>
                            </div>
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>Publish</strong></a>
                                <i class="material-icons text-muted publish">clear</i>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">Featured Image</div>
                    </div>

                    <div class="form-group">
                        <div class="card">
                            @if(!empty($page->image))
                            <img src="{{asset('/storage/uploads/' . $page->image )}}" id="featured_img" width="100%" alt="">
                            @else
                            <img src="{{asset('/assets/img/no-image.jpg')}}" id="featured_img" width="100%" alt="">
                            @endif
                            <div class="card-body">
                                <div class="custom-file">
                                    <input type="file" id="featured_file" name="image" class="custom-file-input" data-preview="#featured_img">
                                    <label for="file" class="custom-file-label">Choose file</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>

$(function() {

    var toolbarOptions = [
        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
        [{ 'color': [] }, { 'background': [] }],  
        ['bold', 'italic', 'underline'],
        ['link', 'blockquote', 'code', 'image'],
        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
        [{ 'indent': '-1'}, { 'indent': '+1' }],
    ];

    // Init Quill Editor for Page Content
    var editor = new Quill('#page_editor', {
        theme: 'snow',
        placeholder: 'Page Content',
        modules: {
            toolbar: toolbarOptions
        },
    });

    $('input[name="title"]').on('focusout', function(e) {
        var slug = convertToSlug($(this).val());
        $('input[name="slug"]').val(slug);
    });

    $('#frm_page').on('submit', function(e) {
        e.preventDefault();

        $(this).ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {
                var content = editor.root.innerHTML;

                // Append Course ID
                formData.push({
                    name: 'content',
                    type: 'text',
                    value: content
                });
            },
            success: function(res) {
                if(res.success) {
                    swal('Success!', 'Successfully Updated', 'success');
                }
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});

</script>

@endpush

@endsection