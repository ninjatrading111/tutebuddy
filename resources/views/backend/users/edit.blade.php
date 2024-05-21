@extends('layouts.app')

@section('content')

@push('after-scripts')

<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet">

    <style>
        [dir=ltr] .avatar-2by1 {
            width: 8rem;
            height: 2.5rem;
        }

        [dir=ltr] label.content-left {
            justify-content: left;
        }

        .profile-avatar img {
            object-fit: cover;
            display: block;
            width: 250px;
            height: 250px;
            object-position: top;
        }
    </style>


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

            {{-- @can('user_delete')
            <div class="row" role="tablist">
                <div class="col-auto">
                    {!! Form::open(['method' => 'DELETE','route' => ['admin.users.destroy', $user->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-outline-secondary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            @endcan --}}
        </div>
    </div>
    
    <div class="page-section container page__container">
        
        {{-- {!! Form::model($user, ['method' => 'PATCH', 'files' => true, 'route' => ['admin.users.update', $user->id]]) !!} --}}

        <div class="">
            <div class="form-group">
                <div class="media">
                    <div class="media-left mr-32pt">
                        <label class="form-label">User photo</label>
                        <div class="profile-avatar mb-16pt">
                            {{-- @if($user->avatar) --}}
                                {{-- <img src="{{ asset('/storage/avatars/' . $user->avatar) }}" --}}
                                    {{-- id="user_avatar" alt="people" width="150" class="rounded-circle" /> --}}
                            {{-- @else --}}
                                <img src="{{ asset('/images/no-avatar.jpg') }}"
                                    id="user_avatar" alt="people" width="150" class="rounded-circle" />
                            {{-- @endif --}}
                        </div>
                        <div>
                            <div class="custom-file">
                                <input type="file" name="avatar" class="custom-file-input" id="avatar_file"
                                    data-preview="#user_avatar">
                                <label class="custom-file-label" for="avatar_file">Choose file</label>
                            </div>
                        </div>
                    </div>

                    <div class="media-body">

                        <div class="page-separator mt-32pt">
                            <div class="page-separator__text">User Information</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">User Name</label>
                                    {!! Form::text('name', null, array('placeholder' => 'Name','class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-24pt">
                                    <label class="form-label">Role</label>
                                    {!! Form::select('roles[]', $roles, $userRole, array('class' => 'form-control', 'multiple', 'data-toggle'=>'select')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                            {{-- @if($user->hasRole('admin')) --}}

                                <div class="form-group">
                                    <label class="form-label">Headline</label>
                                    {!! Form::text('headline', null, array('placeholder' => 'Headline', 'class' =>
                                    'form-control')) !!}
                                </div>

                                <div class="form-group">
                                    <label class="form-label">About you</label>
                                    {!! Form::textarea('about', null, array('placeholder' => 'About You...', 'class' =>
                                    'form-control', 'rows' => 5)) !!}
                                </div>

                            {{-- @endif --}}
                            </div>
                        </div>

                        <div class="row mt-24pt">
                            <div class="col-md-4">
                                <div class="form-group mb-24pt">
                                    <div class="flex form-inline" style="max-width: 100%">
                                        <label class="form-label" for="user_verified">Verify Status: </label>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" id="user_verified" name="verified" 
                                            {{-- @if($user->verified) checked="" @endif --}}
                                                class="custom-control-input" 
                                                {{-- value="{{ $user->verified }} " --}}
                                                >
                                            <label class="custom-control-label" for="user_verified">&nbsp;</label>
                                        </div>
                                        <label class="form-label mb-0" for="user_verified">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-24pt">
                                    <div class="flex form-inline" style="max-width: 100%">
                                        <label class="form-label" for="user_active">Suspend User: </label>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" id="user_active" name="active"
                                             {{-- @if($user->active) checked="" @endif --}}
                                                class="custom-control-input" 
                                                {{-- value="{{ $user->active }} " --}}
                                                >
                                            <label class="custom-control-label" for="user_active">&nbsp;</label>
                                        </div>
                                        <label class="form-label mb-0" for="user_active">Yes</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group mb-24pt">
                                    <div class="flex form-inline" style="max-width: 100%">
                                        <label class="form-label" for="user_pwd">Change Password: </label>
                                        <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                            <input type="checkbox" id="user_pwd" name="user_pwd" class="custom-control-input">
                                            <label class="custom-control-label" for="user_pwd">&nbsp;</label>
                                        </div>
                                        <label class="form-label mb-0" for="user_pwd">Yes</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row wrap d-none" for="user_pwd">
                            <div class="col-md-12">
                                <hr>
                                <div class="wrap">
                                    <div class="form-group">
                                        <div class="controls form-inline">
                                            <label class="form-label col-lg-3">Password:</label>
                                            <input class="form-control col-lg-8" type="text" name="password"
                                                placeholder="New Password" value="">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="controls form-inline">
                                            <label class="form-label col-lg-3">Confirm Password:</label>
                                            <input class="form-control col-lg-8" type="text" name="password_confirmation"
                                                placeholder="Confirm Password" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="page-separator mt-32pt">
                            <div class="page-separator__text">Contact Information</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Email Address</label>
                                    {!! Form::text('email', null, array('placeholder' => 'Email', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Phone Number</label>
                                    {!! Form::text('phone_number', null, array('placeholder' => 'Phone Number', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Country</label>
                                    {!! Form::text('country', null, array('placeholder' => 'Country', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">State</label>
                                    {!! Form::text('state', null, array('placeholder' => 'State', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">City</label>
                                    {!! Form::text('city', null, array('placeholder' => 'City', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Zip code</label>
                                    {!! Form::text('zip', null, array('placeholder' => 'Zip Code', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Address</label>
                                    {!! Form::text('address', null, array('placeholder' => 'Address', 'class' =>
                                    'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Timezone</label>
                                    <select name="timezone" class="form-control"></select>
                                </div>
                            </div>

                        </div>

                        {{-- @if($user->hasRole('admin')) --}}
                        <div class="page-separator mt-32pt">
                            <div class="page-separator__text">Profession</div>
                        </div>

                        <div class="form-group">
                            <select id="categories" name="categories[]" class="form-control" multiple="multiple">
                            {{-- @if(!empty($user->profession)) --}}

                            {{-- @php $pros = json_decode($user->profession); @endphp --}}

                            {{-- @foreach($pros as $pro) --}}
                            {{-- ?php
                                $category = App\Models\Category::find($pro);
                                $name = !empty($category) ? $category->name : $pro;
                            ?> --}}
                            <option value="" selected >name</option>
                            {{-- @endforeach --}}

                            {{-- @endif --}}
                            </select>
                        </div>

                            {{-- @if(auth()->user()->hasRole('Superadmin')) --}}
                                <div class="page-separator mt-32pt">
                                    <div class="page-separator__text">Withhold Period</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            {!! Form::text('withhold', null, array('placeholder' => '30', 'class' =>
                                            'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                            {{-- @endif --}}

                        {{-- @endif --}}

                    </div>
                </div>
            </div>

            <div class="form-group text-right">
                <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </div>

        {!! Form::close() !!}
    </div>

</div>
<!-- // END Header Layout Content -->

@endsection

@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2.js') }}"></script>

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

<script>
    // Timezone
    $('select[name="timezone"]').timezones();

    $('#avatar_file').on('change', function() {
        var target = $('#user_avatar');
        display_image(this, target);
    });

    var select = $('#categories').select2({
        ajax: {
            url: "{{ route('admin.select.getCategoriesByAjax') }}",
            dataType: 'json',
            delay: 250
        },
        tags: true
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
</script>
@endpush