@extends('layouts.app')
@section('style')
    <style>
        .card {
            margin-bottom: 0;
        }

        .card-header dark-bg-black:hover {
            background: #ccc;
            cursor: pointer;
        }

        body {
            margin: 0;
            padding: 0;
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

        .row:after {
            content: "";
            display: table;
            clear: both;
        }

        /* .right{
                width:100%;
                padding:
            }
            button{
                float:right;
                margin:3px;
            } */

        @media screen and (max-width:768px) {
            button {
                display: block;
                width: 100%;
                margin: auto;
                float: left;
                overflow: hidden;
            }

            .form-group {
                width: 100%;
            }
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

        .card-header dark-bg-black {
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
        <div class="mdk-header-layout__content page-content ">

            <div class="page-section dark-bg-black-80 ">
                <div
                    class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">
    
                    <div class="flex mb-32pt mb-md-0">
                        <h2 class="text-white mb-0">Apple</h2>
                        <p class="lead text-white-50 d-flex align-items-center">
                        </p>
                    </div>
                </div>
            </div>
    <div class="page-section dark-bg-black">

        <div class="container-fluid dark-bg-black-80 px-1 px-md-4 py-5 mx-auto">
            <div class="row d-flex justify-content-center">
                <div class="col-12 col-md-11 col-lg-10 col-xl-9">
                    <div class="card card0 border-0 dark-bg-black">
                        <div class="row">
                            <div class="col-12">
                                <div class="card card00 m-2 border-0 dark-bg-black">
                                    <div class="row text-center justify-content-center px-3">
                                        <a href='/dashboard/active' class="prev text-danger"><span
                                                class="fa fa-long-arrow-left"> <b>C A N C E L</b> </span></a>
                                        <h3 class="mt-4 dark-text">Writing Pages</h3>
                                    </div>
                                    <div class="d-flex flex-md-row px-3 mt-4 flex-column-reverse">
 
                                    <div id="accordion" class="col-md-12">
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn1">
                                                <h5 class="mb-0">Step #1</h5>
                                            </div>
                                            <div id="writing_acc_1" class="collapse show" aria-labelledby="btn1" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Acedamic Level*</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select level</option>
                                                            <option>High school</option>
                                                            <option>College</option>
                                                            <option>Master</option>
                                                            <option>Phd</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select Acedemic level</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary next" data-target="#subject_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn2">
                                                <h5 class="mb-0">Step #2</h5>
                                            </div>
                                            <div id="writing_acc_2" class="collapse" aria-labelledby="btn2" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Subject*</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select subject</option>
                                                            <option>Math</option>
                                                            <option>Biology</option>
                                                            <option>Chemistry</option>
                                                            <option>Computer</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select Subject</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#acedamic_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#instruct_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn3">
                                                <h5 class="mb-0">Step #3</h5>
                                            </div>
                                            <div id="writing_acc_3" class="collapse" aria-labelledby="btn3" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Add Instruction*</label>
                                                        <textarea name="short_description" class="form-control" cols="100%" rows="5" placeholder="Short description"></textarea>
                                                        <small class="form-text text-muted">Add Instruction</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#subject_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#file_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn4">
                                                <h5 class="mb-0">Step #4</h5>
                                            </div>
                                            <div id="writing_acc_4" class="collapse" aria-labelledby="btn4" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <div class="custom-file mb-3">
                                                            <input type="file" class="custom-file-input" id="customFile" name="filename">
                                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                                        </div>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#instruct_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#page_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn5">
                                                <h5 class="mb-0">Step #5</h5>
                                            </div>
                                            <div id="writing_acc_5" class="collapse" aria-labelledby="btn5" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Pages*</label>
                                                        <input type="number" class="form-control" />
                                                        <small class="form-text text-muted">Enter pages</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#file_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#spacing_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn6">
                                                <h5 class="mb-0">Step #6</h5>
                                            </div>
                                            <div id="writing_acc_6" class="collapse" aria-labelledby="btn6" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Spacing</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select Spacing</option>
                                                            <option>Double</option>
                                                            <option>Single</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select spacing</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#page_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#deadline_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn7">
                                                <h5 class="mb-0">Step #7</h5>
                                            </div>
                                            <div id="writing_acc_7" class="collapse" aria-labelledby="btn7" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Deadline*</label>
                                                        <input type="date" class="form-control" placeholder="Date">
                                                        <small class="form-text text-muted">Select Deadline</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#spacing_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#formating_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn8">
                                                <h5 class="mb-0">Step #8</h5>
                                            </div>
                                            <div id="writing_acc_8" class="collapse" aria-labelledby="btn8" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Formating*</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select Formating</option>
                                                            <option>APA</option>
                                                            <option>MLA</option>
                                                            <option>CHICAGO</option>
                                                            <option>TURABIAN</option>
                                                            <option>IEEE</option>
                                                            <option>OTHER</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select Formating</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#deadline_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#number_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn9">
                                                <h5 class="mb-0">Step #9</h5>
                                            </div>
                                            <div id="writing_acc_9" class="collapse" aria-labelledby="btn9" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Number of Source*</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select Number of source</option>
                                                            <option>APA</option>
                                                            <option>MLA</option>
                                                            <option>CHICAGO</option>
                                                            <option>TURABIAN</option>
                                                            <option>IEEE</option>
                                                            <option>OTHER</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select Number of source</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#formating_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#writer_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn10">
                                                <h5 class="mb-0">Step #10</h5>
                                            </div>
                                            <div id="writing_acc_10" class="collapse" aria-labelledby="btn10" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Writer*</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select Writer</option>
                                                            <option>Standard</option>
                                                            <option>Gold</option>
                                                            <option>Diamond</option>
                                                            <option>Use the last writer</option>
                                                            <option>Best writer</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select Writer</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#number_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#extra_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn11">
                                                <h5 class="mb-0">Step #11</h5>
                                            </div>
                                            <div id="writing_acc_11" class="collapse" aria-labelledby="btn11" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Extras *</label>
                                                        <select name="category" class="form-control custom-select" data-toggle="select" tute-no-empty>
                                                            <option value="">Select Extras</option>
                                                            <option>Add Abstract</option>
                                                            <option>Add Plagiarism Report</option>
                                                            <option>Add Vip customer Service</option>
                                                            <option>Add Early Draft</option>
                                                            <option>Add Detailed Outline</option>
                                                        </select>
                                                        <small class="form-text text-muted">Select Extras</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#writer_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#summary_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn12">
                                                <h5 class="mb-0">Step #12</h5>
                                            </div>
                                            <div id="writing_acc_12" class="collapse" aria-labelledby="btn12" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <textarea name="short_description" class="form-control" cols="100%" rows="5" placeholder="Short description"></textarea>
                                                        <small class="form-text text-muted">Order summary</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#extra_acc">Previous</button>
                                                        <button class="btn btn-primary next" data-target="#promo_acc">Next</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card">
                                            <div class="card-header dark-bg-black-80" id="btn13">
                                                <h5 class="mb-0">Step #13</h5>
                                            </div>
                                            <div id="writing_acc_13" class="collapse" aria-labelledby="btn13" data-parent="#accordion">
                                                <div class="card-body dark-bg-black">
                                                    <div class="form-group">
                                                        <label class="form-label">Insert Promo code*</label>
                                                        <input type="text" class="form-control" />
                                                        <small class="form-text text-muted">Insert promo code</small>
                                                    </div>
                                                    <div class="right">
                                                        <button class="btn btn-primary previous" data-target="#summary_acc">Previous</button>
                                                        <button class="btn btn-primary next">Next</button>
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
             $(document).ready(function() {
    var access = ['btn1'];

    $('.next').on('click', function() {
        var target = $(this).data('target');
        $(target).collapse('show');
        $(this).closest('.card').next().find('.collapse').collapse('show');
        $(this).closest('.card').find('.collapse').collapse('hide');
    });

    $('.previous').on('click', function() {
        var target = $(this).data('target');
        $(target).collapse('show');
        $(this).closest('.card').prev().find('.collapse').collapse('show');
        $(this).closest('.card').find('.collapse').collapse('hide');
    });

    // Handle header clicks to toggle visibility and update the access array
    $('[id^="btn"]').on('click', function() {
        var btnId = $(this).attr('id');
        var btnIndex = parseInt(btnId.replace('btn', ''), 10) - 1;

        if (btnIndex === 0 || access[btnIndex - 1] === "btn" + btnIndex) {
            $('#' + 'writing_acc_' + (btnIndex + 1)).collapse('toggle');
            toggleChevron(this);
            access[btnIndex] = btnId; // Update access
        }
    });

    function toggleChevron(element) {
        var icon = $(element).find('i');
        icon.toggleClass('fa-chevron-down fa-chevron-right');
    }
});

            

        </script>

        {{-- </html> --}}
    @endpush
@endsection
