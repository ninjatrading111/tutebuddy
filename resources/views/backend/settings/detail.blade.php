@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Settings</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item"><a href="{{route('admin.settings.paygateway')}}">Payment Gateway</a></li>
                        <li class="breadcrumb-item active">Update</li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator mb-4">
            <div class="page-separator__text">Update Gateway</div>
        </div>

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <div class="card-header pb-1">
                    <center><h2>Authorize.net</h2></center>
                    <h3>Global Setting For Authorize.net</h3>
                </div>

                <div class="card-body">
                    <div class="p-4 row">
                        <div class="col-md-6">
                            <label class="form-label text-left">Login ID*: </label>
                            <input class="form-control" id="login_id" value="5dfgdf2g4"></input>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-left">Transaction Key*: </label>
                            <input class="form-control" id="transaction_key" value="5dfgdf2g4"></input>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary btn-block">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
{{-- 
<script>
    
$(document).ready(function() {

    // Init code
    $('.show_secret').on('click', function(e) {
        var input_id = $(this).attr('data-id');
        if($(this).find('span.fa').hasClass('fa-eye')) {
            $('#' + input_id).attr('type', 'text');
            $(this).find('span.fa').removeClass('fa-eye');
            $(this).find('span.fa').addClass('fa-eye-slash');
        } else {
            $('#' + input_id).attr('type', 'password');
            $(this).find('span.fa').addClass('fa-eye');
            $(this).find('span.fa').removeClass('fa-eye-slash');
        }
    });
});

$('#frm_setting').submit(function(e) {
    e.preventDefault();

    $(this).ajaxSubmit({
        success: function(res) {

            if (res.success) {
                swal("Success!", "Successfully updated", "success");
            }
        }
    });
});

$('.custom-checkbox-toggle').on('click', 'input[type="checkbox"]', function() {

    var id = $(this).attr('id');

    if ($(this).prop('checked')) {
        $(this).val('1');
        $('div.wrap[for="' + id + '"').removeClass('d-none');
    } else {
        $(this).val('0');
        $('div.wrap[for="' + id + '"').addClass('d-none');
    }

});
</script> --}}

@endpush

@endsection