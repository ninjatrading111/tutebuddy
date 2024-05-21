{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="accordion/bootstrap.min.css">
    <script src="accordion/jquery.slim.min.js"></script>
    <script src="accordion/popper.min.js"></script>
    <script src="accordion/bootstrap.bundle.min.js"></script>
    <link type="text/css" href="{{ asset('font_awesome4_cheatsheet-master\css\font-awesome.min.css') }}" rel="stylesheet"> --}}
    @extends('layouts.app')
    @section('style')
        <style>
            body {
                margin:0;
                padding:0;
                color: #000;
                overflow-x: hidden;
                height: 100%;
                background-color: #F44336;
                background-repeat: no-repeat;
            }
    
            /*Outter Card*/
            .card0 {
                background-color: #F5F5F5;
                border-radius: 8px;
                z-index: 0;
            }
    
            /*Inner Card*/
            .card00 {
                z-index: 0;
            }
    
            /*Left side card with progressbar*/
            .card1 {
                margin-left: 140px;
                z-index: 0;
                border-right: 1px solid #F5F5F5;
            }
    
            /*right side cards*/
            .card2 {
                display: none;
            }
    
            .card2.show {
                display: block;
            }
    
            .social {
                border-radius: 50%;
                background-color: #FFCDD2;
                color: #E53935;
                height: 47px;
                width: 47px;
                padding-top: 16px;
                cursor: pointer;
            }
    
            input,
            select {
                padding: 2px;
                border-radius: 0px;
                box-sizing: border-box;
                color: #9E9E9E;
                border: 1px solid #BDBDBD;
                font-size: 16px;
                letter-spacing: 1px;
                height: 50px !important;
            }
    
            select {
                width: 100%;
                margin-bottom: 85px;
            }
    
            input:focus,
            select:focus {
                -moz-box-shadow: none !important;
                -webkit-box-shadow: none !important;
                box-shadow: none !important;
                border: 1px solid #E53935 !important;
                outline-width: 0 !important;
            }
    
            /*Red colored checkbox*/
            .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
                background-color: #E53935;
            }
    
            .form-group {
                position: relative;
                margin-bottom: 1.5rem;
                width: 77%;
            }
    
            .form-control-placeholder {
                position: absolute;
                top: 0px;
                padding: 12px 2px 0 2px;
                transition: all 300ms;
                opacity: 0.5;
            }
    
            .form-control:focus+.form-control-placeholder,
            .form-control:valid+.form-control-placeholder {
                font-size: 95%;
                top: 10px;
                transform: translate3d(0, -100%, 0);
                opacity: 1;
                background-color: #fff;
            }
    
            .next-button {
                width: 18%;
                height: 50px;
                background-color: #BDBDBD;
                color: #fff;
                border-radius: 6px;
                padding: 10px;
                cursor: pointer;
            }
    
            .next-button:hover {
                background-color: #E53935;
                color: #fff;
            }
    
            .get-bonus {
                margin-left: 154px;
            }
    
            /*Cookie pic*/
            .pic {
                width: 230px;
                height: 110px;
            }
    
            /*Icon progressbar*/
            #progressbar {
                position: absolute;
                left: 35px;
                overflow: hidden;
                color: #E53935;
            }
    
            #progressbar li {
                list-style-type: none;
                font-size: 8px;
                font-weight: 400;
                margin-bottom: 36px;
            }
    
            #progressbar li:nth-child(4) {
                margin-bottom: 88px;
            }
    
            #progressbar .step0:before {
                content: "";
                color: #fff;
            }
    
            #progressbar li:before {
                width: 30px;
                height: 30px;
                line-height: 30px;
                display: block;
                font-size: 20px;
                background: #fff;
                border: 2px solid #E53935;
                border-radius: 50%;
                margin: auto;
            }
    
            #progressbar li:last-child:before {
                width: 40px;
                height: 40px;
            }
    
            /*ProgressBar connectors*/
            #progressbar li:after {
                content: '';
                width: 3px;
                height: 66px;
                background: #BDBDBD;
                position: absolute;
                left: 58px;
                top: 15px;
                z-index: -1;
            }
    
            #progressbar li:last-child:after {
                top: 147px;
                height: 132px;
            }
    
            #progressbar li:nth-child(4):after {
                top: 81px;
            }
    
            #progressbar li:nth-child(2):after {
                top: 0px;
            }
    
            #progressbar li:first-child:after {
                position: absolute;
                top: -81px;
            }
    
            /*Color of the connector before*/
            #progressbar li.active:after {
                background: #E53935;
            }
    
            /*Color of the step before*/
            #progressbar li.active:before {
                background: #E53935;
                font-family: FontAwesome;
                content: "\f00c";
            }
    
            .tick {
                width: 100px;
                height: 100px;
            }
    
            .prev {
                display: block;
                position: absolute;
                left: 40px;
                top: 20px;
                cursor: pointer;
            }
    
            .prev:hover {
                color: #D50000 !important;
            }
    
            @media screen and (max-width: 912px) {
                .card00 {
                    padding-top: 30px;
                }
    
                .card1 {
                    border: none;
                    margin-left: 50px;
                }
    
                .card2 {
                    border-bottom: 1px solid #F5F5F5;
                    margin-bottom: 25px;
                }
    
                .social {
                    height: 30px;
                    width: 30px;
                    font-size: 15px;
                    padding-top: 8px;
                    margin-top: 7px;
                }
    
                .get-bonus {
                    margin-top: 40px !important;
                    margin-left: 75px;
                }
    
                #progressbar {
                    left: -25px;
                }
            }
    
            .accordion {
                background-color: #eee;
                color: #444;
                cursor: pointer;
                padding: 18px;
                width: 100%;
                border: none;
                text-align: left;
                outline: none;
                font-size: 15px;
                transition: 0.4s;
            }
    
            .active,
            .accordion:hover {
                background-color: #ccc;
            }
    
            .panel {
                padding: 0 18px;
                background-color: white;
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.2s ease-out;
            }
        </style>
        @endsection
    @section('content')
    
    {{-- <body> --}}
        <div class="page-section">
    
        <div class="container-fluid px-1 px-md-4 py-5 mx-auto">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-11 col-lg-10 col-xl-9">
                    <div class="card card0 border-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card00 m-2 border-0">
                                    <div class="row text-center justify-content-center px-3">
                                        <a href='/dashboard/active' class="prev text-danger"><span
                                                class="fa fa-long-arrow-left"> Go Back</span></a>
                                        <h3 class="mt-4">Writing Pages</h3>
                                    </div>
                                    <div class="d-flex flex-md-row px-3 mt-4 flex-column-reverse">
                                        <div id="accordion" class="col-md-12">
                                            <div class="card">
                                                <div class="card-header" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link" id="btn1" aria-expanded="true"
                                                            aria-controls="acedamic_acc">
                                                            <label class="form-label">Acedamic Level* <i class="fa fa-chevron-down" style="float:right" aria-hidden="true"></i></label>   
                                                        </button>
                                                       
                                                    </h5>
                                                </div>
    
                                                <div id="acedamic_acc" class="collapse show" aria-labelledby="headingOne"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group ">
                                                            {{-- <label class="form-label">Acedamic Level*</label> --}}
                                                            <select name="category" class="form-control custom-select"
                                                                data-toggle="select" tute-no-empty>
                                                                <option value="">Select level</option>
                                                                <option>HTML</option>
                                                                <option>CSS</option>
                                                                <option>jQuery</option>
                                                                <option>Javascript</option>
                                                            </select>
                                                            <small class="form-text text-muted">Select Acedemic
                                                                level</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingTwo">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id="btn2"
                                                            data-toggle="collapse" data-target="#subject_acc"
                                                            aria-expanded="false" aria-controls="subject_acc">
                                                            Step #2
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="subject_acc" class="collapse" aria-labelledby="headingTwo"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <label class="form-label">Subject*</label>
                                                            <select name="category" class="form-control custom-select"
                                                                data-toggle="select" tute-no-empty>
                                                                <option value="">Select subject</option>
                                                                <option>HTML</option>
                                                                <option>CSS</option>
                                                                <option>jQuery</option>
                                                                <option>Javascript</option>
                                                            </select>
                                                            <small class="form-text text-muted">Select Subject </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id='btn3'
                                                            data-toggle="collapse" data-target="#formating_acc"
                                                            aria-expanded="false" aria-controls="formating_acc">
                                                            Step #3
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="formating_acc" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label class="form-label">Formating*</label>
                                                                <select name="category" class="form-control custom-select"
                                                                    data-toggle="select" tute-no-empty>
                                                                    <option value="">Select Formating</option>
                                                                    <option>HTML</option>
                                                                    <option>CSS</option>
                                                                    <option>jQuery</option>
                                                                    <option>Javascript</option>
                                                                </select>
                                                                <small class="form-text text-muted">Select Formating
                                                                </small>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id='btn4'
                                                            data-toggle="collapse" data-target="#number_acc"
                                                            aria-expanded="false" aria-controls="number_acc">
                                                            Step #4
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="number_acc" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label class="form-label">Number of source*</label>
                                                                <select name="category" class="form-control custom-select"
                                                                    data-toggle="select" tute-no-empty>
                                                                    <option value="">Select NUmber</option>
                                                                    <option>HTML</option>
                                                                    <option>CSS</option>
                                                                    <option>jQuery</option>
                                                                    <option>Javascript</option>
                                                                </select>
                                                                <small class="form-text text-muted">Select NUmber of
                                                                    sourcel</small>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id='btn5'
                                                            data-toggle="collapse" data-target="#writer_acc"
                                                            aria-expanded="false" aria-controls="writer_acc">
                                                            Step #5
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="writer_acc" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label class="form-label">Writer*</label>
                                                                <select name="category" class="form-control custom-select"
                                                                    data-toggle="select" tute-no-empty>
                                                                    <option value="">Select Writer</option>
                                                                    <option>HTML</option>
                                                                    <option>CSS</option>
                                                                    <option>jQuery</option>
                                                                    <option>Javascript</option>
                                                                </select>
                                                                <small class="form-text text-muted">Select Writer </small>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id='btn6'
                                                            data-toggle="collapse" data-target="#extra_acc"
                                                            aria-expanded="false" aria-controls="extra_acc">
                                                            Step #6
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="extra_acc" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="form-group">
                                                                <label class="form-label">Extras *</label>
                                                                <select name="category" class="form-control custom-select"
                                                                    data-toggle="select" tute-no-empty>
                                                                    <option value="">Select Extras</option>
                                                                    <option>HTML</option>
                                                                    <option>CSS</option>
                                                                    <option>jQuery</option>
                                                                    <option>Javascript</option>
                                                                </select>
                                                                <small class="form-text text-muted">Select Extras l</small>
                                                            </div>
    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id="btn7"
                                                            data-toggle="collapse" data-target="#file_acc"
                                                            aria-expanded="false" aria-controls="file_acc">
                                                            Step #7
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="file_acc" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <div class="custom-file mb-3">
                                                                <input type="file" class="custom-file-input"
                                                                    id="customFile" name="filename">
                                                                <label class="custom-file-label" for="customFile">Choose
                                                                    file</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <button class="btn btn-link collapsed" id='btn8'
                                                            data-toggle="collapse" data-target="#summary_acc"
                                                            aria-expanded="false" aria-controls="summary_acc">
                                                            Step #8
                                                        </button>
                                                    </h5>
                                                </div>
                                                <div id="summary_acc" class="collapse" aria-labelledby="headingThree"
                                                    data-parent="#accordion">
                                                    <div class="card-body">
                                                        <div class="form-group">
                                                            <textarea name="short_description" class="form-control" cols="100%" rows="5"
                                                                placeholder="Short description"></textarea>
                                                            <small class="form-text text-muted">Order summary</small>
    
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row px-3">
                                        <h2 class="text-muted get-bonus mt-4 mb-5">Get Bonus <span
                                                class="text-danger">666</span> cookies</h2>
                                        <img class="pic ml-auto mr-3" src="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- </body> --}}
    @push('after-scripts')
    <script>
        var access = ['btn1'];
        $(document).ready(function() {
            $(".custom-file-input").on("change", function() {
                var fileName = $(this).val().split("\\").pop();
                $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
            });
            $('#btn1').on('click', function() {
                $('#acedamic_acc').collapse('toggle');
                access[0] = "btn1"
                var className=$('#btn1>i').attr('class')
                if(className=="fa fa-chevron-down"){
                    $('#btn1>i').removeClass()
                    $('#btn1>i').addClass('fa fa-chevron-right')
                }else{
                    $('#btn1>i').removeClass()
                    $('#btn1>i').addClass('fa fa-chevron-down')
                }
            })
            $('#btn2').on('click', function() {
                if (access[0] != 'btn1') {
                    return false
                } else {
                    $('#subject_acc').collapse('toggle');
                    access[1] = "btn2"
                }
            })
            $('#btn3').on('click', function() {
                if (access[1] != 'btn2') {
                    return false
                } else {
                    $('#formating_acc').collapse('toggle');
                    access[2] = "btn3"
                }
            })
            $('#btn4').on('click', function() {
                if (access[2] != 'btn3') {
                    return false
                } else {
                    $('#number_acc').collapse('toggle');
                    access[3] = "btn4"
                }
            })
            $('#btn5').on('click', function() {
                if (access[3] != 'btn4') {
                    return false
                } else {
                    $('#writer_acc').collapse('toggle');
                    access[4] = "btn5"
                }
            })
            $('#btn6').on('click', function() {
                if (access[4] != 'btn5') {
                    return false
                } else {
                    $('#extra_acc').collapse('toggle');
                    access[5] = "btn6"
                }
            })
            $('#btn7').on('click', function() {
                if (access[5] != 'btn6') {
                    return false
                } else {
                    $('#file_acc').collapse('toggle');
                    access[6] = "btn7"
                }
            })
            $('#btn8').on('click', function() {
                if (access[6] != 'btn7') {
                    return false
                } else {
                    $('#summary_acc').collapse('toggle');
                    access[7] = "btn8"
                }
            })
            // for (i = 0; i < acc.length; i++) {
            //     acc[i].addEventListener("click", function() {
    
            //     });
            // }
        });
    </script>
    
    {{-- </html> --}}
    @endpush
    @endsection
    