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
                    <h2 class="mb-0">Settings</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">Payment Gateway</li>

                    </ol>

                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator mb-4">
            <div class="page-separator__text">Payment Gateway</div>
        </div>

        <div class="flex" style="max-width: 100%">
            <div class="card dashboard-area-tabs p-relative o-hidden mb-0">
                <div class="card-header text-center pb-1">
                    <h2>Payment Gateway</h2>
                </div>

                <div class="card-body p-0 tab-content">
                    <Table id='gateway' class="table text-center item-center mb-0 thead-border-top-0 table-nowrap">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>NAME</th>
                                <th>STATUS</th>
                                <th class="">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gateways as $gateway) 
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$gateway['name']}}</td>
                                    <td><button disabled class="btn btn-warning btn-sm btn-rounded">{{$gateway['status']}}</button></td>
                                    <td>
                                        <a href="{{route('admin.settings.gateDetail', '$gateway->id')}}" class="btn btn-info">Edit</a>
                                        @if($gateway['status']=="disabled")
                                            <button class="btn btn-primary">Enable</button>
                                        @endif
                                        @if($gateway['status']=="enabled")
                                            <button class="btn btn-danger">Disable</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </Table>
                </div>
                <div class="card-footer text-right">
                    <button type="button" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('after-scripts')

<script>
    
$(document).ready(function() {
    console.log(gateways, "sadfasdfsdf");

    var table=$('#gateway').DataTable({
        rowRecoder:true,
        columnDefs:[

        ],
        columns: [
            {gateways : 'name'},
            {gateways : 'status'},
            {gateways : 'action'}
        ],
        oLanguage: {
            sEmptyTable: 'You have no any Gateway'
        }
    })






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
</script>

@endpush

@endsection