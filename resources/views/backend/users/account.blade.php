@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Select2 -->
<link type="text/css" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">

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

<?php

if(!isset($_GET["active"])) {
    $_GET["active"] = 'account';
}

?>


<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.my_account.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.my_account.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        @include('layouts.parts.alert-messages')

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <div class="card-header p-0 nav">
                    <div class="row no-gutters" role="tablist">

                        <div class="col-auto">
                            <a href="#account" data-toggle="tab" role="tab" aria-selected="true"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.profile')</strong>
                                </span>
                            </a>
                        </div>

                        @if($user->hasRole('admin'))
                        <div class="col-auto border-left border-right">
                            <a href="#profession" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.personal_information')</strong>
                                </span>
                            </a>
                        </div>
                        @endif

                        <div class="col-auto border-left border-right">
                            <a href="#password" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.change_password')</strong>
                                </span>
                            </a>
                        </div>

                        @if(auth()->user()->hasRole('Superadmin') || auth()->user()->hasRole('admin'))
                        <div class="col-auto border-left border-right">
                            <a href="#bank" data-toggle="tab" role="tab" aria-selected="false"
                                class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                <span class="flex d-flex flex-column">
                                    <strong class="card-title">@lang('labels.backend.my_account.banking')</strong>
                                </span>
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->hasRole('User'))
                            <div class="col-auto border-left border-right">
                                <a href="#child" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">@lang('labels.backend.my_account.child_account')</strong>
                                    </span>
                                </a>
                            </div>
                        @endif

                        @if($user->hasRole('admin'))
                            @if (!isset($user->kyc) || (isset($user->kyc) && $user->kyc->status != 1))
                                <div class="col-auto border-left border-right">
                                    <a href="#kyc" data-toggle="tab" role="tab" aria-selected="false"
                                        class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                        <span class="flex d-flex flex-column">
                                            <strong class="card-title">KYC Verification</strong>
                                        </span>
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="card-body tab-content">

                    <!-- Tab Content for Profile Setting -->
                    <div id="account" class="tab-pane p-4 fade text-70 active show">

                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                        ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group">
                            <div class="media">
                                <div class="media-left mr-32pt">
                                    <label class="form-label">@lang('labels.backend.my_account.your_photo')</label>
                                    <div class="profile-avatar mb-16pt">
                                        @if($user->avatar)
                                            <img src="{{ asset('/storage/avatars/' . $user->avatar) }}"
                                                id="user_avatar" alt="people" width="150" class="rounded-circle" />
                                        @else
                                            <img src="{{ asset('/images/no-avatar.jpg') }}"
                                                id="user_avatar" alt="people" width="150" class="rounded-circle" />
                                        @endif
                                    </div>
                                    <div>
                                        <div class="custom-file">
                                            <input type="file" name="avatar" class="custom-file-input" id="avatar_file"
                                                data-preview="#user_avatar">
                                            <label class="custom-file-label" for="avatar_file">@lang('labels.backend.general.choose_file')</label>
                                        </div>
                                    </div>

                                </div>
                                <div class="media-body">
                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.profile_name')</label>
                                        {!! Form::text('name', null, array('placeholder' => "Name", 'class' =>
                                        'form-control', 'tute-no-empty' => true)) !!}
                                        <small class="form-text text-muted">
                                            @lang('string.backend.my_account.profile_name')
                                        </small>
                                    </div>

                                    @if($user->hasRole('admin'))

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.headline')</label>
                                        {!! Form::text('headline', null, array('placeholder' => "Headline", 'class' =>
                                        'form-control', 'tute-no-empty' => true)) !!}
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.about')</label>
                                        {!! Form::textarea('about', null, array('placeholder' => "About", 'class' =>
                                        'form-control', 'rows' => 5, 'tute-no-empty' => true)) !!}
                                    </div>

                                    @endif

                                    <div class="page-separator mt-32pt">
                                        <div class="page-separator__text bg-white">@lang('labels.backend.my_account.contact_information')</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.email_address')</label>
                                                <div class="input-group controls form-inline">
                                                    {{-- {!! Form::text('email', null, array('placeholder' => "Email Address", 'class' =>
                                                    'form-control', 'tute-no-empty' => true, 'disabled' => true)) !!} --}}
                                                    <label class="form-control m-0">{{ $user->email }}</label>
                                                    <div class="input-group-append" data-toggle="modal" data-target="#mdl_change_email" style="cursor: pointer">
                                                        <div class="input-group-text">
                                                            <span class="fa fa-edit icon-16pt"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.phone_number')</label>
                                                {!! Form::text('phone_number', null, array('placeholder' => "Phone Number", 'class' =>
                                                'form-control', 'tute-no-empty' => true)) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.country')</label>
                                                <?php $country_list = get_country_list(); ?>
                                                <select id="country_list" name="country" class="form-control" tute-no-empty>
                                                    @foreach($country_list as $country)
                                                        @if($user->country == $country)
                                                        <option value="{{ $country }}" selected>{{ $country }}</option>
                                                        @else
                                                        <option value="{{ $country }}">{{ $country }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.state')</label>
                                                {!! Form::text('state', null, array('placeholder' => "State", 'class' =>
                                                'form-control', 'tute-no-empty' => true)) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.city')</label>
                                                {!! Form::text('city', null, array('placeholder' => "City", 'class' =>
                                                'form-control', 'tute-no-empty' => true)) !!}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.zip_code')</label>
                                                {!! Form::text('zip', null, array('placeholder' => "Zip Code", 'class' =>
                                                'form-control', 'tute-no-empty' => true)) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">@lang('labels.backend.my_account.address')</label>
                                        {!! Form::text('address', null, array('placeholder' => "", 'class' =>
                                        'form-control', 'tute-no-empty' => true)) !!}
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.timezone')</label>
                                                <select name="timezone" class="form-control" tute-no-empty></select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group text-right">
                            @if($user->profile == 3)
                            <button type="submit" class="btn btn-primary" disabled>@lang('labels.backend.general.save_changes')</button>
                            @else
                            <button type="submit" class="btn btn-primary">@lang('labels.backend.buttons.submit')</button>
                            @endif
                        </div>

                        {!! Form::close() !!}
                    </div>

                    <!-- Tab Content for Professional Information -->
                    <div id="profession" class="tab-pane p-4 fade text-70">

                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                            ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group">
                            <div class="row form-inline mb-16pt">
                                <div class="col-10">
                                    <div class="page-separator">
                                        <div class="page-separator__text bg-white">
                                            @lang('labels.backend.my_account.profession_certification')
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button id="btn_add_qualifications" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                            </div>
                            <div class="wrap-qualifications">

                            @if(!empty($user->qualifications))

                                @foreach(json_decode($user->qualifications) as $qualification)
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="qualification[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.profession_certification')"
                                        value="{{ $qualification }}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                                @endforeach

                            @else
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="qualification[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.profession_certification')" >
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                            @endif

                            </div>
                        </div>

                        <div class="form-group mt-64pt">
                            <div class="row form-inline mb-16pt">
                                <div class="col-10">
                                    <div class="page-separator">
                                        <div class="page-separator__text bg-white">@lang('labels.backend.my_account.achievement')</div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <button id="btn_add_achievements" class="btn btn-md btn-outline-secondary" type="button">+</button>
                                </div>
                            </div>
                            <div class="wrap-achievements">

                            @if(!empty($user->achievements))

                                @foreach(json_decode($user->achievements) as $achievement)
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="achievement[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.achievement')"
                                        value="{{ $achievement }}">
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                                @endforeach

                            @else
                                <div class="row form-inline mb-8pt">
                                    <div class="col-10">
                                        <input type="text" name="achievement[]" class="form-control w-100" placeholder="@lang('labels.backend.my_account.achievement')" >
                                    </div>
                                    <div class="col-2">
                                        <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                                    </div>
                                </div>
                            @endif

                            </div>
                        </div>

                        <div class="form-group mt-64pt col-11">
                            <div class="page-separator">
                                <div class="page-separator__text bg-white">@lang('labels.backend.my_account.experience')</div>
                            </div>
                            {!! Form::textarea('experience', null, array('placeholder' => "Experience", 'class' =>
                                'form-control', 'rows' => 5)) !!}
                        </div>

                        <div class="form-group mt-64pt col-11">
                            <div class="page-separator">
                                <div class="page-separator__text bg-white">@lang('labels.backend.my_account.profession')</div>
                            </div>
                            <div class="form-group">
                                <select id="categories" name="categories[]" class="form-control" multiple="multiple">
                                @if(!empty($user->profession))

                                @php $pros = json_decode($user->profession); @endphp

                                @foreach($pros as $pro)
                                <?php
                                    $category = App\Models\Category::find($pro);
                                    $name = !empty($category) ? $category->name : $pro;
                                ?>
                                <option value="{{ $pro }}" selected >{{ $name }}</option>
                                @endforeach

                                @endif
                                </select>
                            </div>

                            <div class="form-group text-right">
                                @if($user->profile == 3)
                                <button type="submit" class="btn btn-primary" disabled>@lang('labels.backend.general.save_changes')</button>
                                @else
                                <button type="submit" class="btn btn-primary">@lang('labels.backend.buttons.submit')</button>
                                @endif
                                </div>
                        </div>

                        {{ Form::close() }}
                    </div>

                    <!-- Tab Content for Profile Setting -->
                    <div id="password" class="tab-pane p-4 fade text-70">
                        {!! Form::model($user, ['method' => 'POST', 'files' => true, 'route' =>
                        ['admin.myaccount.update', $user->id]]) !!}

                        <div class="form-group mb-48pt">
                            <label class="form-label" for="current_pwd">@lang('labels.backend.my_account.current_password'):</label>
                            <input id="current_pwd" name="current_password" type="password" class="form-control" 
                                placeholder="@lang('labels.backend.my_account.current_password_placeholder')" tute-no-empty>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_pwd">@lang('labels.backend.my_account.new_password'):</label>
                            <input id="new_pwd" name="new_password" type="password" class="form-control" 
                                placeholder="@lang('labels.backend.my_account.new_password_placeholder')">
                            <span class="invalid-feedback" role="alert">
                                Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                            </span>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="cfm_pwd">@lang('labels.backend.my_account.confirm_password'):</label>
                            <input id="cfm_pwd" name="confirm_password" type="password" class="form-control" placeholder="Confirm your new password ..." tute-no-empty>
                        </div>

                        <input type="hidden" name="update_type" value="password">

                        <button type="submit" class="btn btn-primary mt-48pt">@lang('labels.backend.my_account.save_password')</button>
                        {!! Form::close() !!}
                    </div>

                    <!-- Tab content for billing information -->
                    <div id="bank" class="tab-pane p-4 fade text-70">

                        <div class="col-lg-10 p-0">
                            <div class="list-group list-group-form">
                                <div class="list-group-item d-flex align-items-center">
                                    <div class="flex">
                                        @lang('labels.backend.my_account.bank_note')
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <fieldset aria-labelledby="label-type" class="m-0 form-group">
                                        <div class="form-row align-items-center">
                                            <label for="payment_cc" id="label-type" class="col-md-3 col-form-label form-label">
                                            @lang('labels.backend.my_account.payment_type')
                                            </label>
                                            <div role="group" aria-labelledby="label-type" class="col-md-9">
                                                <div role="group" class="btn-group btn-group-toggle" data-toggle="buttons">
                                                    <label class="btn btn-outline-secondary">
                                                        <input type="radio" id="payment_bank" name="payment_type" value="cc" checked="" aria-checked="true">
                                                        @lang('labels.backend.my_account.bank_detail')
                                                    </label>
                                                    <label class="btn btn-outline-secondary active">
                                                        <input type="radio" id="payment_account" name="payment_type" value="pp" aria-checked="true">
                                                        @lang('labels.backend.my_account.link_account')
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>

                                {!! Form::model($user->bank, ['method' => 'POST', 'route' => ['admin.myaccount.update', $user->id]]) !!}
                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.account_number') *</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('account_number', null, 
                                                array(
                                                    'placeholder' => "Account Number",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.ifsc') *</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('ifsc', null, 
                                                array(
                                                    'placeholder' => "IFSC",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.beneficiary_name') *</label>
                                        <div class="col-sm-9">
                                            {!! Form::text('account_holder_name', null, 
                                                array(
                                                    'placeholder' => "Beneficiary Name",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">@lang('labels.backend.my_account.account_type')</label>
                                        <div class="col-sm-9">
                                            {!! Form::select(
                                                'account_type', 
                                                array('saving' => 'Saving', 'current' => 'Current'), 
                                                null,
                                                array('class' => 'form-control')
                                                ) !!}
                                        </div>
                                    </div>
                                </div>

                                @if(auth()->user()->hasRole('admin'))
                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">GST:</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                                @if(isset(auth()->user()->bank) && auth()->user()->bank->gst_status == 1)
                                                <input type="checkbox" id="chk_gst" name="gst_status" class="custom-control-input" checked value="1">
                                                @else
                                                <input type="checkbox" id="chk_gst" name="gst_status" class="custom-control-input" value="0">
                                                @endif
                                                <label class="custom-control-label" for="chk_gst">&nbsp;</label>
                                            </div>
                                            <label class="form-label mb-0" for="chk_gst">Yes</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item 
                                    @if((isset(auth()->user()->bank) && auth()->user()->bank->gst_status == 0) 
                                    || !isset(auth()->user()->bank)) d-none @endif" 
                                    for="chk_gst">
                                    <div class="form-group row align-items-center mb-0">
                                        <label class="col-form-label form-label col-sm-3">GST Number: </label>
                                        <div class="col-sm-9">
                                            {!! Form::text('gst_number', null, 
                                                array(
                                                    'placeholder' => "GST Number",
                                                    'class' => 'form-control'
                                                )) !!}
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="list-group-item">
                                    <div class="form-group row align-items-center mb-0">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <input type="hidden" name="update_type" value="bank">
                                            <button type="submit" class="btn btn-primary">@lang('labels.backend.general.save_changes')</button>
                                        </div>
                                    </div>
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                    <!-- Tab for Child Account -->
                    <div id="child" class="tab-pane p-4 fade text-70">

                        <div class="accordion js-accordion accordion--boxed mb-24pt" id="parent">

                            @foreach($user->child() as $child)
                            <div class="accordion__item" lesson-id="{{ $child->id }}">
                                <a href="#" class="accordion__toggle collapsed" data-toggle="collapse"
                                    data-target="#child-{{ $child->id }}" data-parent="#parent">
                                    <span class="flex">{{ $child->name }}</span>
                                    <span class="accordion__toggle-icon material-icons">keyboard_arrow_down</span>
                                </a>
                                <div class="accordion__menu collapse" id="child-{{ $child->id }}">
                                    <div class="accordion__menu-link">
                                        <form method="POST" action="{{ route('admin.myaccount.child.update') }}" class="w-100" enctype="multipart/form-data">@csrf

                                            <div class="page-separator">
                                                <div class="page-separator__text bg-transparent">&nbsp;</div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.username'): </label>
                                                <span class="font-size-16pt">{{ $child->username }}</span>
                                                <input type="hidden" name="child_id" class="form-control" value="@if($child){{ $child->id }}@endif">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.child_name')</label>
                                                <input type="text" name="name" class="form-control"
                                                    value="@if($child) {{ $child->name }} @endif" placeholder="@lang('labels.backend.my_account.child_name')">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.child_nick_name')</label>
                                                <input type="text" name="nick_name" class="form-control"
                                                    value="@if($child) {{ $child->nick_name }} @endif" placeholder="Nick Name">
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.password')</label>
                                                <input type="password" name="password" class="form-control" value="" 
                                                    placeholder="@lang('labels.backend.my_account.password')">
                                                <span class="invalid-feedback" role="alert">
                                                    Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-label">@lang('labels.backend.my_account.confirm_password')</label>
                                                <input type="password" name="password_confirmation" class="form-control" value="" 
                                                    placeholder="@lang('labels.backend.my_account.password')">
                                            </div>

                                            <div class="form-group mt-32pt">
                                                <button type="submit" class="btn btn-primary">@lang('labels.backend.my_account.update_child_account')</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach

                        </div>

                        {{-- <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="chkChild" @if($user->child()) checked="" @endif>
                                <label class="custom-control-label" for="chkChild">
                                    @lang('labels.backend.my_account.add_child_account.title')
                                </label>
                                <small class="form-text text-muted">
                                    @lang('labels.backend.my_account.add_child_account.description')
                                </small>
                            </div>
                        </div> --}}

                        <div class="form-group">
                            <button type="button" id="btn_add_child" class="btn btn-outline-secondary btn-block mb-24pt mb-sm-0">+ Add Child</button>
                        </div>

                        <form id="frm_child" method="POST" action="{{ route('admin.myaccount.child') }}" enctype="multipart/form-data"
                            style="display: none;">@csrf

                            <div class="page-separator">
                                <div class="page-separator__text bg-transparent">&nbsp;</div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.child_name')</label>
                                <input type="text" name="name" class="form-control" tute-no-empty
                                    value="" placeholder="@lang('labels.backend.my_account.child_name')">
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.child_nick_name')</label>
                                <input type="text" name="nick_name" class="form-control" placeholder="Nick Name" tute-no-empty autocomplete="off" />
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.password')</label>
                                <input type="password" name="password" class="form-control" value="" 
                                    placeholder="@lang('labels.backend.my_account.password')" tute-no-empty autocomplete="off" />
                                <span class="invalid-feedback" role="alert">
                                    Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                                </span>
                            </div>

                            <div class="form-group">
                                <label class="form-label">@lang('labels.backend.my_account.confirm_password')</label>
                                <input type="password" name="password_confirmation" class="form-control" value="" 
                                    placeholder="@lang('labels.backend.my_account.password')">
                            </div>

                            {{-- <div class="form-group align-items-end d-flex">
                                <div class="flex mr-16pt">
                                    <label class="form-label">@lang('labels.backend.my_account.parent_phone_number')</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="{{ $user->phone_number }}">
                                </div>
                                <div class="justify-content-end">
                                    <button type="button" class="btn btn-primary">@lang('labels.backend.my_account.send_otp')</button>
                                </div>
                            </div>

                            <div class="form-group align-items-end d-flex">
                                <div class="flex mr-16pt">
                                    <label class="form-label">@lang('labels.backend.my_account.enter_otp')</label>
                                    <input type="text" name="otp" class="form-control" value="">
                                </div>
                                <div class="justify-content-end">
                                    <button type="button" class="btn btn-primary">@lang('labels.backend.buttons.verify')</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    @lang('labels.backend.my_account.upload_parent_id')
                                </label>
                                <div class="custom-file">
                                    <input type="file" id="file" class="custom-file-input">
                                    <label for="file" class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                                </div>
                                <small class="form-text text-muted">
                                    @lang('labels.backend.my_account.upload_parent_description')
                                </small>
                            </div> --}}

                            {{-- <div class="form-group">
                                <label class="form-label">
                                    @lang('labels.backend.my_account.relationship_to_child')
                                </label>
                                <input type="text" name="relation" class="form-control"
                                    value="{{ $user->relationship }}">
                            </div> --}}

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkChildTerm" tute-no-empty />
                                    <label class="custom-control-label" for="chkChildTerm">
                                        @lang('string.backend.my_account.terms_and_condition_note')
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="chkChildlegal" tute-no-empty />
                                    <label class="custom-control-label" for="chkChildlegal">
                                        @lang('string.backend.my_account.legal_note')
                                    </label>
                                </div>
                            </div>

                            <div class="form-group mt-32pt">
                                <button type="submit" class="btn btn-primary">@lang('labels.backend.my_account.create_child_account')</button>
                            </div>
                        </form>

                    </div>

                    <!-- KYC Verification for Teacher -->
                    <div id="kyc" class="tab-pane p-4 fade text-70">
                        <div class="col-lg-12 p-0">
                            <div class="list-group list-group-form">

                                <div id="kyc_alert_wrap" @if (isset($user->kyc) && $user->kyc->status == 1 || !isset($user->kyc)) class="d-none" @endif>
                                    <div class="list-group-item">
                                        <div class="form-group row align-items-center mb-0">
                                            <div class="alert alert-primary w-100 mb-0" role="alert">
                                                <div class="d-flex flex-wrap align-items-start">
                                                    <div class="mr-8pt">
                                                        <i class="material-icons">access_time</i>
                                                    </div>
                                                    <div class="flex" style="min-width: 180px">
                                                        <small id="kyc_alert_content" class="text-black-100" style="font-size: 16px;">
                                                            @if (isset($user->kyc) && $user->kyc->status == 0)
                                                            <strong>Under Review: </strong> Your KYC submittion is under review.
                                                            @endif

                                                            @if (isset($user->kyc) && $user->kyc->status == 2))
                                                            <strong>KYC Rejected, Reason: </strong> {!! $user->kyc->content !!}
                                                            @endif
                                                        </small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="list-group-item d-flex align-items-center">
                                    <div class="flex">
                                        <p class="text-mute text-70">Accepted Documents </p>
                                        <ul>
                                            <li>1. Government Issued ID (Front and Back)</li>
                                            <li>2. Passport (First and Last Page)</li>
                                            <li>3. Valid Driving License (Both Sides)</li>
                                            <li>4. Your documents must contain the same name and other details that you used to create your account on Tutebuddy
                                                instructions</li>
                                        </ul>
                                    </div>
                                </div>

                                @if (!isset($user->kyc) || (isset($user->kyc) && $user->kyc->status == 2))
                                <div id="kyc_frm_wrap">
                                    {!! Form::model($user->kyc, ['method' => 'POST', 'route' => ['admin.myaccount.update', $user->id]]) !!}
                                    <div class="list-group-item">
                                        <div class="form-group row align-items-center mb-0">
                                            <label class="col-form-label form-label col-sm-3">Document Type</label>
                                            <div class="col-sm-9">
                                                {!! Form::select(
                                                    'document_type', 
                                                    array(
                                                        'government_id' => 'Government Issued ID (Front and Back)',
                                                        'passport' => 'Passport (First and Last Page)',
                                                        'drive_license' => 'Valid Driving License (Both Sides)',

                                                    ), 
                                                    null,
                                                    array('class' => 'form-control')
                                                    ) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item">

                                        <div class="form-group row align-items-center mb-0">
                                            <div class="col-sm-4">
                                                <label class="col-form-label form-label">Upload Front Side</label>
                                                <div class="card">
                                                    <img src="{{asset('/assets/img/no-image.jpg')}}" id="img_front_image" alt="" width="100%">
                                                    <div class="card-body">
                                                        <div class="custom-file">
                                                            <input type="file" name="front_image" id="front_image" class="custom-file-input" 
                                                                accept=".jpg, .jpeg, .png" data-preview="#img_front_image" tute-no-empty>
                                                            <label for="front_image" class="custom-file-label">Choose File</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-4">
                                                <label class="col-form-label form-label">Upload Back Side</label>
                                                <div class="card">
                                                    <img src="{{asset('/assets/img/no-image.jpg')}}" id="img_back_image" alt="" width="100%">
                                                    <div class="card-body">
                                                        <div class="custom-file">
                                                            <input type="file" name="back_image" id="back_image" class="custom-file-input" 
                                                                accept=".jpg, .jpeg, .png" data-preview="#img_back_image" tute-no-empty>
                                                            <label for="back_image" class="custom-file-label">Choose File</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item">
                                        <div class="form-group row align-items-center mb-0">
                                            <label class="col-form-label form-label col-sm-3">Document IDNumber</label>
                                            <div class="col-sm-9">
                                                {!! Form::text('document_id', null, 
                                                array(
                                                    'placeholder' => "Document IDNumber",
                                                    'class' => 'form-control',
                                                    'tute-no-empty' => true
                                                )) !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="list-group-item">
                                        <div class="form-group row align-items-center mb-0">
                                            <div class="col-sm-9">
                                                <input type="hidden" name="update_type" value="kyc">
                                                <button type="submit" class="btn btn-primary" data-button-type="kyc">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif                               

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mdl_change_email" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Change Accound Email</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="dv_alert_1" class="alert alert-accent mb-4 d-none" role="alert">

                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>

                    <div class="d-flex flex-wrap align-items-center">
                        <i class="material-icons mr-8pt">error</i>
                        <div class="media-body" style="min-width: 180px">
                            New Email is required.
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Current Email: </label>
                    <p class="form-control" >{{ $user->email }}</p>
                    <input type="hidden" id="current_email" value="{{ $user->email }}">
                </div>

                <div class="form-group">
                    <label class="form-label">New Email: </label>
                    <input type="email" id="new_email" class="form-control" tute-no-empty>
                </div>
                <div class="form-group">
                    <label class="form-label">Re-enter New Email: </label>
                    <input type="email" id="new_email_confirmation" class="form-control" tute-no-empty>
                </div>

                <div id="dv_verification" class="verify-section mt-4 d-none">
                    <div id="dv_alert_2" class="alert alert-info" role="alert">
                        <div class="d-flex flex-wrap align-items-center">
                            <i class="material-icons mr-8pt">info</i>
                            <div class="media-body" style="min-width: 180px">
                                Verificaftion code sent to your old email address. Enter the code below
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Verify Code: </label>
                        <input type="text" id="verify_code" class="form-control" tute-no-empty>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
            <div class="modal-footer">
                <button id="btn_mdl_save" type="button" class="btn btn-primary" data-step="0">Change Email</button>
                <a id="btn_mdl_close" class="btn btn-primary d-none" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    Close
                </a>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

    <!-- Timezone Picker -->
    <script src="{{ asset('assets/js/timezones.full.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.js') }}"></script>

    <script>
        $(function () {
            var active_tab = '{{ $_GET["active"] }}';
            $('div[role="tablist"]').find('a').removeClass('active');
            $('div[role="tablist"]').find('a[href="#' + active_tab + '"]').addClass('active');

            $('div.tab-pane').removeClass('show');
            $('div.tab-pane').removeClass('active');
            $('#' + active_tab).addClass('active');
            $('#' + active_tab).addClass('show');

            $('#btn_add_child').on('click', function(e) {
                $('#frm_child').show();
            });

            // Timezone
            $('select[name="timezone"]').timezones();
            $('select[name="timezone"]').val('{{ $user->timezone }}').change();

            var select = $('#categories').select2({
                ajax: {
                    url: "{{ route('admin.select.getCategoriesByAjax') }}",
                    dataType: 'json',
                    delay: 250
                },
                tags: true
            });

            var tmp = `<div class="row form-inline mb-8pt">
                            <div class="col-10">
                                <input type="text" class="form-control w-100" placeholder="Professional Qualifications and Certifications">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-md btn-outline-secondary remove" type="button">-</button>
                            </div>
                        </div>`;

            // Add Qualitications
            $('#btn_add_qualifications').on('click', function(e) {
                var row_qualification = $(tmp).clone();
                row_qualification.find('input').attr('name', 'qualification[]');
                row_qualification.appendTo('#profession .wrap-qualifications');
            });

            // Add Achievements
            $('#btn_add_achievements').on('click', function(e) {
                var row_achievement = $(tmp).clone();
                row_achievement.find('input').attr('name', 'achievement[]');
                row_achievement.appendTo('#profession .wrap-achievements');
            });

            $('#profession').on('click', 'button.remove', function(e) {
                $(this).closest('.row').remove();
            });

            $('form').submit(function (e) {
                e.preventDefault();

                if (!checkValidForm($(this))) {
                    return false;
                }

                $(this).ajaxSubmit({
                    success: function (res) {
                        if(res.success) {
                            swal("Success!", res.message, "success");
                            if(res.action != undefined && res.action == 'child') {
                                location.reload();
                            }

                            if(res.action != undefined && res.action == 'kyc') {
                                console.log(res);
                                $('#kyc_frm_wrap').addClass('d-none');
                                $('#kyc_alert_wrap').removeClass('d-none');
                                $('#kyc_alert_content').html(`<strong>Under Review: </strong> Your KYC submittion is under review.`);
                            }
                        } else {
                            swal("Error!", res.message, "error");
                        }
                    }
                });
            });

            var pattern = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/;

            $('#new_pwd').on('keyup', function(e) {
                var rlt = checkPassword($(this).val());
                if(!rlt) {
                    if(!$(this).hasClass('is-invalid')) {
                        $(this).addClass('is-invalid');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                }
            });

            $('input[name="password"]').on('keyup', function(e) {
                var rlt = checkPassword($(this).val());
                if(!rlt) {
                    if(!$(this).hasClass('is-invalid')) {
                        $(this).addClass('is-invalid');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).addClass('is-valid');
                }
            });

            $('.custom-checkbox-toggle').on('click', 'input[type="checkbox"]', function() {

                var id = $(this).attr('id');

                if ($(this).prop('checked')) {
                    $(this).val('1');
                    $('div[for="' + id + '"').removeClass('d-none');
                } else {
                    $(this).val('0');
                    $('div[for="' + id + '"').addClass('d-none');
                }

            });

            $('#btn_mdl_save').on('click', (e) => {
                let step = $(e.target).attr('data-step');

                if (step == '0') {
                    let new_email = $('#new_email').val();
                    let new_email_confirmation = $('#new_email_confirmation').val();

                    if (new_email == '') {
                        $('#dv_alert_1').removeClass('d-none');
                        $('#dv_alert_1 .media_body').text('New Email is required');
                        return false;
                    };

                    if (new_email_confirmation == '') {
                        $('#dv_alert_1').removeClass('d-none');
                        $('#dv_alert_1 .media_body').text('New Email Confirmation is required');
                        return false;
                    };

                    if (new_email != new_email_confirmation) {
                        $('#dv_alert_1').removeClass('d-none');
                        $('#dv_alert_1 .media_body').text('New Email and email confirmation is not same. Pleaes check');
                        return false;
                    };

                    // Send verification code to old email
                    btnLoading($(e.target), true);
                    $.ajax({
                        url: `{{ route('admin.user.verifycode.send') }}`,
                        method: 'POST',
                        data: {
                            email: $('#current_email').val()
                        },
                        success: (res) => {

                            btnLoading($(e.target), false);

                            if (res.success) {
                                $('#dv_verification').removeClass('d-none');
                                $(e.target).attr('data-step', 1);
                                $(e.target).text('Verify');
                            }
                        },
                        error: (err) => {
                            btnLoading($(e.target), false);
                            console.log(res);
                        }
                    });
                }

                if (step == '1') {
                    // Send verification function to new email
                    btnLoading($(e.target), true);
                    $.ajax({
                        url: `{{ route('admin.user.email.change') }}`,
                        method: 'POST',
                        data: {
                            email: $('#current_email').val(),
                            new_email: $('#new_email').val(),
                            verify_code: $('#verify_code').val()
                        },
                        success: (res) => {
                            btnLoading($(e.target), false);
                            console.log(res);
                            if (res.success) {
                                $('#dv_alert_2').removeClass('d-none');
                                $('#dv_alert_2').removeClass('alert-accent');
                                $('#dv_alert_2').addClass('alert-info');
                                $('#dv_alert_2 .media-body').text('Verification email has been sent to your new email address. Click on the verification link in the email to verify it.');
                                $('#btn_mdl_save').addClass('d-none');
                                $('#btn_mdl_close').removeClass('d-none');
                            } else {
                                $('#dv_alert_2').removeClass('d-none');
                                $('#dv_alert_2').removeClass('alert-info');
                                $('#dv_alert_2').addClass('alert-accent');
                                $('#dv_alert_2 .media-body').text(res.message);
                            }
                        },
                        error: (err) => {
                            btnLoading($(e.target), false);
                            console.log(err);
                        }
                    })
                    
                }

                if (step == 2) {

                }
            });

            function checkPassword(password) {
                if (pattern.test(password)) {
                    return true;
                } else {
                    return false;
                }
            }
            
        });
    </script>

@endpush

@endsection