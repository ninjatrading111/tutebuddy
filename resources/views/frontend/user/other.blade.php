@extends('layouts.app')

@section('content')
<style>
    
a:focus {
	outline: none !important;
	outline-offset: none !important;
}

body {
	background: #f5f6f5;
	color: #333;
}

/* helper classses */

.margin-top-20 {
	margin-top: 20px;
}

.margin-bottom-20 {
	margin-top: 20px;
}

.no-margin {
	margin: 0px;
}

/* box component */

.box {
	border-color: #e6e6e6;
	background: #FFF;
	border-radius: 6px;
	box-shadow: 0 2px 4px rgba(0, 0, 0, 0.25);
	padding: 10px;
	margin-bottom: 40px;
}

.box-center {
	margin: 20px auto;
}

/* input [type = file]
----------------------------------------------- */

input[type=file] {
	display: block !important;
	right: 1px;
	top: 1px;
	height: 34px;
	opacity: 0;
  width: 100%;
	background: none;
	position: absolute;
  overflow: hidden;
  z-index: 2;
}

.control-fileupload {
	display: block;
	border: 1px solid #d6d7d6;
	background: #FFF;
	border-radius: 4px;
	width: 100%;
	height: 36px;
	line-height: 36px;
	padding: 0px 10px 2px 10px;
  overflow: hidden;
  position: relative;
  
  &:before, input, label {
    cursor: pointer !important;
  }
  /* File upload button */
  &:before {
    /* inherit from boostrap btn styles */
    padding: 4px 12px;
    margin-bottom: 0;
    font-size: 14px;
    line-height: 20px;
    color: #333333;
    text-align: center;
    text-shadow: 0 1px 1px rgba(255, 255, 255, 0.75);
    vertical-align: middle;
    cursor: pointer;
    background-color: #f5f5f5;
    background-image: linear-gradient(to bottom, #ffffff, #e6e6e6);
    background-repeat: repeat-x;
    border: 1px solid #cccccc;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    border-bottom-color: #b3b3b3;
    border-radius: 4px;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.2), 0 1px 2px rgba(0, 0, 0, 0.05);
    transition: color 0.2s ease;

    /* add more custom styles*/
    content: 'Browse';
    display: block;
    position: absolute;
    z-index: 1;
    top: 2px;
    right: 2px;
    line-height: 20px;
    text-align: center;
  }
  &:hover, &:focus {
    &:before {
      color: #333333;
      background-color: #e6e6e6;
      color: #333333;
      text-decoration: none;
      background-position: 0 -15px;
      transition: background-position 0.2s ease-out;
    }
  }
  
  label {
    line-height: 24px;
    color: #999999;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    position: relative;
    z-index: 1;
    margin-right: 90px;
    margin-bottom: 0px;
    cursor: text;
  }
}
    </style>
    <!-- Header Layout Content -->
    <div class="mdk-header-layout__content page-content ">

        <div class="page-section bg-primary">
            <div
                class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-md-left">

                <div class="flex mb-32pt mb-md-0">
                    <h2 class="text-white mb-0">Apple</h2>
                    <p class="lead text-white-50 d-flex align-items-center">

                    </p>
                </div>
                <!-- <a href="" class="btn btn-outline-white">Follow</a> -->
            </div>
        </div>

        <div class="page-section">
            <div class="container page__container">
                <div class="row">
                    <div class="col-md-12 mx-auto">
                        <div class="page-separator mb-6 ">
                            <div class="page-separator__text">Other Page</div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <form id="personal_form" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Acedamic Level*</label>
                                                <select name="category" class="form-control custom-select"
                                                    data-toggle="select" tute-no-empty>
                                                    <option value="">Select level</option>
                                                    <option>HTML</option>
                                                    <option>CSS</option>
                                                    <option>jQuery</option>
                                                    <option>Javascript</option>
                                                </select>
                                                <small class="form-text text-muted">Select Acedemic level</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">deadline</label>
                                                <input type="date" class="form-control" placeholder="Date">
                                                <small class="form-text text-muted">Select Acedemic level</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                                    <small class="form-text text-muted">Select Formating </small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
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
                                                <small class="form-text text-muted">Select NUmber of sourcel</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Extras*</label>
                                                <select name="category" class="form-control custom-select"
                                                    data-toggle="select" tute-no-empty>
                                                    <option value="">Select level</option>
                                                    <option>HTML</option>
                                                    <option>CSS</option>
                                                    <option>jQuery</option>
                                                    <option>Javascript</option>
                                                </select>
                                                <small class="form-text text-muted">Select Extras level</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-group">
                                                    <label class="form-label">file*</label>
                                                    <span class="control-fileupload">
                                                        <label for="file">Choose a file :</label>
                                                        <input type="file" id="file">
                                                    </span>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="comment" class="text-main text-bold mar-no">Comment:</label>
                                        <div id="summernote"><p>Hello Summernote</p></div>
                                    </div>
                                    <input required type='submit' id="personal_btn" class='btn btn-info'
                                        value='Update Data' />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('after-scripts')
        <script>
            $(document).ready(function() {
                $('#summernote').summernote({
                    placeholder: 'Hello Bootstrap 5',
        tabsize: 4,
        height: 200
                });
                $(function() {
                    $('input[type=file]').change(function() {
                        var t = $(this).val();
                        var labelText = 'File : ' + t.substr(12, t.length);
                        $(this).prev('label').text(labelText);
                    })
                });
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



