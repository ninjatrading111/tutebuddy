@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.payment.orders.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                            @lang('labels.backend.dashboard.title')
                            </a>
                        </li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.payment.orders.title')
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.payment.orders.payment_history')</div>
        </div>

        <div class="card table-responsive">
            <table id="tbl_orders" class="table table-flush table-nowrap" data-page-length='50'>
                <thead>
                    <tr>
                        <th style="width: 18px;" class="pr-0"></th>
                        <th>@lang('labels.backend.table.no')</th>
                        <th>@lang('labels.backend.payment.table.order_id')</th>
                        <th>@lang('labels.backend.payment.table.billed_name')</th>
                        <th>@lang('labels.backend.table.date')</th>
                        <th class="">@lang('labels.backend.payment.table.amount')</th>
                        <th>@lang('labels.backend.table.status')</th>
                        <th class="text-right"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('admin.orders.detail', $order->id) }}" class="text-underline">{{ $order->uuid }}</a>
                        </td>
                        <td>{{ !empty($order->userFor) ? $order->userFor->name : $order->user->name }}</td>
                        <td>{{ \Carbon\Carbon::parse(timezone()->convertToLocal(\Carbon\Carbon::parse($order->created_at)))->format('M d Y h:i A') }}</td>
                        <td class="">{{ getCurrency(config('app.currency'))['symbol'] . $order->amount }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                @if(!empty($order->refund) && $order->refund->status == 0)
                                    <small class="js-lists-values-status text-50 mb-4pt text-capitalize">Refund Requested</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                @elseif(!empty($order->refund) && $order->refund->status == 1)
                                    <small class="js-lists-values-status text-50 mb-4pt text-capitalize">Refunded</small>
                                    <span class="indicator-line rounded bg-accent"></span>
                                @else
                                    <small class="js-lists-values-status text-50 mb-4pt text-capitalize">{{ $order->status }}</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                @endif
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="d-inline-flex align-items-center">
                                <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-sm btn-outline-secondary mr-16pt">
                                    View Detail<i class="icon--right material-icons">keyboard_arrow_right</i></a>
                                <a href="{{ route('admin.orders.invoice', $order->id) }}" class="btn btn-sm btn-outline-secondary">
                                    Invoice<i class="icon--right material-icons">file_download</i></a>
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if(count($orders) < 1)
                    <tr>
                        <td colspan="6" class="text-center">No orders</td>
                    </tr>
                    @endif

                </tbody>
            </table>
            
            <div class="card-footer p-8pt">
                @if($orders->hasPages())
                {{ $orders->links('layouts.parts.page') }}
                @else
                <ul class="pagination justify-content-start pagination-xsm m-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true" class="material-icons">chevron_left</span>
                            <span>@lang('labels.backend.general.prev')</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Page 1">
                            <span>1</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span>@lang('labels.backend.general.next')</span>
                            <span aria-hidden="true" class="material-icons">chevron_right</span>
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection