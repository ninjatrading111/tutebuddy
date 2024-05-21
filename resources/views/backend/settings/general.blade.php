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

                        <li class="breadcrumb-item active">
                            Settings
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <form action="{{ route('admin.settings.general.save') }}" method="post" enctype="multipart/form-data"
                    id="frm_setting">
                    @csrf

                    <div class="card-header p-0 nav">
                        <div class="row no-gutters" role="tablist">
                            {{-- <div class="col-auto">
                                <a href="#general" data-toggle="tab" role="tab" aria-selected="true"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start active">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">General</strong>
                                    </span>
                                </a>
                            </div> --}}
                            <div class="col-auto border-left border-right">
                                <a href="#term" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Term Of Sevice</strong>
                                    </span>
                                </a>
                            </div>
                            <div class="col-auto border-left border-right">
                                <a href="#commission" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Commission Rate</strong>
                                    </span>
                                </a>
                            </div>
                            <div class="col-auto border-left border-right">
                                <a href="#payment" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Payment Gateway</strong>
                                    </span>
                                </a>
                            </div>
                            <div class="col-auto border-left">
                                <a href="#logos" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Cusrromize Feature</strong>
                                    </span>
                                </a>
                            </div>
                            {{-- <div class="col-auto border-left">
                                <a href="#mail" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Email Configuration</strong>
                                    </span>
                                </a>
                            </div>
                            <div class="col-auto border-left border-right">
                                <a href="#bbb" data-toggle="tab" role="tab" aria-selected="false"
                                    class="dashboard-area-tabs__tab card-body d-flex flex-row align-items-center justify-content-start">
                                    <span class="flex d-flex flex-column">
                                        <strong class="card-title">Video Conference Configuration</strong>
                                    </span>
                                </a>
                            </div> --}}
                        </div>
                    </div>

                    <div class="card-body tab-content">

<!-- Tab Content for General Setting -->
                        <div id="general" class="tab-pane p-4 fade text-70 active show">

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">App Name: </label>
                                    <input type="text" name="app__name" class="form-control col-lg-8"
                                        value="{{ config('app.name') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">App URL: </label>
                                    <input type="text" name="app__url" class="form-control col-lg-8"
                                        value="{{ config('app.url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Video Conference APP URL: </label>
                                    <input type="text" name="liveapp__url" class="form-control col-lg-8"
                                        value="{{ config('liveapp.url') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Video Conference Key: </label>
                                    <input type="text" name="liveapp__key" class="form-control col-lg-8"
                                        value="{{ config('liveapp.key') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Google Analytics ID: </label>
                                    <input type="text" name="google_analytics_id" class="form-control col-lg-8"
                                        value="{{ config('google_analytics_id') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Contact Email: </label>
                                    <input type="text" name="site_contact_email" class="form-control col-lg-8"
                                        value="{{ config('site_contact_email') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="captcha_status">Captcha Status</label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('access.captcha.registration')==1) checked=""
                                            @endif id="captcha_status" name="access__captcha__registration"
                                            class="custom-control-input" value="{{ config('access.captcha.registration') }} ">
                                        <label class="custom-control-label" for="captcha_status">&nbsp;</label>
                                    </div>
                                    <label class="form-label mb-0" for="captcha_status">Yes</label>
                                </div>
                            </div>

                            <div class="wrap @if(config('access.captcha.registration')==0) d-none @endif"
                                for="captcha_status">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label class="form-label col-lg-3 content-left">Captcha Site Key:</label>
                                        <input class="form-control col-lg-8" type="text" name="captcha__key"
                                            placeholder="Captcha Key" value="{{ config('captcha.key') }}">
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label class="form-label col-lg-3 content-left">Captcha Secret:</label>
                                        <input class="form-control col-lg-8" type="text" name="captcha__secret"
                                            placeholder="Captcha Secret" value="{{ config('captcha.secret') }}">
                                    </div>
                                </div>
                            </div>
                        </div>

