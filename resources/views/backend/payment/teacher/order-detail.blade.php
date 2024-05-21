@extends('layouts.app')

@section('content')

{{-- ?php
    $completed = false;
    if(\Carbon\Carbon::parse($orderItem->course->end_date)->diffInDays(\Carbon\Carbon::now()) > 7 &&
    $orderItem->course->end_date < \Carbon\Carbon::now()->format('Y-m-d')) {
        $completed = true;
    }
?> --}}

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt pb-5">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.payment.order_detail.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.payment.order_detail.title')
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <!-- Actions -->
                    {{-- @if(!$completed)
                    <a href="" class="btn btn-sm btn-accent">@lang('labels.backend.payment.order_detail.refund')</a>
                    @endif --}}
                    <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div  class="page-separator">
        <div class="page-separator__text">@lang('labels.backend.payment.order_detail.payment_detail')</div>
    </div>
    <div class="container page__container page-section">
        <div class="row mb-32pt">
            <div class="col-6">

                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.payment.order_detail.order_id'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>1985564</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Payment Status: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong class="text-capitalize">
                                    {{-- {{ ($orderItem->order->status == 'captured') ? 'Successful' : $order->status }} --}}
                                    Successful
                                </strong>
                            </div>
                        </div>
                    </div>

                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">
                            @lang('labels.backend.payment.order_detail.amount'): </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong class="text-capitalize">{{ getCurrency(config('app.currency'))['symbol'] . 58.35 }}</strong>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <div class="col-6">
                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Order Title: </label>
                            <div class="d-flex align-items-center" style="white-space: nowrap;">
                                <div class="flex ml-4pt">
                                    <div class="d-flex flex-column">
                                        <p class="mb-0"><strong>Order title</strong></p>
                                        <small class="text-50">Physics</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Payment Date: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong>
                                    {{-- {{ timezone()->convertFromTimezone($orderItem->created_at, 'UTC', 'M d Y h:i A') }} --}}
                                    2023-05-09
                                </strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label for="payment_cc" id="label-type" class="col-md-4 col-form-label form-label">Commission: </label>
                            <div role="group" aria-labelledby="label-type" class="col-md-8">
                                <strong class="text-capitalize">{{ getCurrency(config('app.currency'))['symbol'] . 0.35}}</strong>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

</div>

@endsection