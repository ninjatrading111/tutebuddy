@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.contacts.customer_information')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item">
                            <a href="">@lang('labels.backend.contacts.title')</a>
                        </li>
                        <li class="breadcrumb-item active">
                            @lang('labels.backend.contacts.edit')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    {!! Form::open(['method' => 'DELETE','route' => ['admin.contacts.destroy',
                    $contact->id],'style'=>'display:inline']) !!}
                    {!! Form::submit('Delete', ['class' => 'btn btn-outline-secondary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>

        </div>
    </div>

    <div class="page-section container page__container">

        <div class="card card-body">

            {!! Form::model($contact, ['method' => 'PATCH', 'files' => true, 'route' => ['admin.contacts.update',
            $contact->id]]) !!}
                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.contacts.full_name') *:</label>
                    {!! Form::text('name', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.contacts.company') *:</label>
                    {!! Form::text('company', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.contacts.company_email') *:</label>
                    {!! Form::email('company_email', null, array('class' => 'form-control')) !!}
                </div>
                <div class="row">
                    <div class="form-group col-8">
                        <label class="form-label">@lang('labels.backend.contacts.business_phone') *:</label>
                        {!! Form::text('business_phone', null, array('class' => 'form-control')) !!}
                    </div>
                    <div class="form-group col-4">
                        <label class="form-label">@lang('labels.backend.contacts.ext'):</label>
                        {!! Form::text('ext', null, array('class' => 'form-control')) !!}
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.contacts.mobile_number') *:</label>
                    {!! Form::text('mobile_phone', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group">
                    <label class="form-label">@lang('labels.backend.contacts.best_time')</label>
                    {!! Form::text('meet_time', null, array('class' => 'form-control')) !!}
                </div>
                <div class="form-group ">
                    <label class="form-label">@lang('labels.backend.contacts.message')*:</label>
                    {!! Form::textarea('message', null, array('class' => 'form-control', 'rows' => '4')) !!}
                </div>
                <label class="form-label">@lang('labels.backend.contacts.contact_method_notice')</label>
                <div class="form-group form-inline mb-24pt">
                    <div class="custom-control custom-radio">
                        <input id="by_email" name="contact_type" type="radio" value="1" class="custom-control-input" @if($contact->contact_type == 1) checked @endif>
                        <label for="by_email" class="custom-control-label">@lang('labels.backend.contacts.by_email')</label>
                    </div>
                    <div class="custom-control custom-radio ml-3">
                        <input id="by_mobile_phone" name="contact_type" type="radio" value="2" class="custom-control-input" @if($contact->contact_type == 2) checked @endif>
                        <label for="by_mobile_phone" class="custom-control-label">@lang('labels.backend.contacts.by_mobile_phone')</label>
                    </div>
                    <div class="custom-control custom-radio ml-3">
                        <input id="by_business_phone" name="contact_type" type="radio" value="3" class="custom-control-input" @if($contact->contact_type == 3) checked @endif>
                        <label for="by_business_phone" class="custom-control-label">@lang('labels.backend.contacts.by_business_phone')</label>
                    </div>
                </div>
                <button class="btn btn-primary">Submit @lang('labels.backend.buttons.submit')</button>
            {!! Form::close() !!}

        </div>

    </div>
</div>

@endsection