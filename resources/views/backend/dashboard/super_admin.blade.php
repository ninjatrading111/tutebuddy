@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.dashboard.title')</h2>

                    <ol class="breadcrumb p-0 m-0">

                        <li class="breadcrumb-item active">

                            @lang('labels.backend.dashboard.title')

                        </li>

                    </ol>

                </div>
            </div>

        </div>
    </div>

    <div class="container page__container">
        <div class="page-section">

            <div class="row row mb-32pt">
                <div class="col-lg-3">
                    <a href="{{ route('admin.users.index') }}?tab=teacher">
                        <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $teachers_count }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.admins')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.users.index') }}?tab=student">
                        <div class="card border-1 border-left-3 border-left-accent-yellow text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $students_count }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.students')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.courses.index') }}">
                        <div class="card border-1 border-left-3 border-left-dark text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $enrolled_courses }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.enrolled_courses')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.courses.index') }}?tab=published">
                        <div class="card border-1 border-left-3 border-left-primary text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $active_courses }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.active_courses')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row row mb-32pt">
                <div class="col-lg-3">
                    <a href="{{ route('admin.orders') }}">
                        <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ getCurrency(config('app.currency'))['symbol'] . number_format($total_sales, 2) }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.total_sales')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.orders') }}">
                        <div class="card border-1 border-left-3 border-left-accent-yellow text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ getCurrency(config('app.currency'))['symbol'] . number_format($total_payments, 2) }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.total_payments')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.courses.index') }}?tab=pending">
                        <div class="card border-1 border-left-3 border-left-primary text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $course_approval }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.courses_approval')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.admin.liveSessions') }}">
                        <div class="card border-1 border-left-3 border-left-dark text-center mb-lg-0">
                            <div class="card-body">
                                <h4 class="h2 mb-0">{{ $live_lessons }}</h4>
                                <div><label class="form-label">@lang('labels.backend.dashboard.live_lessons')</label></div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            @if(count($pending_courses) > 0)

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.courses_pending_approval')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div class="pl-8pt" style="background-color: #f5f7fa;">
                        <a href="{{ route('admin.courses.index') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">
                            @lang('labels.backend.buttons.browse_all')
                        </a>
                    </div>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table id="tbl_pending_courses" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>@lang('labels.backend.dashboard.table.no')</th>
                                <th>
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-time">Title
                                    </a>
                                </th>
                                <th>@lang('labels.backend.dashboard.table.admin')</th>
                                <th>@lang('labels.backend.dashboard.table.category')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>

                        <tbody class="list">
                            @foreach($pending_courses as $course)
                            <tr>
                                <td></td>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded bg-primary text-white">{{ mb_substr($course->title, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex flex-column">
                                                <small class="js-lists-values-project">
                                                    <strong>{{ $course->title }}</strong></small>
                                                <small class="js-lists-values-location text-50">{{ $course->slug }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded-circle">{{ mb_substr($course->teachers[0]->name, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $course->teachers[0]->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $course->teachers[0]->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $course->category->name }}</td>
                                <td>
                                    @if($loop->iteration < 2)
                                        @include('backend.buttons.show', ['show_route' => route('courses.show', $course->slug), 'no_tooltip' => true])
                                    @else
                                        @include('backend.buttons.show', ['show_route' => route('courses.show', $course->slug)])
                                    @endif
                                    <a href="{{ route('admin.courses.publish', $course->id) }}" class="btn btn-success btn-sm" data-action="publish">
                                        <i class="material-icons">arrow_upward</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(count($withdraw_requests) > 0)
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.withdraw_requests')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div class="pl-8pt" style="background-color: #f5f7fa;">
                        <a href="{{ route('admin.transactions') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">
                            @lang('labels.backend.buttons.browse_all')
                        </a>
                    </div>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th>@lang('labels.backend.dashboard.table.no')</th>
                                <th>
                                    <a href="javascript:void(0)" class="sort"
                                        data-sort="js-lists-values-time">@lang('labels.backend.dashboard.table.transaction_id')
                                    </a>
                                </th>
                                <th>@lang('labels.backend.dashboard.table.admin')</th>
                                <th>@lang('labels.backend.dashboard.table.amount')</th>
                                <th>@lang('labels.backend.dashboard.table.action')</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($withdraw_requests as $item)
                            <tr>
                                <td></td>
                                <td>{{ $loop->iteration }}</td>
                                <td><strong>{{ $item->transaction_id }}</strong></td>
                                <td>
                                    <div class="media flex-nowrap align-items-center" style="white-space: nowrap;">
                                        <div class="avatar avatar-sm mr-8pt">
                                            <span class="avatar-title rounded-circle">{{ mb_substr($item->user->name, 0, 2) }}</span>
                                        </div>
                                        <div class="media-body">
                                            <div class="d-flex align-items-center">
                                                <div class="flex d-flex flex-column">
                                                    <p class="mb-0"><strong class="js-lists-values-lead">{{ $item->user->name }}</strong></p>
                                                    <small class="js-lists-values-email text-50">{{ $item->user->email }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><strong>{{ $item->currency . $item->amount }}</strong></td>
                                <td><a href="javascript:void(0)" class="btn btn-accent btn-sm">Detail</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @if(count($orders) > 0)
            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.orders')</div>
                <div class="d-flex flex">
                    <div class="flex">&nbsp;</div>
                    <div class="pl-8pt" style="background-color: #f5f7fa;">
                        <a href="{{ route('admin.orders') }}" class="btn btn-md btn-white float-right border-accent-dodger-blue">
                            @lang('labels.backend.buttons.browse_all')
                        </a>
                    </div>
                </div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="table-responsive" data-toggle="lists" data-lists-sort-by="js-lists-values-schedule"
                    data-lists-sort-desc="true" data-lists-values='["js-lists-values-no"]'>
                    <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='5'>
                        <thead>
                            <tr>
                                <th style="width: 18px;" class="pr-0"></th>
                                <th> @lang('labels.backend.payment.table.order') </th>
                                <th> @lang('labels.backend.table.date') </th>
                                <th> @lang('labels.backend.table.course') </th>
                                <th> @lang('labels.backend.table.amount') </th>
                                <th> @lang('labels.backend.payment.sales.account_fee') </th>
                                <th> @lang('labels.backend.table.actions') </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                @foreach($order->items as $item)
                                    @if(!empty($item->course) && ($item->transaction))
                                        <?php
                                            $completed = false;
                                            if(\Carbon\Carbon::parse($item->course->end_date)->diffInDays(\Carbon\Carbon::now()) > 7 &&
                                            $item->course->end_date < \Carbon\Carbon::now()->format('Y-m-d')) {
                                                $completed = true;
                                            }
                                        ?>
                                        <tr>
                                            <td></td>
                                            <td>
                                                <strong>{{ $order->uuid }}</strong>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                <small class="js-lists-values-date"><strong>{{ \Carbon\Carbon::parse($order->created_at)->format('M d Y') }}</strong></small>
                                                    <small class="text-50">{{ \Carbon\Carbon::parse($order->created_at)->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center" style="white-space: nowrap;">
                
                                                    <div class="avatar avatar-32pt mr-8pt">
                                                        @if(empty($item->course->course_image))
                                                        <span class="avatar-title rounded-circle">{{ mb_substr($item->course->title, 0, 2) }}</span>
                                                        @else
                                                        <img src="{{ asset('storage/uploads/' . $item->course->course_image ) }}" alt="Avatar" class="avatar-img rounded-circle">
                                                        @endif
                                                    </div>
                
                                                    <div class="flex ml-4pt">
                                                        <div class="d-flex flex-column">
                                                            <p class="mb-0"><strong>{{ $item->course->title }}</strong></p>
                                                            <small class="text-50">{{ $item->course->category->name }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . $item->transaction->amount }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ getCurrency(config('app.currency'))['symbol'] . $item->transaction->account_fee }}</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.detail', $item->id) }}" class="btn btn-accent btn-sm">Detail</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.daily_signups')</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-body">
                    <canvas id="dailysignChart" class="chart-canvas"></canvas>
                </div>
            </div>

            <div class="page-separator">
                <div class="page-separator__text">@lang('labels.backend.dashboard.daily_orders')</div>
            </div>

            <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
                <div class="card-body">
                    <canvas id="dailyOrderChart" class="chart-canvas"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<!-- Moment.js -->
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/moment-range.min.js') }}"></script>

<!-- Chart.js -->
<script src="{{ asset('assets/js/Chart.min.js') }}"></script>

<script>

$(function() {

    var sign_days = JSON.parse('{{ $json_sign_days }}');
    var sign_admins = JSON.parse('{{ $json_sign_admins }}');
    var sign_students = JSON.parse('{{ $json_sign_students }}');
    var daily_orders = JSON.parse('{{ $json_daily_orders }}');

    var max_admins = Math.max.apply(Math, sign_admins);
    var max_studetns = Math.max.apply(Math, sign_students);
    var max_signups = max_admins > max_studetns ? max_admins : max_studetns;
    var max_daily = Math.max.apply(Math, daily_orders);

    var sign_data = {
        labels: sign_days,
        datasets: [
            { 
                data: sign_admins,
                label: "admins",
                borderColor: "#005EA6",
                fill: false
            }, { 
                data: sign_students,
                label: "Students",
                borderColor: "#8e5ea2",
                fill: false
            }
        ]
    }

    var sign_chart = new Chart($('#dailysignChart'), {
        type: 'line',
        data: sign_data,
        options: {
            title: {
                display: true,
                text: 'Daily Signups'
            },
            scales: {
                yAxes: [
                    {
                        ticks: {
                            callback: function(label, index, labels) {
                                if(Number.isInteger(label)) {
                                    return label;
                                }
                            },
                            suggestedMax: max_signups + 1
                        }
                    }
                ]
            }
        }
    });

    var order_data = {
        labels: sign_days,
        datasets: [
            {
                data: daily_orders,
                label: "Sales ({{ config('app.currency') }})",
                borderColor: 'red',
                fill: false
            }
        ]
    }

    var order_chart = new Chart($('#dailyOrderChart'), {
        type: 'line',
        data: order_data,
        options: {
            title: {
                display: true,
                text: "Daily Orders ({{ config('app.currency') }})"
            },
            scales: {
                yAxes: [
                    {
                        ticks: {
                            suggestedMax: max_daily + 1
                        }
                    }
                ]
            }
        }
    });

    $('#tbl_pending_courses').on('click', 'a[data-action="publish"]', function(e) {

        e.preventDefault();

        var url = $(this).attr('href');
        var tr = $(this).closest('tr');

        $.ajax({
            method: 'get',
            url: url,
            success: function(res) {
                console.log(res);
                if(res.success) {
                    if(res.published == 1) {
                        swal("Success!", 'Published successfully', "success");
                        tr.remove();
                    }
                }
            }
        });
    });
});

</script>
@endpush

@endsection