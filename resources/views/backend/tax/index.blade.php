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
                    <h2 class="mb-0">Payment Taxes</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Payment Taxes
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.tax.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">Taxes</div>
        </div>
        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists">
                <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>Sr No.</th>
                            <th>Name</th>
                            <th>Rate (in %)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach($tax as $item)
                        <tr>
                            <td></td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->rate }}</td>
                            <td>
                                @if($item->status == 1)
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Active</small>
                                    <span class="indicator-line rounded bg-primary"></span>
                                </div>
                                @else 
                                <div class="d-flex flex-column">
                                    <small class="js-lists-values-status text-50 mb-4pt">Inactive</small>
                                    <span class="indicator-line rounded bg-warning"></span>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($item->status == 0)
                                <a href="{{ route('admin.tax.publish', $item->id) }}" class="btn btn-success btn-sm" data-action="publish">
                                    <i class="material-icons">arrow_upward</i>
                                </a>
                                @else
                                <a href="{{ route('admin.tax.publish', $item->id) }}" class="btn btn-info btn-sm" data-action="publish">
                                    <i class="material-icons">arrow_downward</i>
                                </a>
                                @endif
                                @include('backend.buttons.edit', ['edit_route' => route('admin.tax.edit', $item->id), 'no_tooltip' => true])
                                @include('backend.buttons.delete', ['delete_route' => route('admin.tax.destroy', $item->id), 'no_tooltip' => true])
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

@endsection