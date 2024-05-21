@extends('layouts.app')

@section('content')

@push('after-scripts')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">
@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Account</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item">
                            <a href="">Account</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Edit Account
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">
        <div class="page-separator">
            <div class="page-separator__text">Profile &amp; Privacy</div>
        </div>
        <div class="col-md-7 p-0">

            {!! Form::open(array('id' => 'frm_account', 'route' => 'admin.users.store', 'files' => true, 'method'=>'POST', 'files' => true)) !!}

            <div class="form-group">
                <label class="form-label">Your photo</label>
                <div class="media align-items-center">
                    <a href="" class="media-left mr-16pt">
                        <img src="{{asset('/images/no-avatar.jpg')}}" id="user_avatar" alt="people" width="56" class="rounded-circle" />
                    </a>
                    <div class="media-body">
                        <div class="custom-file">
                            <input type="file" name="avatar" class="custom-file-input" id="avatar_file">
                            <label class="custom-file-label" for="avatar_file">Choose file</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Profile name</label>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'tute-no-empty' => true)) !!}
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                {!! Form::text('email', null, array('placeholder' => 'Email','class' => 'form-control', 'tute-no-empty' => true)) !!}
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                {!! Form::password('password', array('placeholder' => 'Password','class' => 'form-control', 'tute-no-empty' => true)) !!}
            </div>

            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                {!! Form::password('password_confirmation', array('placeholder' => 'Confirm Password','class' => 'form-control')) !!}
            </div>

            <div class="form-group">
                <label class="form-label">Role</label>
                {!! Form::select('roles[]', $roles,[], array('class' => 'form-control', 'multiple', 'data-toggle'=>'select', 'tute-no-empty' => true)) !!}
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" checked id="customCheck1">
                    <label class="custom-control-label" for="customCheck1">Display your real name on your profile</label>
                    <small class="form-text text-muted">If unchecked, your profile name will be displayed instead of your full name.</small>
                </div>
            </div>

            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" checked id="customCheck2">
                    <label class="custom-control-label" for="customCheck2">Allow everyone to see your profile</label>
                    <small class="form-text text-muted">If unchecked, your profile will be private and no one except you will be able to view it.</small>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save changes</button>

            {!! Form::close() !!}
        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection

@push('after-scripts')

@include('layouts.parts.sweet-alert')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<script>

    $(function() {

        $('#frm_account').on('submit', function(e) {
            e.preventDefault();

            if(!checkValidForm($(this))){
                return false;
            }

            $(this).ajaxSubmit({
                success: function(res) {
                    console.log(res);

                    if(res.success) {
                        swal({
                            title: "Success!",
                            text: res.message,
                            type: 'success',
                            showCancelButton: false,
                            showConfirmButton: true,
                            confirmButtonText: 'Confirm',
                            dangerMode: false,
                        }, function (val) {
                            if(val) {
                                window.location.href = "{!! route('admin.users.index') !!}";
                            }
                        });
                    } else {
                        swal('Error!', res.message, 'error');
                    }
                }
            });
        });
    });
    
    $('#avatar_file').on('change', function() {
        var target = $('#user_avatar');
        display_image(this, target);
    });
</script>
@endpush