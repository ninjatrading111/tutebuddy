@extends('layouts.app')

@section('content')

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
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Go To List</a>
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
                            <div class="page-separator__text">Profile photo</div>
                        </div>
                        <div class="card card-body font-size-16pt">
                            {{-- @if($user->avatar) --}}
                            {{-- <img src="{{asset('/storage/avatars/'. $user->avatar) }}" alt="people" width="250" class="rounded-circle" /> --}}
                            {{-- @else --}}
                            <img src="{{asset('/images/no-avatar.jpg')}}" alt="people" width="250" class="rounded-circle" />
                            {{-- @endif --}}
                        </div>

                        <div class="card card-body font-size-16pt">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex">
                                    <span class="flex form-label"><strong>Profile Status</strong></span>
                                    {{-- @if($user->profile == 1)
                                    <i class="material-icons text-primary profile-status">check</i>
                                    @else
                                    <i class="material-icons text-primary profile-status">close</i>
                                    @endif --}}
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

                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Name: </label>
                                    <span>Misha Lazarenko</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Email Address: </label>
                                    <span> mishalazarenko9@gmail.com</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Role: </label>
                                    <span>User</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Phone: </label>
                                    <span>+380714061863</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Address: </label>
                                    <span>184 rue du Faubourg Saint Denis, Deliy, State, India</span>
                                </div>
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Timezone: </label>
                                    <span> Asia/Kolkata</span>
                                </div>
                                {{-- @if(!empty($user->bank->gst_number))
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">GST NO: </label>
                                    <span>{{ $user->bank->gst_number }}</span>
                                </div>
                                @endif --}}
                                <div class="form-group">
                                    <label class="form-label font-size-16pt">Signup Date:</label>
                                    <span>
                                        {{-- {{ Carbon\Carbon::parse($user->created_at)->format('Y-m-d') }} --}}
                                        2020-09-03
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- @if($user->hasRole('admin'))

                        @if(!empty($user->about)) --}}
                        {{-- <div class="page-separator mt-32pt">
                            <div class="page-separator__text">Instrutor Information</div>
                        </div>

                        <div class="card">
                            <div class="card-body p-5">
                                <h4>
                                    {{ $user->headline }}
                                    User headline
                                </h4>
                                <p class="font-size-16pt">
                                    {{ $user->about }}
                                    User about
                                </p>
                            </div>
                        </div>
                        @endif

                        @if(!empty($user->qualifications))

                        <div class="card">
                            <div class="card-body p-5">
                                <h4>Professional Qualifications and Certifications</h4>
                                @foreach(json_decode($user->qualifications) as $qualification)
                                <p class="font-size-16pt mb-1"><strong>
                                    {{ $loop->iteration }}. 
                                </strong> 
                                {{ $qualification }}
                            </p>
                                @endforeach
                            </div>
                        </div>

                        @endif

                        @if(!empty($user->achievements))

                            <div class="card">
                                <div class="card-body p-5">
                                    <h4>Achievements</h4>
                                    @foreach(json_decode($user->achievements) as $achievement)
                                    <p class="font-size-16pt mb-1"><strong>
                                        {{ $loop->iteration }}. 
                                    </strong> 
                                    {{ $achievement }}
                                </p>
                                    @endforeach
                                </div>
                            </div>

                            @endif

                            @if(!empty($user->experience))

                            <div class="card">
                                <div class="card-body p-5">
                                    <h4>Experience</h4>
                                    <p class="font-size-16pt mb-1">
                                        {{ $user->experience }}
                                    </p>
                                </div>
                            </div>

                            @endif
                        @endif --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script>

    $(function() {

        $('#btn_approve').on('click', function(e) {
            var user_id = $(this).attr('data-user-id');
            var route = '/account/'+ user_id +'/approve';
            $.ajax({
                url: route,
                method: 'GET',
                success: function(res) {
                    if(res.success) {
                        $('i.profile-status').html('check');
                        $('#btn_approve').text('Approved');
                        $('#btn_approve').attr('disabled', 'disabled');
                        swal('Success!', res.message, 'success');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });

        $('#btn_decline').on('click', function(e) {
            var user_id = $(this).attr('data-user-id');
            var route = '/account/'+ user_id +'/decline';
            $.ajax({
                url: route,
                method: 'GET',
                success: function(res) {
                    if(res.success) {
                        swal('Warning!', res.message, 'warning');
                        $('i.profile-status').html('close');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        });
    });
</script>

@endpush

@endsection