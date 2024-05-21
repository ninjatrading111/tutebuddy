@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Refunds</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Refunds
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-order js-lists-values-date">
                <table id="tbl_sales" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-order">Order</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">Date</a>
                            </th>
                            <th> Customer </th>
                            <th> Amount </th>
                            <th> Status </th>
                            <th> Action </th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle">
                        @foreach($refunds as $refund)

                        @php
                            if(empty($refund->order)) {
                                continue;
                            }
                        @endphp
                        <tr>
                            <td class="pr-0"></td>

                            <td>
                                <strong>{{ $refund->order->uuid }}</strong>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                <small class="js-lists-values-date"><strong>{{ \Carbon\Carbon::parse($refund->created_at)->format('M d Y') }}</strong></small>
                                    <small class="text-50">{{ \Carbon\Carbon::parse($refund->created_at)->diffForHumans() }}</small>
                                </div>
                            </td>

                            <td>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">

                                        <span class="avatar-title rounded-circle">{{ mb_substr($refund->user->name, 0, 2) }}</span>

                                    </div>
                                    <div class="media-body">

                                        <div class="d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-name">{{ $refund->user->name }}</strong></p>
                                            <small class="js-lists-values-email text-50">{{ $refund->user->email }}</small>
                                        </div>

                                    </div>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-budget"><strong>{{ getCurrency(config('app.currency'))['symbol'] . $refund->order->amount }}</strong></small>
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    @if($refund->status == 1)
                                    <small class="js-lists-values-status text-50 mb-4pt">Processed</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                    @else
                                    <small class="js-lists-values-status text-50 mb-4pt">Pending</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <a href="{{ route('admin.refund.detail', $refund->id) }}" class="btn btn-accent btn-sm">Detail</a>
                            </td>
                        </tr>
                        @endforeach

                        @if(count($refunds) < 1)
                        <tr>
                            <td colspan="7" class="text-center">No Refunds</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="card-footer p-8pt">
                    @if($refunds->hasPages())
                    {{ $refunds->links('layouts.parts.page') }}
                    @else
                    <ul class="pagination justify-content-start pagination-xsm m-0">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Previous">
                                <span aria-hidden="true" class="material-icons">chevron_left</span>
                                <span>Prev</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Page 1">
                                <span>1</span>
                            </a>
                        </li>
                        <li class="page-item disabled">
                            <a class="page-link" href="#" aria-label="Next">
                                <span>Next</span>
                                <span aria-hidden="true" class="material-icons">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(function() {
        //
    });
</script>

@endpush

@endsection