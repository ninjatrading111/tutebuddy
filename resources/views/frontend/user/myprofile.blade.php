@extends('layouts.app')

@section('content')
    <!-- Header Layout Content -->
    <div class="mdk-header-layout__content page-content ">

        <div class="page-section bg-primary">
            <div
                class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">

                <div class="flex mb-32pt mb-md-0">
                    <h2 class="text-white mb-0">HHHH</h2>
                    <p class="lead text-white-50 d-flex align-items-center">

                    </p>
                </div>
                <!-- <a href="" class="btn btn-outline-white">Follow</a> -->
            </div>
        </div>

        <div class="page-section">
            <div class="container page__container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="page-separator mb-6">
                            <div class="page-separator__text">@lang('labels.frontend.profile.personal')</div>
                        </div>
                        <div class="card border-1 border-left-3 border-left-accent mb-lg-0">
                            <div class="card-body">
                                <form id="personal_form" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body">
                                        <div class="form-group">
                                            <span id='output'></span>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="first_name">@lang('labels.auth.register.first_name'):</label>
                                            <input id="first_name" name="first_name" type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                placeholder="@lang('labels.auth.register.first_name_placeholder')" required>
                                            @error('first_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="last_name">@lang('labels.auth.register.last_name'):</label>
                                            <input id="last_name" name="last_name" type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                placeholder="@lang('labels.auth.register.last_name_placeholder')" required>
                                            @error('last_name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="email">@lang('labels.auth.register.your_email'):</label>
                                            <input id="email" name="email" type="text"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="@lang('labels.auth.register.your_email_placeholder')" required>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="mobile_number">@lang('labels.auth.register.mobile_number'):</label>
                                            <input id="mobile_number" name="mobile_number" type="text"
                                                class="form-control @error('mobile_number') is-invalid @enderror"
                                                placeholder="@lang('labels.auth.register.mobile_number_placeholder')" required>
                                            @error('mobile_number')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="image">Thumbnail:</label>
                                            <div class="custom-file">
                                                <input type="file" name="image" id="image"
                                                    class="custom-file-input" data-preview="#display_course_image"
                                                    accept=".jpg, .jpeg, .png" required>
                                                <label for="image"
                                                    class="custom-file-label">@lang('labels.backend.general.choose_file')</label>
                                            </div>

                                        </div>
                                        <input type='submit' id="personal_btn" class='btn btn-info' value='Update Data' />
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="page-separator mb-6">
                            <div class="page-separator__text">@lang('labels.frontend.profile.password')</div>
                        </div>
                        <div class="card border-1 border-top-3 border-top-primary mb-lg-0">
                            {{-- <div class="card-body"> --}}
                            <form id="password_form" method="post">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label class="form-label" for="password">@lang('labels.auth.register.password'):</label>
                                        <input id="password" name="password" type="text"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="@lang('labels.auth.register.password_placeholder')" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="password">@lang('labels.auth.register.password'):</label>
                                        <input id="password" name="password" type="text"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="@lang('labels.auth.register.password_placeholder')" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" for="password">@lang('labels.auth.register.password'):</label>
                                        <input id="password" name="password" type="text"
                                            class="form-control @error('password') is-invalid @enderror"
                                            placeholder="@lang('labels.auth.register.password_placeholder')" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <input type='button' id='password_btn' class='btn btn-success'
                                        value='Save Password' />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('after-scripts')
        <script>
            $(document).ready(function() {
                $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });
                $('#personal_form').on('submit', function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    console.log(formData,'formdata');
                    $.ajax({
                        url: 'myprofile/fetchdata', // Replace with your Laravel endpoint
                        type: 'POST',
                        data: formData,
                        dataType:'json',
                        processData: false, // Important to prevent jQuery from converting the FormData object into a string
                        contentType: false, // Important to prevent jQuery from setting content-type header
                        success: function(res) {
                            // console.log(res.error);
                            var out_html=''
                            if(res.error.length>0){
                                // alert('dd')
                                for(i=0;i<res.error.length;i++){
                                    out_html+=res.error[i]
                                }
                                console.log(out_html)
                                $('#output').html(out_html)
                            }else{
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
