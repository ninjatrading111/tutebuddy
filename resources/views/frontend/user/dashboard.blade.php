@extends('layouts.app')
@push('after-styles')

<link type="text/css" href="{{ asset('assets/css/semantic.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/owl.carousel.min.css') }}" rel="stylesheet">
<link type="text/css" href="{{ asset('assets/css/owl.theme.default.min.css') }}" rel="stylesheet">

<style>
    .owl-nav {
        height: 0;
    }
</style>

@endpush

{{-- @section('content') --}}
@section('content')
    <!-- Header Layout Content -->
    <div class="mdk-header-layout__content page-content ">

        <div class="page-section ">
            <div
                class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">

                <div class="flex mb-32pt mb-md-0">
                    <h2 class="text-white mb-0">Apple</h2>
                    <p class="lead text-white-50 d-flex align-items-center">
                    </p>
                </div>
            </div>
        </div>

        <div class="page-section">
            <div class="container page__container">
                <div class="card border-1 border-left-3 border-left-accent text-center mb-lg-0">
                    <div class="card-body">
                        <div class="table-responsive" data-toggle="lists"
                            data-lists-sort-by="js-lists-values-order js-lists-values-date">
                            <div class="mb-1 align-items-center mx-auto" align="center">
                                <h3>Active Order</h3>
                            </div>

                            <table id="tbl_sales"
                                class="table mb-0 thead-border-top-0 table-hover table-striped table-nowra p-3 text-2xl"
                                data-page-length='50'>
                                <thead>
                                    <tr>
                                        <th class="pr-0">No</th>
                                        <th>
                                            <a href="javascript:void(0)" class="sort"
                                                data-sort="js-lists-values-order">Order</a>
                                        </th>
                                        <th>
                                            <a href="javascript:void(0)" class="sort"
                                                data-sort="js-lists-values-date">Deadline</a>
                                        </th>
                                        <th> Subject </th>
                                        <th> Status </th>
                                        <th> Action </th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="toggle">
                                    <tr>
                                        <td>1</td>
                                        <td>Writing</td>
                                        <td>2023/03/05</td>
                                        <td>Math</td>
                                        <td>Complete</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Editing</td>
                                        <td>2022/07/02</td>
                                        <td>English</td>
                                        <td>Complete</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Other</td>
                                        <td>2024/11/25</td>
                                        <td>Vollyball</td>
                                        <td>Complete</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @push('after-scripts')
            <script>
                $(document).ready(function() {
                    $('#personal_form').on('submit', function(e) {
                        e.preventDefault();
                        var formData = new FormData(this);
                        console.log(formData, 'formdata');
                        $.ajax({
                            url: 'myprofile/fetchdata', // Replace with your Laravel endpoint
                            type: 'POST',
                            data: formData,
                            dataType: 'json',
                            processData: false, // Important to prevent jQuery from converting the FormData object into a string
                            contentType: false, // Important to prevent jQuery from setting content-type header
                            success: function(res) {
                                // console.log(res.error);
                                var out_html = ''
                                if (res.error.length > 0) {
                                    // alert('dd')
                                    for (i = 0; i < res.error.length; i++) {
                                        out_html += res.error[i]
                                    }
                                    console.log(out_html)
                                    $('#output').html(out_html)
                                } else {
                                    $('#output').html(res.success)
                                }
                                // Handle success
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error(textStatus, errorThrown);
                                // Handle error
                            }
                        });
                    });
                    // $(document).on('click','#personal_btn',function(){
                    //     var form_data=$('#personal_form').serializeArray();
                    //     var file=$('#image')[0].files[0];
                    //     // console.log(file);
                    //     console.log(form_data);
                    //     $.ajax({
                    //         url:'myprofile/fetchdata',
                    //         method:'post',
                    //         data:form_data,
                    //         dataType:'json',
                    //         success:function(res){
                    //             console.log(res,'res')
                    //         }
                    //     })
                    // })
                })
            </script>
        @endpush
    @endsection
