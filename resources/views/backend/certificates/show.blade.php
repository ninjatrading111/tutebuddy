<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ config('app.name') }} : Certificate of Completion</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>
        @font-face {
            font-family: 'La Jolla ES';
            src: url({{ asset('assets/fonts/certificate/Old-Script.ttf') }});
        }

        @font-face {
            font-family: 'Baskerville Old Face';
            src: url({{ asset('assets/fonts/certificate/BASKVILL.TTF') }});
        }

        body {
            margin: 0px;
            padding: 0px;
            color: #37231a;
        }

        .container-fluid {
            width: 1100px;
            margin: auto;
            top: 50px;
            position: relative;
            box-shadow: 0px 0px 2px 0px black;
            border-radius: 15px;
        }

        .row {
            position: relative;
        }

        .block {
            position: absolute;
            right: 0;
            margin: auto;
            left: 0;
            text-align: center;
        }
    </style>
    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container-fluid px-0">
    <div class="row h-100 justify-content-center text-center position-relative m-0">

        <div class="col-12 align-self-center block" style="top: 36%;">
            <p style="font-family: La Jolla ES; font-size: 62px; color: #4c4c4c;">{{ $data['name'] }}</p>
        </div>
        <div class="col-12 align-self-center block" style="top: 49.5%;">
            <p style="font-family: Baskerville Old Face; font-size: 26px; color: #b68746; margin-right: 62px;">{{ $data['hours'] }}</p>
        </div>

        <div class="col-12 align-self-center block" style="top: 55%;">
            <p style="font-family: Baskerville Old Face; font-size: 24px; color: #b68746;">{{ $data['course_name'] }}</p>
        </div>

        <div class="col-12 align-self-center block" style="top: 60%;">
            <?php
                $qualifications = '';
                if (!empty($data['admin']->qualifications)) {
                    foreach(json_decode($data['admin']->qualifications) as $item) {
                        $qualifications .= $item . ', ';
                    }
                }
            ?>
            <p style="font-family: Baskerville Old Face; font-size: 20px; color: #4c4c4c; padding: 0 15%;">
                By admin: {{ $data['admin']->name }}, 
                {{ $qualifications }}
                {{ $data['admin']->city }},
                {{ $data['admin']->country }}
            </p>
        </div>

        <div class="row block" style="top: 76%;">
            <div class="col-md-6 align-self-center">
                <p style="font-family: Baskerville Old Face; font-size: 24px; color: #714b1d;">{{ $data['date'] }}</p>
            </div>
        </div>

        <div class="col-12 align-self-center block" style="top: 88%;">
            <p style="font-family: Baskerville Old Face; font-size: 20px; color: #714b1d;">Certificate No: {{ $data['cert_number'] }}</p>
        </div>
        
        <img width="100%" src="{{ asset('images/certificate-new.jpg') }}" style="overflow: hidden; z-index: -1;">
    </div>
</div>
</body>
</html>