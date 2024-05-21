@extends('layouts.app')

@section('content')
<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt pt-sm-64pt pb-32pt">
        <div class="page-section container page__container">
            <div class="col-lg-6 p-0 mx-auto">

                <div class="page-separator mb-4">
                    <div class="page-separator__text">
                        @lang('labels.auth.verify.thankyou')
                    </div>
                </div>
                @lang('string.auth.verify.note')
                @lang('string.auth.verify.not_received_email'),
                <form id="frm_resend" class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-primary-dodger-blue">{{ __('click here to request another') }}</button>.
                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                </form>

            </div>
        </div>
    </div>

    {{-- <div class="page-separator justify-content-center m-0">
        <div class="page-separator__text">or sign-in with</div>
    </div>
    <div class="bg-body pt-32pt pb-32pt pb-md-64pt text-center">
        <div class="container page__container">
            <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.facebook')</a>
            <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.twitter')</a>
            <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.google_plus')</a>
        </div>
    </div> --}}

</div>

@push('after-scripts')

<script>
    $(function() {
        $('#frm_resend').on('submit', function(e){
            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    console.log(res);
                    if(res.success) {
                        swal('Success!', 'Activation email sent, Please check your email address!', 'success');
                    } else {
                        swal('Error happend', res.message + '\n Please contact to support', 'error');
                    }
                }
            })
        });
    });
</script>

@endpush

@endsection
