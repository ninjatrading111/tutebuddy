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
            <div class="flex d-flex flex-column flex-sm-row align-items-center">
                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.user_detail.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.users.index') }}">User Management</a>
                        </li>
                        <li class="breadcrumb-item active">
                            Edit Account
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.kyc.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section container page__container">
        <div class="">
            <div class="form-group">
                <div class="media">
                    <div class="media-left mr-32pt">
                        <div class="page-separator">
                            <div class="page-separator__text">User Information</div>
                        </div>
                        <div class="card card-body font-size-16pt">
                            @if($user->avatar)
                            <img src="{{asset('/storage/avatars/'. $user->avatar) }}" alt="people" width="250" class="rounded-circle" />
                            @else
                            <img src="{{asset('/images/no-avatar.jpg')}}" alt="people" width="250" class="rounded-circle" />
                            @endif
                        </div>

                        <div class="card card-body font-size-16pt">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex">
                                    <span class="flex form-label"><strong>Verify Status</strong></span>
                                    @if($user->kyc->status == 1)
                                    <i class="material-icons text-primary verify-status">check</i>
                                    @else
                                    <i class="material-icons text-primary verify-status">close</i>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="media-body">

                        <div class="page-separator">
                            <div class="page-separator__text">Profile Information</div>
                        </div>
                        <div class="card">
                            <div class="card-body p-5 font-size-16pt">

                                <div class="">
                                    <label class="form-label font-size-16pt">Name: </label>
                                    <span>{{ $user->name }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Email Address: </label>
                                    <span>{{ $user->email }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Role: </label>
                                    <span>{{ $user->getRoleNames()->first() }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Phone: </label>
                                    <span>{{ $user->phone }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Address: </label>
                                    <span>{{ $user->address }}, {{ $user->city }}, {{ $user->state }}, {{ $user->country }}</span>
                                </div>
                                <div class="">
                                    <label class="form-label font-size-16pt">Timezone: </label>
                                    <span>{{ $user->timezone }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="page-separator mt-32pt">
                            <div class="page-separator__text">KYC Information</div>
                        </div>

                        <?php
                            $arrDocTypes = [
                                'government_id' => 'Government ID',
                                'passport'      => 'Passport',
                                'drive_license' => 'Driving License'
                            ];
                        ?>

                        <div class="card">
                            <div class="card-body p-5 font-size-16pt">
                                <div>
                                    <label class="form-label font-size-16pt">Document Type: </label>
                                    <span>{{ $arrDocTypes[$user->kyc->document_type] }}</span>
                                </div>
                                <div>
                                    <label class="form-label font-size-16pt">Document IDNumber: </label>
                                    <span>{{ $user->kyc->document_id }}</span>
                                </div>
                                <div>
                                    <label class="form-label font-size-16pt">Document Front Image: </label>
                                    <a href="{{asset('/storage/uploads/'. $user->kyc->front_img) }}" target="_blank">Click Here to See</a>
                                </div>
                                <div>
                                    <label class="form-label font-size-16pt">Document Back Image: </label>
                                    <a href="{{asset('/storage/uploads/'. $user->kyc->back_img) }}" target="_blank">Click Here to See</a>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="hidden" id="kyc-id" value="{{ $user->kyc->id }}">
                            @if ($user->kyc->status != 1)
                                <button id="btn_approve" class="btn btn-primary">Approve</button>
                            @endif
                            @if ($user->kyc->status != 2)
                                <button id="btn_decline" class="btn btn-accent">Reject</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

<!-- Modal for Reject Reason -->
<div class="modal fade" id="mdl_reason" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Reason</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="form-group mb-0">
                    <!-- quill editor -->
                    <div id="reason_editor" class="mb-0" style="min-height: 400px;"></div>
                </div>
            </div>

            <div class="modal-footer">
                <div class="form-group">
                    <button id="btn_confirm" class="btn btn-outline-primary btn-update">@lang('labels.backend.buttons.confirm')</button>
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

        // Ajax Header for Ajax Call
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });

        var toolbarOptions = [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            [{ 'color': [] }, { 'background': [] }],  
            ['bold', 'italic', 'underline'],
            ['link', 'blockquote', 'code', 'image'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
        ];

        // Init Quill Editor for Reason Content
        var reason_editor = new Quill('#reason_editor', {
            theme: 'snow',
            placeholder: "Reject Reason",
            modules: {
                toolbar: toolbarOptions
            },
        });

        $('#btn_approve').on('click', function(e) {
            var id = $('#kyc-id').val();
            var route = '/dashboard/ajax/kyc/'+ id +'/approve';
            $.ajax({
                url: route,
                method: 'POST',
                success: function(res) {
                    if(res.success) {
                        swal('Success!', res.message, 'success');
                        $('i.verify-status').html('check');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $('#btn_decline').on('click', function(e) {
            $('#mdl_reason').modal('toggle');
        });

        $('#btn_confirm').on('click', function() {
            var content = reason_editor.root.innerHTML;
            if(content != '<p><br></p>') {
                sendReject(content);
                $('#mdl_reason').modal('toggle');
            } else {
                $('#reason_editor').focus();
            }
        });

        function sendReject(content) {
            var id = $('#kyc-id').val();
            var route = '/dashboard/ajax/kyc/'+ id +'/reject';
            $.ajax({
                url: route,
                method: 'POST',
                data: {
                    content: content
                },
                success: function(res) {
                    if(res.success) {
                        swal('Success!', res.message, 'warning');
                        $('i.verify-status').html('close');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    });
</script>

@endpush

@endsection