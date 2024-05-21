<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title>{{ config('app.name') }} : Certificate of Completion</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <style>

        @font-face {
            font-family: 'La Jolla ES';
            src: url({{ storage_path('fonts/Old-Script.ttf') }}) format('truetype');
        }

        @font-face {
            font-family: 'Baskerville Old Face';
            src: url({{ storage_path('fonts/BASKVILL.TTF') }}) format('truetype');
        }

        body {
            margin: 0px;
            padding: 0px;
            color: #37231a;
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
    <div style="position: relative;text-align: center" class="row h-100 justify-content-center text-center position-relative m-0">

        <div class="col-12 align-self-center block" style="top: 31%;">
            <p style="font-family: La Jolla ES; font-size: 62px; color: #4c4c4c;">{{ $data['name'] }}</p>
        </div>
        <div class="col-12 align-self-center block" style="top: 46%;">
            <p style="font-family: Baskerville Old Face; font-size: 24px; color: #b68746; margin-right: 62px;">{{ $data['hours'] }}</p>
        </div>

        <div class="col-12 align-self-center block" style="top: 52%;">
            <p style="font-family: Baskerville Old Face; font-size: 24px; color: #b68746;">{{ $data['course_name'] }}</p>
        </div>

        <div class="col-12 align-self-center block" style="top: 59%;">
            <?php
                $qualifications = '';
                if (!empty($data['instructor']->qualifications)) {
                    foreach(json_decode($data['instructor']->qualifications) as $item) {
                        $qualifications .= $item . ', ';
                    }
                }
            ?>
            <p style="font-family: Baskerville Old Face; font-size: 20px; color: #4c4c4c; padding: 0 15%;">
                By Instructor: {{ $data['instructor']->name }}, 
                {{ $qualifications }}
                {{ $data['instructor']->city }},
                {{ $data['instructor']->country }}
            </p>
        </div>

        <div class="col-12 align-self-center block" style="top: 71%; width: 50%;">
            <p style="font-family: Baskerville Old Face; font-size: 24px; color: #714b1d;">{{ $data['date'] }}</p>
        </div>

        <div class="col-12 align-self-center block" style="top: 82.5%;">
            <p style="font-family: Baskerville Old Face; font-size: 18px; color: #714b1d;">Certificate No: {{ $data['cert_number'] }}</p>
        </div>

        <img width="100%" src="{{ public_path('images/certificate-new.jpg') }}" style="overflow: hidden; z-index: -1;">
    </div>
</div>
</body>
</html>