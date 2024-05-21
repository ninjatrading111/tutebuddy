<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'TuteBuddy LMS') }}</title>

    <!-- App CSS -->
    <link type="text/css" href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
    
    <style>
        body {
            background-color: lightblue;
            background-image: url("{{ asset('images/tutebuddy-bg.jpg') }}");
            background-repeat: no-repeat;
            background-size: auto;
        }
        .logo {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -250px;
            margin-top: -70px;
        }
        .card {
            position: absolute;
            top: 50%;
        }
    </style>
</head>

<body>
    <input type="hidden" class="form-control" id="live_url" value="{{ $join_room }}">
    <img src="{{ asset('images/logo.png') }}" alt="Online Learning Platform" class="logo" />

    @if(auth()->user()->hasRole('Student') && !$is_room_run || auth()->user()->hasRole('Student') && !$is_room_run)
    <div class="card card-body">
        <label class="card-title">Please wait until create meeting room by Instructor...</label>
    </div>
    @endif
    <!-- Setting Modal for Live Lesson -->
    <div class="modal" id="settingModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Meet Settings</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right">How to student join the Metting?: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_join_auto" name="bbb__join_method" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_join_auto" class="custom-control-label">Join Automatically</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_join_wait" name="bbb__join_method" type="radio" class="custom-control-input" value="0">
                                    <label for="live_join_wait" class="custom-control-label">Wait for Instructor to let them in</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Mic on Join: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_mic_on" name="bbb__mic_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_mic_on" class="custom-control-label">On</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_mic_off" name="bbb__mic_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_mic_off" class="custom-control-label">Off</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Speaker on Join: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_speaker_on" name="bbb__speaker_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_speaker_on" class="custom-control-label">On</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_speaker_off" name="bbb__speaker_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_speaker_off" class="custom-control-label">Off</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Camera on Join: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_camera_on" name="bbb__camera_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_camera_on" class="custom-control-label">On</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_camera_off" name="bbb__camera_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_camera_off" class="custom-control-label">Off</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Enable Screensharing for Students: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_screenshare_yes" name="bbb__screenshare_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_screenshare_yes" class="custom-control-label">Yes</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_screenshare_no" name="bbb__screenshare_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_screenshare_no" class="custom-control-label">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Enable File Upload for Students: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_upload_yes" name="bbb__upload_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_upload_yes" class="custom-control-label">Yes</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_upload_no" name="bbb__upload_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_upload_no" class="custom-control-label">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Allow Private Chat: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_chat_yes" name="bbb__chat_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_chat_yes" class="custom-control-label">Yes</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_chat_no" name="bbb__chat_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_chat_no" class="custom-control-label">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Default Presenter: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_instructor_presenter" name="bbb__presenter" type="radio" class="custom-control-input" value="instructor" checked="">
                                    <label for="live_instructor_presenter" class="custom-control-label">Instructor</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_student_presenter" name="bbb__presenter" type="radio" class="custom-control-input" value="student">
                                    <label for="live_student_presenter" class="custom-control-label">Student</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_both_presenter" name="bbb__presenter" type="radio" class="custom-control-input" value="both">
                                    <label for="live_both_presenter" class="custom-control-label">Both</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Enable Whiteboard: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_whiteboard_yes" name="bbb__whiteboard_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_whiteboard_yes" class="custom-control-label">Yes</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_whiteboard_no" name="bbb__whiteboard_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_whiteboard_no" class="custom-control-label">No</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <label for="" class="form-label col-lg-4 text-right d-inline-block">Allow Students to join and Moderators: </label>
                            <div class="custom-controls-stacked form-inline">
                                <div class="custom-control custom-radio">
                                    <input id="live_moderator_allow" name="bbb__moderator_status" type="radio" class="custom-control-input" value="1" checked="">
                                    <label for="live_moderator_allow" class="custom-control-label">Yes</label>
                                </div>
                                <div class="custom-control custom-radio ml-8pt">
                                    <input id="live_moderator_disallow" name="bbb__moderator_status" type="radio" class="custom-control-input" value="0">
                                    <label for="live_moderator_disallow" class="custom-control-label">No</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="form-group">
                        <button id="btn_start" class="btn btn-outline-primary btn-add-new">Start</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
</body>

<script>
    $(function() {
        if("{{ auth()->user()->hasRole('Instructor') }}" == '1') {
            $('#settingModal').toggle();
        }

        if("{{ auth()->user()->hasRole('Student') }}" == '1' && '{{ $is_room_run }}' == '1') {
            redirect();
        }

        if("{{ auth()->user()->hasRole('Child') }}" == '1' && '{{ $is_room_run }}' == '1') {
            redirect();
        }

        $('#btn_start').on('click', function() {
            redirect();
        });
    });
    function redirect() {
        var url = document.getElementById('live_url').value.replace(/\\\//g, "/");
        location.href = url;
    }
</script>

</html>