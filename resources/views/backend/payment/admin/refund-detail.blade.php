@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Refund Detail</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Refund Detail
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    @if($refund->status == 1)
                    <button id="btn_refund" class="btn btn-accent" disabled>Sent Refund</button>
                    @else
                    <button id="btn_refund" class="btn btn-accent">Send Refund</button>
                    @endif
                </div>
                <div class="col-auto pl-0">
                    @if($refund->status != 1)
                    <button id="btn_dismiss" class="btn btn-primary">Dismiss</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="row">
            <div class="col-6">

                <div class="page-separator">
                    <div class="page-separator__text">Refund Information</div>
                </div>

                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label class="col-md-4 col-form-label form-label">Order Id: </label>
                            <div role="group" class="col-md-8">
                                <strong>{{ $refund->order->uuid }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label class="col-md-4 col-form-label form-label">Payment Id: </label>
                            <div role="group" class="col-md-8">
                                <strong>{{ $refund->order->payment_id }}</strong>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label class="col-md-4 col-form-label form-label">Request Customer: </label>
                            <div role="group" class="col-md-8">
                                <div class="d-flex align-items-center" style="white-space: nowrap;">

                                    <div class="avatar avatar-42pt mr-8pt">
                                        @if(empty($refund->user->avatar))
                                        <span class="avatar-title rounded-circle">{{ mb_substr($refund->user->name, 0, 2) }}</span>
                                        @else
                                        <img src="{{ asset('storage/avatars/' . $refund->user->avatar ) }}" alt="Avatar" class="avatar-img rounded-circle">
                                        @endif
                                    </div>

                                    <div class="flex ml-4pt">
                                        <div class="d-flex flex-column">
                                            <p class="mb-0"><strong>{{ $refund->user->name }}</strong></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label class="col-md-4 col-form-label form-label">@lang('labels.backend.payment.order_detail.reason'): </label>
                            <div role="group" class="col-md-8">
                                {{ $refund->reason }}
                            </div>
                        </div>
                    </div>
                    @if($refund->confirm_status == 1)
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label class="col-md-4 col-form-label form-label">Confirmed Reason: </label>
                            <div role="group" class="col-md-8">
                                {!! $refund->confirm !!}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-6">
                <div class="page-separator">
                    <div class="page-separator__text">Order Items</div>
                </div>

                <div class="list-group list-group-form">
                    @php $total = 0; @endphp
                    @foreach($refund->order->items as $item)
                    @php $total += $item->amount; @endphp
                    <div class="list-group-item p-16pt">
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
                            <span>{{ getCurrency(config('app.currency'))['symbol'] . $item->price }}</span>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script>
    $(function() {
        $('#btn_refund').on('click', function() {
            $.ajax({
                method: 'GET',
                url: '/dashboard/refunds/process/' + '{{ $refund->id }}',
                success: function(res) {
                    if(res.success) {
                        swal('Success!', 'Successfully processed refund', 'success');
                        $('#btn_refund').val('Sent Refund');
                        $('#btn_refund').attr('disabled', 'disabled');
                    } else {
                        swal('Error!', res.message);
                    }
                }
            });
        });

        $('#btn_dismiss').on('click', () => {
            
            $.ajax({
                methods: 'POST',
                url: '{{ route("admin.refund.replyByAjax") }}',
                data: {
                    id: '{{ $refund->id }}',
                    type: 'admin'
                },
                success: (res) => {
                    console.log(res);
                }
            });
        });
    });
</script>

@endpush

@endsection