<!-- Tab for Term of Service -->
                        <div id="term" class="tab-pane p-4 fade text-70">

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Currency: </label>
                                    <select class="form-control col-lg-8" id="app__currency" name="app__currency">
                                        @foreach(config('currencies') as $currency)
                                        <option @if(config('app.currency')==$currency['short_code']) selected @endif
                                            value="{{$currency['short_code']}}">
                                            {{$currency['symbol'].' - '.$currency['name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Account Fee (%):</label>
                                    <input type="number" class="form-control col-lg-8" id="account__fee" name="account__fee" value="{{ config('account.fee') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Available Withdraw days:</label>
                                    <input type="number" class="form-control col-lg-8" id="withdraw__days" name="withdraw__days" value="{{ config('withdraw.days') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="services__razorpay__active">RazorPay: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('services.razorpay.active')==1) checked="" @endif id="services__razorpay__active"
                                            name="services__razorpay__active" class="custom-control-input" value="{{ config('services.razorpay.active') }}">
                                        <label class="custom-control-label" for="services__razorpay__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="services__razorpay__active">Yes
                                        <small class="text-muted">&nbsp; (Enables payments in site with Debit / Credit
                                            Cards)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="wrap @if(config('services.razorpay.active')==0) d-none @endif"
                                for="services__razorpay__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">API Key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpay__key"
                                            id="services__razorpay__key" value="{{ config('services.razorpay.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="input-group controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">API Secret: </label>
                                        <input class="form-control col-lg-8" type="password" name="services__razorpay__secret"
                                            id="services__razorpay__secret" value="{{ config('services.razorpay.secret') }}">
                                        <div class="input-group-append show_secret" data-id="services__razorpay__secret" style="cursor: pointer;">
                                            <div class="input-group-text">
                                                <span class="fa fa-eye icon-16pt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX Account Number: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpayX__number"
                                            id="services__razorpayX__number" value="{{ config('services.razorpayX.number') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX API key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpayX__key"
                                            id="services__razorpayX__key" value="{{ config('services.razorpayX.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX API Secret: </label>
                                        <input class="form-control col-lg-8" type="password" name="services__razorpayX__secret"
                                            id="services__razorpayX__secret" value="{{ config('services.razorpayX.secret') }}">
                                        <div class="input-group-append show_secret" data-id="services__razorpayX__secret" style="cursor: pointer;">
                                            <div class="input-group-text">
                                                <span class="fa fa-eye icon-16pt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="services__stripe__active">Stripe: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('services.stripe.active')==1) checked="" @endif id="services__stripe__active"
                                            name="services__stripe__active" class="custom-control-input" value="{{ config('services.stripe.active') }}">
                                        <label class="custom-control-label" for="services__stripe__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="services__stripe__active">Yes
                                        <small class="text-muted">&nbsp; (Enables payments in site with Debit / Credit
                                            Cards)</small>
                                    </label>

                                </div>
                            </div>

                            <div class="wrap @if(config('services.stripe.active')==0) d-none @endif"
                                for="services__stripe__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-2 content-left">API Key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__stripe__key"
                                            id="services__stripe__key" value="{{ config('services.stripe.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-2 content-left">API Secret: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__stripe__secret"
                                            id="services__stripe__secret" value="{{ config('services.stripe.secret') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="paypal__active">PayPal: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('paypal.active')==1) checked="" @endif id="paypal__active" name="paypal__active"
                                            class="custom-control-input" value="{{ config('paypal.active') }}">
                                        <label class="custom-control-label" for="paypal__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="paypal__active">Yes
                                        <small class="text-muted">&nbsp; (Redirects to paypal for payment)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="wrap @if(config('paypal.active')==0) d-none @endif" for="paypal__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Mode: </label>
                                        <select class="form-control col-lg-8" id="paypal_settings_mode"
                                            name="paypal__settings__mode">
                                            <option value="sandbox" selected="selected">Sandbox</option>
                                            <option value="live">Live</option>
                                        </select>
                                        <small class="text-muted offset-2 col-lg-8"><b>Sandbox</b>= Will be used for
                                            testing
                                            payments with PayPal Test Credentials. Account with USD only can make
                                            payments with PayPal for now. This options will redirect to test PayPal
                                            payment with Sandbox User Credentials. It will be used for dummy
                                            transactions only.<br />
                                            <b>Live</b> = Will be used with you Live PayPal credentials to make actual
                                            transaction with normal users with PayPal account.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Client ID: </label>
                                        <input class="form-control" type="text" name="paypal__client_id"
                                            id="paypal__client_id" value="test">
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Secret: </label>
                                        <input class="form-control" type="text" name="paypal__secret"
                                            id="paypal__secret" value="test">
                                    </div>
                                </div>
                            </div>

                        </div>
<!-- Tab for Commission Rate -->
                        <div id="commission" class="tab-pane p-4 fade text-70">

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Currency: </label>
                                    <select class="form-control col-lg-8" id="app__currency" name="app__currency">
                                        @foreach(config('currencies') as $currency)
                                        <option @if(config('app.currency')==$currency['short_code']) selected @endif
                                            value="{{$currency['short_code']}}">
                                            {{$currency['symbol'].' - '.$currency['name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Account Fee (%):</label>
                                    <input type="number" class="form-control col-lg-8" id="account__fee" name="account__fee" value="{{ config('account.fee') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Available Withdraw days:</label>
                                    <input type="number" class="form-control col-lg-8" id="withdraw__days" name="withdraw__days" value="{{ config('withdraw.days') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="services__razorpay__active">RazorPay: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('services.razorpay.active')==1) checked="" @endif id="services__razorpay__active"
                                            name="services__razorpay__active" class="custom-control-input" value="{{ config('services.razorpay.active') }}">
                                        <label class="custom-control-label" for="services__razorpay__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="services__razorpay__active">Yes
                                        <small class="text-muted">&nbsp; (Enables payments in site with Debit / Credit
                                            Cards)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="wrap @if(config('services.razorpay.active')==0) d-none @endif"
                                for="services__razorpay__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">API Key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpay__key"
                                            id="services__razorpay__key" value="{{ config('services.razorpay.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="input-group controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">API Secret: </label>
                                        <input class="form-control col-lg-8" type="password" name="services__razorpay__secret"
                                            id="services__razorpay__secret" value="{{ config('services.razorpay.secret') }}">
                                        <div class="input-group-append show_secret" data-id="services__razorpay__secret" style="cursor: pointer;">
                                            <div class="input-group-text">
                                                <span class="fa fa-eye icon-16pt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX Account Number: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpayX__number"
                                            id="services__razorpayX__number" value="{{ config('services.razorpayX.number') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX API key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpayX__key"
                                            id="services__razorpayX__key" value="{{ config('services.razorpayX.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX API Secret: </label>
                                        <input class="form-control col-lg-8" type="password" name="services__razorpayX__secret"
                                            id="services__razorpayX__secret" value="{{ config('services.razorpayX.secret') }}">
                                        <div class="input-group-append show_secret" data-id="services__razorpayX__secret" style="cursor: pointer;">
                                            <div class="input-group-text">
                                                <span class="fa fa-eye icon-16pt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="services__stripe__active">Stripe: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('services.stripe.active')==1) checked="" @endif id="services__stripe__active"
                                            name="services__stripe__active" class="custom-control-input" value="{{ config('services.stripe.active') }}">
                                        <label class="custom-control-label" for="services__stripe__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="services__stripe__active">Yes
                                        <small class="text-muted">&nbsp; (Enables payments in site with Debit / Credit
                                            Cards)</small>
                                    </label>

                                </div>
                            </div>

                            <div class="wrap @if(config('services.stripe.active')==0) d-none @endif"
                                for="services__stripe__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-2 content-left">API Key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__stripe__key"
                                            id="services__stripe__key" value="{{ config('services.stripe.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-2 content-left">API Secret: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__stripe__secret"
                                            id="services__stripe__secret" value="{{ config('services.stripe.secret') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="paypal__active">PayPal: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('paypal.active')==1) checked="" @endif id="paypal__active" name="paypal__active"
                                            class="custom-control-input" value="{{ config('paypal.active') }}">
                                        <label class="custom-control-label" for="paypal__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="paypal__active">Yes
                                        <small class="text-muted">&nbsp; (Redirects to paypal for payment)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="wrap @if(config('paypal.active')==0) d-none @endif" for="paypal__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Mode: </label>
                                        <select class="form-control col-lg-8" id="paypal_settings_mode"
                                            name="paypal__settings__mode">
                                            <option value="sandbox" selected="selected">Sandbox</option>
                                            <option value="live">Live</option>
                                        </select>
                                        <small class="text-muted offset-2 col-lg-8"><b>Sandbox</b>= Will be used for
                                            testing
                                            payments with PayPal Test Credentials. Account with USD only can make
                                            payments with PayPal for now. This options will redirect to test PayPal
                                            payment with Sandbox User Credentials. It will be used for dummy
                                            transactions only.<br />
                                            <b>Live</b> = Will be used with you Live PayPal credentials to make actual
                                            transaction with normal users with PayPal account.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Client ID: </label>
                                        <input class="form-control" type="text" name="paypal__client_id"
                                            id="paypal__client_id" value="test">
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Secret: </label>
                                        <input class="form-control" type="text" name="paypal__secret"
                                            id="paypal__secret" value="test">
                                    </div>
                                </div>
                            </div>

                        </div>
<!-- Tab for Payment -->
                        <div id="payment" class="tab-pane p-4 fade text-70">

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Currency: </label>
                                    <select class="form-control col-lg-8" id="app__currency" name="app__currency">
                                        @foreach(config('currencies') as $currency)
                                        <option @if(config('app.currency')==$currency['short_code']) selected @endif
                                            value="{{$currency['short_code']}}">
                                            {{$currency['symbol'].' - '.$currency['name']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Account Fee (%):</label>
                                    <input type="number" class="form-control col-lg-8" id="account__fee" name="account__fee" value="{{ config('account.fee') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Available Withdraw days:</label>
                                    <input type="number" class="form-control col-lg-8" id="withdraw__days" name="withdraw__days" value="{{ config('withdraw.days') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="services__razorpay__active">RazorPay: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('services.razorpay.active')==1) checked="" @endif id="services__razorpay__active"
                                            name="services__razorpay__active" class="custom-control-input" value="{{ config('services.razorpay.active') }}">
                                        <label class="custom-control-label" for="services__razorpay__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="services__razorpay__active">Yes
                                        <small class="text-muted">&nbsp; (Enables payments in site with Debit / Credit
                                            Cards)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="wrap @if(config('services.razorpay.active')==0) d-none @endif"
                                for="services__razorpay__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">API Key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpay__key"
                                            id="services__razorpay__key" value="{{ config('services.razorpay.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="input-group controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">API Secret: </label>
                                        <input class="form-control col-lg-8" type="password" name="services__razorpay__secret"
                                            id="services__razorpay__secret" value="{{ config('services.razorpay.secret') }}">
                                        <div class="input-group-append show_secret" data-id="services__razorpay__secret" style="cursor: pointer;">
                                            <div class="input-group-text">
                                                <span class="fa fa-eye icon-16pt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX Account Number: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpayX__number"
                                            id="services__razorpayX__number" value="{{ config('services.razorpayX.number') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX API key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__razorpayX__key"
                                            id="services__razorpayX__key" value="{{ config('services.razorpayX.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-3 content-left">RazorpayX API Secret: </label>
                                        <input class="form-control col-lg-8" type="password" name="services__razorpayX__secret"
                                            id="services__razorpayX__secret" value="{{ config('services.razorpayX.secret') }}">
                                        <div class="input-group-append show_secret" data-id="services__razorpayX__secret" style="cursor: pointer;">
                                            <div class="input-group-text">
                                                <span class="fa fa-eye icon-16pt"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="services__stripe__active">Stripe: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('services.stripe.active')==1) checked="" @endif id="services__stripe__active"
                                            name="services__stripe__active" class="custom-control-input" value="{{ config('services.stripe.active') }}">
                                        <label class="custom-control-label" for="services__stripe__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="services__stripe__active">Yes
                                        <small class="text-muted">&nbsp; (Enables payments in site with Debit / Credit
                                            Cards)</small>
                                    </label>

                                </div>
                            </div>

                            <div class="wrap @if(config('services.stripe.active')==0) d-none @endif"
                                for="services__stripe__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-2 content-left">API Key: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__stripe__key"
                                            id="services__stripe__key" value="{{ config('services.stripe.key') }}">
                                    </div>
                                </div>

                                <div class="form-group offset-3">
                                    <div class="controls form-inline ">
                                        <label for="" class="form-label col-lg-2 content-left">API Secret: </label>
                                        <input class="form-control col-lg-8" type="text" name="services__stripe__secret"
                                            id="services__stripe__secret" value="{{ config('services.stripe.secret') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="flex form-inline" style="max-width: 100%">
                                    <label class="form-label col-lg-3" for="paypal__active">PayPal: </label>
                                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                                        <input type="checkbox" @if(config('paypal.active')==1) checked="" @endif id="paypal__active" name="paypal__active"
                                            class="custom-control-input" value="{{ config('paypal.active') }}">
                                        <label class="custom-control-label" for="paypal__active">Yes</label>
                                    </div>
                                    <label class="form-label mb-0" for="paypal__active">Yes
                                        <small class="text-muted">&nbsp; (Redirects to paypal for payment)</small>
                                    </label>
                                </div>
                            </div>

                            <div class="wrap @if(config('paypal.active')==0) d-none @endif" for="paypal__active">
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Mode: </label>
                                        <select class="form-control col-lg-8" id="paypal_settings_mode"
                                            name="paypal__settings__mode">
                                            <option value="sandbox" selected="selected">Sandbox</option>
                                            <option value="live">Live</option>
                                        </select>
                                        <small class="text-muted offset-2 col-lg-8"><b>Sandbox</b>= Will be used for
                                            testing
                                            payments with PayPal Test Credentials. Account with USD only can make
                                            payments with PayPal for now. This options will redirect to test PayPal
                                            payment with Sandbox User Credentials. It will be used for dummy
                                            transactions only.<br />
                                            <b>Live</b> = Will be used with you Live PayPal credentials to make actual
                                            transaction with normal users with PayPal account.
                                        </small>
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Client ID: </label>
                                        <input class="form-control" type="text" name="paypal__client_id"
                                            id="paypal__client_id" value="test">
                                    </div>
                                </div>
                                <div class="form-group offset-3">
                                    <div class="controls form-inline">
                                        <label for="" class="form-label col-lg-2 content-left">Secret: </label>
                                        <input class="form-control" type="text" name="paypal__secret"
                                            id="paypal__secret" value="test">
                                    </div>
                                </div>
                            </div>

                        </div>

<!-- Tab content for Logo and Favicon -->
                        <div id="logos" class="tab-pane p-4 fade text-70">

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

<!-- Tab for Mail configuration -->
                        <div id="mail" class="tab-pane p-4 fade text-70">

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label text-left col-lg-3">Mail From Name: </label>
                                    <input type="text" name="mail__from__name" class="form-control col-lg-8"
                                        value="{{ config('mail.from.name') }}">
                                    <small class="offset-3 col-lg-8 text-muted">This will be display name for your sent
                                        email.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail From Address: </label>
                                    <input type="text" name="mail__from__address" class="form-control col-lg-8"
                                        value="{{ config('mail.from.address') }}">
                                    <small class="offset-3 col-lg-8 text-muted">This email will be used for "Contact
                                        Form"
                                        correspondence.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail Driver: </label>
                                    <input type="text" name="mail__driver" class="form-control col-lg-8"
                                        value="{{ config('mail.driver') }}">
                                    <small class="offset-3 col-lg-8 text-muted">You can select any driver you want for
                                        your
                                        Mail setup. Ex. SMTP, Mailgun, Mandrill, SparkPost, Amazon SES etc.
                                        Add single driver only.</small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail Host: </label>
                                    <input type="text" name="mail__host" class="form-control col-lg-8"
                                        value="{{ config('mail.host') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail Port: </label>
                                    <input type="text" name="mail__port" class="form-control col-lg-8"
                                        value="{{ config('mail.port') }}">
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail User Name: </label>
                                    <input type="text" name="mail__username" class="form-control col-lg-8"
                                        value="{{ config('mail.username') }}">
                                    <small class="offset-3 col-lg-8 text-muted">
                                        Add your email id you want to configure for sending emails
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail Password: </label>
                                    <input type="password" name="mail__password" class="form-control col-lg-8"
                                        value="{{ config('mail.password') }}">
                                    <small class="offset-3 col-lg-8 text-muted">
                                        Add your email password you want to configure for sending emails
                                    </small>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-left">Mail Encryption: </label>
                                    <select class="form-control col-lg-8" name="mail__encryption">
                                        <option value="tls" @if(config('mail.encryption') == 'tls') selected="selected" @endif>tls</option>
                                        <option value="ssl" @if(config('mail.encryption') == 'ssl') selected="selected" @endif>ssl</option>
                                    </select>
                                    <small class="offset-3 col-lg-8 text-muted">
                                        Use tls if your site uses HTTP protocol and ssl if you site uses HTTPS protocol
                                    </small>
                                </div>
                            </div>
                        </div>



<!-- Tab for BBB -->
                        <div id="bbb" class="tab-pane p-4 fade text-70">
                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right">How to student join the Metting?: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_join_auto" name="bbb__join_method" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.join_method') == 1 ) checked="" @endif>
                                            <label for="live_join_auto" class="custom-control-label">Join Automatically</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_join_wait" name="bbb__join_method" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.join_method') == 0 ) checked="" @endif>
                                            <label for="live_join_wait" class="custom-control-label">Wait for admin to let them in</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Mic on Join: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_mic_on" name="bbb__mic_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.mic_status') == 1 ) checked="" @endif>
                                            <label for="live_mic_on" class="custom-control-label">On</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_mic_off" name="bbb__mic_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.mic_status') == 0 ) checked="" @endif>
                                            <label for="live_mic_off" class="custom-control-label">Off</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Speaker on Join: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_speaker_on" name="bbb__speaker_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.speaker_status') == 1 ) checked="" @endif>
                                            <label for="live_speaker_on" class="custom-control-label">On</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_speaker_off" name="bbb__speaker_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.speaker_status') == 0 ) checked="" @endif>
                                            <label for="live_speaker_off" class="custom-control-label">Off</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Camera on Join: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_camera_on" name="bbb__camera_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.camera_status') == 1 ) checked="" @endif>
                                            <label for="live_camera_on" class="custom-control-label">On</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_camera_off" name="bbb__camera_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.camera_status') == 0 ) checked="" @endif>
                                            <label for="live_camera_off" class="custom-control-label">Off</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Enable Screensharing for Students: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_screenshare_yes" name="bbb__screenshare_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.screenshare_status') == 1 ) checked="" @endif>
                                            <label for="live_screenshare_yes" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_screenshare_no" name="bbb__screenshare_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.screenshare_status') == 0 ) checked="" @endif>
                                            <label for="live_screenshare_no" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Enable File Upload for Students: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_upload_yes" name="bbb__upload_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.upload_status') == 1 ) checked="" @endif>
                                            <label for="live_upload_yes" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_upload_no" name="bbb__upload_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.upload_status') == 0 ) checked="" @endif>
                                            <label for="live_upload_no" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Allow Private Chat: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_chat_yes" name="bbb__chat_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.chat_status') == 1 ) checked="" @endif>
                                            <label for="live_chat_yes" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_chat_no" name="bbb__chat_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.chat_status') == 0 ) checked="" @endif>
                                            <label for="live_chat_no" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Default Presenter: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_admin_presenter" name="bbb__presenter" type="radio" class="custom-control-input" value="admin"
                                            @if(config('bbb.presenter') == 'admin' ) checked="" @endif>
                                            <label for="live_admin_presenter" class="custom-control-label">admin</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_student_presenter" name="bbb__presenter" type="radio" class="custom-control-input" value="student"
                                            @if(config('bbb.chat_status') == 'student' ) checked="" @endif>
                                            <label for="live_student_presenter" class="custom-control-label">Student</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_both_presenter" name="bbb__presenter" type="radio" class="custom-control-input" value="both"
                                            @if(config('bbb.chat_status') == 'both' ) checked="" @endif>
                                            <label for="live_both_presenter" class="custom-control-label">Both</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Enable Whiteboard: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_whiteboard_yes" name="bbb__whiteboard_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.whiteboard_status') == 1 ) checked="" @endif>
                                            <label for="live_whiteboard_yes" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_whiteboard_no" name="bbb__whiteboard_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.whiteboard_status') == 0 ) checked="" @endif>
                                            <label for="live_whiteboard_no" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="controls form-inline">
                                    <label for="" class="form-label col-lg-3 text-right d-inline-block">Allow Students to join and Moderators: </label>
                                    <div class="custom-controls-stacked form-inline">
                                        <div class="custom-control custom-radio">
                                            <input id="live_moderator_allow" name="bbb__moderator_status" type="radio" class="custom-control-input" value="1"
                                            @if(config('bbb.moderator_status') == 1 ) checked="" @endif>
                                            <label for="live_moderator_allow" class="custom-control-label">Yes</label>
                                        </div>
                                        <div class="custom-control custom-radio ml-8pt">
                                            <input id="live_moderator_disallow" name="bbb__moderator_status" type="radio" class="custom-control-input" value="0"
                                            @if(config('bbb.moderator_status') == 0 ) checked="" @endif>
                                            <label for="live_moderator_disallow" class="custom-control-label">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer text-right">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

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
</script>

@endpush

@endsection