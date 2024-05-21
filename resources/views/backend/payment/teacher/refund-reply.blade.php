@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- Quill Theme -->
<link type="text/css" href="{{ asset('assets/css/quill.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Refund Request Detail</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Refund Request Detail
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <!-- Actions -->
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="row">

            <div class="col-7">
                <div class="page-separator">
                    <div class="page-separator__text">Request Content</div>
                </div>

                <div class="list-group list-group-form">
                    <div class="list-group-item">
                        <div class="form-row align-items-center">
                            <label class="col-md-4 col-form-label form-label">Reason: </label>
                            <div role="group" class="col-md-8">
                                {{ $refund->reason }}
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="form-group mb-24pt">
                            <!-- quill editor -->
                            <div id="reply_editor" class="mb-0" style="min-height: 300px;">
                                @if($refund->confirm_status == 1)
                                {!! $refund->confirm !!}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <button id="btn_replay" class="btn btn-primary">Send</button>
                    </div>
                </div>
            </div>
            
            <div class="col-5">

                <div class="page-separator">
                    <div class="page-separator__text">Information</div>
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
                </div>
            </div>

        </div>
    </div>
</div>

@push('after-scripts')

<!-- Quill -->
<script src="{{ asset('assets/js/quill.min.js') }}"></script>
<script src="{{ asset('assets/js/quill.js') }}"></script>

<script>
    $(function() {

        var toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],  
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
        ];

        // Init Reply Editor
        var reply_editor = new Quill('#reply_editor', {
            theme: 'snow',
            placeholder: "Reply here...",
            modules: {
                toolbar: toolbarOptions
            },
        });

        $('#btn_replay').on('click', function() {
            var content = reply_editor.root.innerHTML;
            $.ajax({
                method: 'POST',
                url: '{{ route("admin.refund.replyByAjax") }}',
                data: {
                    id: '{{ $refund->id }}',
                    confirm: content,
                    type: 'teacher'
                },
                success: function(res) {
                    if(res.success) {
                        swal('Success!', 'Successfully processed refund', 'success');
                        $('#btn_replay').val('Sent Refund');
                        $('#btn_replay').attr('disabled', 'disabled');
                    } else {
                        swal('Error!', res.message);
                    }
                }
            });
        });
    });
</script>

@endpush

@endsection