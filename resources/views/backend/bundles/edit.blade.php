@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

<!-- Flatpickr -->
<link type="text/css" href="{{ asset('assets/css/flatpickr.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/flatpickr-airbnb.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.paths.edit')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.bundles.index') }}">@lang('labels.backend.paths.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.paths.edit')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.bundles.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">

            {!! Form::open(['method' => 'PATCH', 'route' => ['admin.bundles.update', $bundle->id], 'files' => true, 'id' => 'frm_bundle']) !!}

            <div class="row">
                <div class="col-md-8">
                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.paths.information') </div>
                    </div>

                    <label class="form-label">@lang('labels.backend.paths.fields.title')</label>
                    <div class="form-group mb-24pt">
                        <input type="text" name="title" class="form-control form-control-lg"
                            placeholder="@lang('labels.backend.paths.fields.title')" value="{{ $bundle->title }}" tute-no-empty>
                    </div>

                    <label class="form-label">@lang('labels.backend.paths.fields.description')</label>
                    <div class="form-group mb-48pt">
                        <textarea name="short_description" class="form-control" cols="100%" rows="5"
                            placeholder="@lang('labels.backend.paths.fields.description')">{{ $bundle->description }}</textarea>
                        <small class="form-text text-muted">@lang('labels.backend.paths.description_note')</small>
                    </div>

                    <label class="form-label">@lang('labels.backend.paths.courses') </label>
                    <div class="form-group">
                        <select id="select_courses" name="courses[]" multiple="multiple"
                            class="form-control form-label" tute-no-empty>
                            @foreach($courses as $course)
                            <option value="{{ $course->id }}" @if(in_array($course->id, $bundle_courses)) selected @endif>
                                {{ $course->title }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.meta.meta_information')</div>
                    </div>

                    <label class="form-label">@lang('labels.backend.meta.title')</label>
                    <div class="form-group">
                        <input type="text" name="meta_title" class="form-control" placeholder="@lang('labels.backend.meta.title')" value="{{ $bundle->meta_title }}">
                    </div>

                    <label class="form-label">@lang('labels.backend.meta.description')</label>
                    <div class="form-group mb-24pt">
                        <textarea name="meta_description" class="form-control" cols="100%" rows="5"
                            placeholder="@lang('labels.backend.meta.description')">{{ $bundle->meta_description }}</textarea>
                        <small class="form-text text-muted">@lang('labels.backend.meta.description_note')</small>
                    </div>
                </div>

                <div class="col-md-4">

                    <div class="card">
                        <div class="card-header text-center">
                            <button type="button" id="btn_save_bundle" class="btn btn-accent">@lang('labels.backend.buttons.save_draft')</button>
                            <button type="button" id="btn_publish_bundle" class="btn btn-primary">@lang('labels.backend.buttons.publish')</button>
                            <a href="{{ route('bundles.show', $bundle->slug) }}" class="btn btn-info">@lang('labels.backend.buttons.preview')</a>
                        </div>
                        <div class="list-group list-group-flush" id="save_status">
                            @if($bundle->published == 0)
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.save_draft')</strong></a>
                                <i class="material-icons text-muted draft">check</i>
                            </div>
                            @else
                            <div class="list-group-item d-flex">
                                <a class="flex" href="javascript:void(0)"><strong>@lang('labels.backend.buttons.publish')</strong></a>
                                <i class="material-icons text-muted publish">check</i>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.general.information')</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <!-- Set Category -->
                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.sidebar.category')</label>
                                <select id="select_category" name="category_id" class="form-control custom-select" data-toggle="select">
                                    <option value="">@lang('labels.backend.sidebar.no_category')</option>
                                    @foreach ($parentCategories as $category)
                                    <option value="{{ $category->id }}" @if($bundle->category_id == $category->id)
                                        selected @endif>{{ $category->name }}</option>
                                    @if ($category->children()->count() > 0 )
                                    <?php $space = ''; ?>
                                    @include('backend.category.sub.option', ['category' => $category, 'space' =>
                                    $space, 'selected' => $bundle->category_id])
                                    @endif
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_category')</small>
                            </div>

                            <!-- Set Tags -->
                            <div class="form-group mb-0">
                                <label class="form-label">@lang('labels.backend.sidebar.tags')</label>
                                <select id="select_tags" name="tags[]" multiple="multiple" class="form-control">
                                    @foreach($tags as $tag)
                                    @php $course_tags = (!empty($course->tags)) ? json_decode($course->tags) : []; @endphp
                                    <option @if(in_array($tag->name, $course_tags)) selected @endif>{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.select_tags')</small>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.options')</div>
                    </div>

                    <div class="card">
                        <div class="card-body">

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_group" type="checkbox" checked="" class="custom-control-input">
                                    <label for="chk_group" class="custom-control-label form-label">@lang('labels.backend.sidebar.group_course')</label>
                                </div>
                            </div>

                            <!-- Set Max number in case of group course -->
                            <div class="form-group" for="chk_group">
                                <div class="row">
                                    <div class="col-md-6 pr-1">
                                        <label class="form-label">@lang('labels.backend.sidebar.min_students'):</label>
                                        <input type="number" name="min" class="form-control" min="1" value="3"
                                            placeholder="5">
                                    </div>
                                    <div class="col-md-6 pl-1">
                                        <label class="form-label">@lang('labels.backend.sidebar.max_students'):</label>
                                        <input type="number" name="max" class="form-control" min="1" value="30"
                                            placeholder="30">
                                    </div>
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.group_note')</small>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group" for="chk_group">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">
                                            @lang('labels.backend.sidebar.price')({{ getCurrency(config('app.currency'))['symbol'] }})</span></span>
                                    <input type="number" name="group_price" class="form-control" placeholder="5.00"
                                        value="{{ $bundle->group_price }}">
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.price_note')</small>
                            </div>

                            <div class="page-separator"></div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input id="chk_private" type="checkbox" checked="" class="custom-control-input">
                                    <label for="chk_private" class="custom-control-label form-label">@lang('labels.backend.sidebar.private_course')</label>
                                </div>
                            </div>

                            <!-- Set Price -->
                            <div class="form-group" for="chk_private">
                                <div class="input-group form-inline">
                                    <span class="input-group-prepend"><span
                                            class="input-group-text form-label">
                                            @lang('labels.backend.sidebar.price')({{ getCurrency(config('app.currency'))['symbol'] }})</span></span>
                                    <input type="number" name="private_price" class="form-control"
                                            value="{{ $bundle->private_price }}" placeholder="24.00">
                                </div>
                                <small class="form-text text-muted">@lang('labels.backend.sidebar.private_course_note')</small>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.thumb')</div>
                    </div>

                    <div class="card">
                        @if(empty($bundle->bundle_image))
                        <img src="{{asset('/assets/img/no-image.jpg')}}" id="img_bundle_image" alt="" width="100%">
                        @else
                        <img src="{{ asset('/storage/uploads/' . $bundle->bundle_image) }}" id="img_bundle_image" alt="" width="100%">
                        @endif
                        <div class="card-body">
                            <div class="custom-file">
                                <input type="file" name="bundle_image" id="bundle_image" class="custom-file-input" data-preview="#img_bundle_image">
                                <label for="bundle_image" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                            </div>
                        </div>
                    </div>

                    <div class="page-separator">
                        <div class="page-separator__text">@lang('labels.backend.sidebar.intro_video')</div>
                    </div>

                    <div class="card">
                        @if(!empty($bundle->mediaVideo))
                        <div class="embed-responsive embed-responsive-16by9">
                            <?php
                                $embed = Embed::make($bundle->mediaVideo->url)->parseUrl();
                                $embed->setAttribute([
                                    'id'=>'iframe_bundle_video',
                                    'class'=>'embed-responsive-item',
                                    'allowfullscreen' => ''
                                ]);
                            ?>
                            {!! $embed->getHtml() !!}
                        </div>
                        <div class="card-body">
                            <label class="form-label">@lang('labels.backend.sidebar.url')</label>
                            <input type="text" class="form-control" name="bundle_video" id="bundle_video_url"
                                data-video-preview="#iframe_bundle_video"
                                value="{{ $bundle->mediaVideo->url }}" placeholder="@lang('labels.backend.sidebar.url_placeholder')">
                            <small class="form-text text-muted">@lang('labels.backend.sidebar.url_note')</small>
                        </div>
                        @else
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item no-video" id="iframe_bundle_video" src=""
                                allowfullscreen="">
                            </iframe>
                        </div>
                        <div class="card-body">
                            <label class="form-label">@lang('labels.backend.sidebar.url')</label>
                            <input type="text" class="form-control" name="bundle_video" id="bundle_video_url" value=""
                                data-video-preview="#iframe_bundle_video"
                                placeholder="@lang('labels.backend.sidebar.url_placeholder')">
                            <small class="form-text text-muted">@lang('labels.backend.sidebar.url_note')</small>
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

</div>

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<!-- Flatpickr -->
<script src="{{ asset('assets/js/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/flatpickr.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>

$(function() {

    // Multiselect for Courses
    $('#select_courses').select2({ tags: true });

    // Single Select for category
    $('#select_category').select2();

    // Multiselect for Tags
    $('#select_tags').select2({ tags: true });

    $('#frm_bundle').submit(function(e) {
        e.preventDefault();
    });

    $('#btn_save_bundle').on('click', function() {
        save_bundle('draft');
    });

    $('#btn_publish_bundle').on('click', function() {
        save_bundle('publish');
    });

    function save_bundle(action) {

        if(!checkValidForm($('#frm_bundle'))){
            return false;
        }

        if(action == 'draft') {
            btnLoading($('#btn_save_bundle'), true);
        } else {
            btnLoading($('#btn_publish_bundle'), true);
        }

        $('#frm_bundle').ajaxSubmit({
            beforeSubmit: function(formData, formObject, formOptions) {

                formData.push({
                    name: 'action',
                    type: 'string',
                    value: action
                });
            },
            success: function(res) {
                if(res.success) {
                    swal("@lang('labels.backend.swal.success.title')", 'Successfully Updated', 'success');
                }

                if(action == 'draft') {
                    btnLoading($('#btn_save_bundle'), false);
                } else {
                    btnLoading($('#btn_publish_bundle'), false);
                }
            },
            error: function(err) {
                var errMsg = getErrorMessage(err);
                swal("@lang('labels.backend.general.error')", errMsg, 'error');

                if(action == 'draft') {
                    btnLoading($('#btn_save_bundle'), false);
                } else {
                    btnLoading($('#btn_publish_bundle'), false);
                }
            }
        })
    }
});
</script>

@endpush

@endsection