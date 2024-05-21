@extends('layouts.app')

@section('content')

<div class="mdk-header-layout__content page-content ">
    <div class="pt-32pt pt-sm-64pt pb-32pt">
        <div class="page-section container page__container">

            <div class="page-separator mb-4">
                <div class="page-separator__text">@lang('labels.auth.register.title') </div>
            </div>

            <div class="row">
                
                <div class="col-lg-5 p-0 mx-auto">
                    
                    <form id="frm_register" method="POST" action="{{ route('register') }}" class="card card-body p-32pt">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="firstname">@lang('labels.auth.register.first_name') *:</label>
                            <input id="firstname" type="text" name="firstname" class="form-control @error('firstname') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.first_name_placeholder')" value="{{ old('firstname') }}" tute-no-empty >

                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                        First Name is required. and max length is 32. <br>
                                        ex: Johe Doe
                                    </strong>
                                </span>

                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="lastname">@lang('labels.auth.register.last_name') *:</label>
                            <input id="lastname" type="text" name="lastname" class="form-control @error('lastname') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.last_name_placeholder')" value="{{ old('lastname') }}" tute-no-empty >

                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                         Last Name is required. and max length is 32. <br>
                                        ex: Johe Doe
                                    </strong>
                                </span>

                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">@lang('labels.auth.register.your_email') *:</label>
                            <input id="email" type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.your_email_placeholder')" value="{{ old('email') }}" tute-no-empty>
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                       Email is required. <br>
                                        ex: Johe@Doe.com
                                    </strong>
                                </span>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="phone">@lang('labels.auth.register.mobile_number') *:</label>
                            <input id="phone" type="number" name="phone" class="form-control @error('mobile_number') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.mobile_number')" value="{{ old('mobile_number') }}" tute-no-empty>
                                <span class="invalid-feedback" role="alert">
                                    <strong>
                                       phone is required. <br>
                                        ex: 222255557777
                                    </strong>
                                </span>
                            @error('mobile_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="timezone">@lang('labels.auth.register.your_timezone') *:</label>
                            <select name="timezone" class="form-control  @error('timezone') is-invalid @enderror"></select>
                        </div>

                        <div class="form-group mb-24pt">
                            <label class="form-label" for="password">@lang('labels.auth.register.password'):</label>
                            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="@lang('labels.auth.register.password_placeholder')">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <span class="invalid-feedback" role="alert">
                                Must be at least 8 characters, At least 1 number, 1 lowercase, 1 uppercase letter, At least 1 special character from @#$%&
                            </span>
                        </div>
                        <div class="form-group mb-24pt">
                            <label class="form-label" for="password">@lang('labels.auth.register.confirm_password'):</label>
                            <input id="password-confirm" type="password" name="password_confirmation" class="form-control"
                                placeholder="@lang('labels.auth.register.confirm_password_placeholder')">
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" value="" id="chk_terms" required="">
                                <label class="custom-control-label" for="chk_terms">
                                    @lang('string.auth.register.terms_and_conditions')
                                </label>
                            </div>
                        </div>

                        <button type="button" id="btn_register" class="btn btn-primary">@lang('labels.auth.register.create_account')</button>
                        <input type="hidden" name="recaptcha_v3" id="recaptcha_v3">
                    </form>
                </div>

                <div class="col-lg-7">
                    <div class="card card-body p-4">
                        <img src="{{ asset('assets/img/bg_register_now_1.jpg') }}" alt="" class="avatar-img rounded">
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="page-separator justify-content-center m-0">
            <div class="page-separator__text">@lang('labels.auth.login.sign_with')</div>
        </div>
        <div class="page-section text-center">
            <div class="container page__container">
                <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.facebook')</a>
                <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.twitter')</a>
                <a href="" class="btn btn-secondary btn-block-xs">@lang('labels.social.google_plus')</a>
            </div>
        </div> --}}
    </div>
</div>

@push('after-scripts')

<!-- Timezone Picker -->
<script src="{{ asset('assets/js/timezones.full.js') }}"></script>

@if(config("access.captcha.registration") > 0)

<script src="https://www.google.com/recaptcha/api.js?render={{ config('captcha.key') }}"></script>

<script>
    grecaptcha.ready(function() {
        grecaptcha.execute("{{ config('captcha.key') }}", {action: 'register'}).then(function(token) {
            if(token) {
                $("#recaptcha_v3").val(token);
            }
        });
    });
</script>

@endif

<script>
    $(function() {
        // var offset = new Date().getTimezoneOffset() / 60;
        // if(Math.abs(offset) < 10) {
            
        //     if(offset < 0) {
        //         offset = '+0' + Math.round(Math.abs(offset)) + ':00';
        //     } else {
        //         offset = '-0' + Math.round(Math.abs(offset)) + ':00';
        //     }
        // } else {
        //     if(offset < 0) {
        //         offset = '+' + Math.round(Math.abs(offset)) + ':00';
        //     } else {
        //         offset = '-' + Math.round(Math.abs(offset)) + ':00';
        //     }
        // }
        // $('select[name="timezone"]').timezones();
        // $('select[name="timezone"] option[data-offset="'+ offset +'"]').prop('selected', true);

        $('select[name="timezone"]').timezones();
        $('select[name="timezone"]').val('Asia/Kolkata').prop('selected', true);

        var pattern_pwd = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%&]).*$/;
        var pattern_name = /^[a-zA-Z]{1,16} [a-zA-Z]{1,16}$/i;

        $('#name').on('blur', function(e) {
            if (!pattern_name.test($(this).val())) {
                if (!$(this).hasClass('is-invalid')) {
                    $(this).addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
        });

        $('#password').on('keyup', function(e) {
            var rlt = checkPassword($(this).val());
            if (!rlt) {
                if(!$(this).hasClass('is-invalid')) {
                    $(this).addClass('is-invalid');
                }
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
            }
        });

        function checkPassword(password) {
            if (pattern_pwd.test(password)){
                return true;
            } else {
                return false;
            }
        }

        $('#btn_register').on('click', function(e) {
            var isCheckedTerms = $('#chk_terms').is(":checked");

            if ($('#frm_register').find('.is-invalid').length > 0) {
                swal('Error!', 'Please fix invalid fields', 'error');
            } else {
                if(isCheckedTerms) {
                    $('#frm_register').submit();
                } else {
                    swal('Error!', 'Please Check our Terms and Conditions ', 'error');
                }
            }
        });

    });
</script>

@endpush

@endsection