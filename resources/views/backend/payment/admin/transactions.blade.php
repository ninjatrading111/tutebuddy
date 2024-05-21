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
                    <h2 class="mb-0">All Transactions</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            All Transactions
                        </li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <!-- <div class="page-separator">
            <div class="page-separator__text">My Transactions</div>
        </div> -->

        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-order js-lists-values-date">
                <table id="tbl_sales" class="table mb-0 thead-border-top-0 table-nowra" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-order">
                                    @lang('labels.backend.payment.table.transaction_id')
                                </a>
                            </th>
                            <th>@lang('labels.backend.my_account.user_name')</th>
                            <th>@lang('labels.backend.payment.table.amount')</th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-date">
                                    @lang('labels.backend.table.date')</a>
                            </th>
                            <th>@lang('labels.backend.table.status')</th>
                            <th>@lang('labels.backend.table.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="toggle">
                        @foreach($transactions as $transaction)
                        <tr>
                            <td class="pr-0"></td>
                            <td>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded-circle">TR</span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="flex d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-lead">{{ $transaction->transaction_id }}</strong></p>
                                            <small class="js-lists-values-email text-50">
                                                {{ $transaction->type }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                    <div class="avatar avatar-sm mr-8pt">
                                        <span class="avatar-title rounded-circle">{{ mb_substr($transaction->user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="media-body">
                                        <div class="d-flex flex-column">
                                            <p class="mb-0"><strong class="js-lists-values-name">{{ $transaction->user->name }}</strong></p>
                                            <small class="js-lists-values-email text-50">{{ $transaction->user->email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-budget">
                                        <strong>{{ getCurrency(config('app.currency'))['symbol'] . $transaction->amount }}</strong>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                <small class="js-lists-values-date"><strong>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d Y') }}</strong></small>
                                    <small class="text-50">{{ \Carbon\Carbon::parse($transaction->created_at)->diffForHumans() }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">{{ $transaction->status }}</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.transactions.invoice.download', $transaction->id) }}" class="btn btn-sm btn-outline-secondary">
                                    Invoice<i class="icon--right material-icons">file_download</i></a>
                            </td>
                        </tr>
                        @endforeach

                        @if(count($transactions) < 1)
                        <tr>
                            <td colspan="7" class="text-center">No Transactions</td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="card-footer p-8pt">
                    @if($transactions->hasPages())
                    {{ $transactions->links('layouts.parts.page') }}
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