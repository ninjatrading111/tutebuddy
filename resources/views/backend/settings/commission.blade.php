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

                        <li class="breadcrumb-item active">Commission</li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator mb-4">
            <div class="page-separator__text">Commission Rate</div>
        </div>

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">

                <div class="card-header text-center pb-1">
                    <h2>Commission Rate</h2>
                </div>

                <div class="card-body">
                    <div class="p-4">
                        <div class=" form-group controls form-inline">
                            <label class="form-label col-lg-3 text-left">Currency: </label>
                            <select class="form-control col-lg-8" id="app__currency" name="app__currency">
                                @foreach(config('currencies') as $currency)
                                <option @if(config('app.currency')==$currency['short_code']) selected @endif
                                    value="{{$currency['short_code']}}">
                                    {{$currency['symbol'].' - '.$currency['name']}}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="controls form-inline">
                                <label for="" class="form-label col-lg-3 text-left">Commission Rate (%):</label>
                                <input type="number" class="form-control col-lg-8" id="account__fee" name="account__fee" value="{{ config('account.fee') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary">Update</button>
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