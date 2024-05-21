@extends('layouts.app')

@section('content')

@push('after-styles')

<style>
[dir=ltr] .avatar-2by1 {
    width: 8rem;
    height: 2.5rem;
}

[dir=ltr] label.content-left {
    justify-content: left;
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
                    <h2 class="mb-0">Settings</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item active">Feature</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator mb-4">
            <div class="page-separator__text">Customize Feature</div>
        </div>

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">
                <div class="card-header pb-1">
                    <h2>Customize Feature</h2>
                </div>

                <div class="card-body tab-content">
                    <div class="p-4">
                        <div class="form-group row">
                            <div class="avatar avatar-xxl avatar-2by1">
                                <img src="@if(!empty(config('nav_logo'))) 
                                        {{ asset('storage/logos/'.config('nav_logo')) }}
                                    @else 
                                        {{asset('/assets/img/no-image.jpg')}}
                                    @endif" alt="Avatar" class="avatar-img rounded" id="file_nav_logo_preview">
                            </div>
                            <div class="from-group col">
                                <label for="" class="form-label text-left">Logo for Nav menu (Light version): </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_nav_logo" name="nav_logo"
                                        accept="image/jpeg,image/gif,image/png"
                                        data-preview="#file_nav_logo_preview">
                                    <label for="file_nav_logo" class="custom-file-label">Choose file</label>
                                </div>
                                <small class="text-muted">Note : Upload logo with transparent background
                                    in .png format and 300x100(WxH) pixels.
                                    Height should be fixed, width according to your aspect ratio.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="avatar avatar-xxl avatar-2by1">
                                <img src="@if(!empty(config('nav_logo_dark'))) 
                                        {{ asset('storage/logos/'.config('nav_logo_dark')) }}
                                    @else 
                                        {{asset('/assets/img/no-image.jpg')}}
                                    @endif" alt="Avatar" class="avatar-img rounded" id="file_nav_logo_dark_preview">
                            </div>
                            <div class="from-group col">
                                <label for="" class="form-label text-left">Logo for Nav menu (dark version): </label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file_nav_logo_dark" name="nav_logo_dark"
                                        accept="image/jpeg,image/gif,image/png"
                                        data-preview="#file_nav_logo_dark_preview">
                                    <label for="file_nav_logo_dark" class="custom-file-label">Choose file</label>
                                </div>
                                <small class="text-muted">Note : Upload logo with transparent background
                                    in .png format and 300x100(WxH) pixels.
                                    Height should be fixed, width according to your aspect ratio.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="avatar avatar-xxl border avatar-1by1">
                                <img src="@if(!empty(config('sidebar_logo'))) 
                                        {{ asset('storage/logos/'.config('sidebar_logo')) }}
                                    @else 
                                        {{asset('/assets/img/no-image.jpg')}}
                                    @endif" alt="Avatar" class="avatar-img rounded" id="file_sidebar_logo_preview">
                            </div>
                            <div class="from-group col">
                                <label for="" class="form-label text-left">Logo for Sidebar menu: </label>
                                <div class="custom-file">
                                    <input type="file" id="file_sidebar_logo" name="sidebar_logo"
                                        class="custom-file-input" accept="image/jpeg,image/gif,image/png"
                                        data-preview="#file_sidebar_logo_preview">
                                    <label for="file_sidebar_logo" class="custom-file-label">Choose file</label>
                                </div>
                                <small class="text-muted">Note : Upload logo transparent background
                                    in .png format and 150x150(WxH) pixels.
                                    Height should be fixed, width according to your aspect ratio.</small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="avatar avatar-xxl border avatar-1by1">
                                <img src="@if(!empty(config('favicon'))) 
                                        {{ asset('storage/logos/'.config('favicon')) }}
                                    @else 
                                        {{asset('/assets/img/no-image.jpg')}}
                                    @endif" alt="Avatar" class="avatar-img rounded" id="file_favicon_preview">
                            </div>
                            <div class="from-group col">
                                <label for="" class="form-label text-left">Favicon: </label>
                                <div class="custom-file">
                                    <input type="file" id="file_favicon" name="favicon" class="custom-file-input"
                                        accept="image/jpeg,image/gif,image/png"
                                        data-preview="#file_favicon_preview">
                                    <label for="file_favicon" class="custom-file-label">Choose file</label>
                                </div>
                                <small class="text-muted">Note : Upload logo with resolution 32x32 pixels and
                                    extension
                                    .png or .gif or .ico</small>
                            </div>
                        </div>
                    </div>

                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
{{-- 
<script>
    
$(document).ready(function() {

    // Init code
    $('.show_secret').on('click', function(e) {
        var input_id = $(this).attr('data-id');
        if($(this).find('span.fa').hasClass('fa-eye')) {
            $('#' + input_id).attr('type', 'text');
            $(this).find('span.fa').removeClass('fa-eye');
            $(this).find('span.fa').addClass('fa-eye-slash');
        } else {
            $('#' + input_id).attr('type', 'password');
            $(this).find('span.fa').addClass('fa-eye');
            $(this).find('span.fa').removeClass('fa-eye-slash');
        }
    });
});

$('#frm_setting').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit({
        success: function(res) {

            if (res.success) {
                swal("Success!", "Successfully updated", "success");
            }
        }
    });
});

$('.custom-checkbox-toggle').on('click', 'input[type="checkbox"]', function() {

    var id = $(this).attr('id');

    if ($(this).prop('checked')) {
        $(this).val('1');
        $('div.wrap[for="' + id + '"').removeClass('d-none');
    } else {
        $(this).val('0');
        $('div.wrap[for="' + id + '"').addClass('d-none');
    }

});
</script> --}}

@endpush

@endsection