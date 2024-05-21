@extends('layouts.admin2.app')
@section('link')
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection
@section('content')
    <!-- Header Layout Content -->
    <div class="page-section">
        <div class="container  d-flex flex-column flex-md-row align-items-center text-center text-md-left">
            <span id='toast'></span>
            <div class="flex ">
                <h2 class="text-primary mb-0">Manage All Clients of Here</h2>
                <p class="lead text-primary-50 d-flex align-items-center float-right">
                    <button type="button" class="btn btn-success text-white " id="add">Add New
                        Client</button>
                <div class="modal fade" id="modal_user" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content ">
                            <div class="modal-header">
                                <h5 class="modal-title">@lang('labels.frontend.course.select_child')</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form method='post' id='client_form' action='/'>

                                <div class="modal-body">
                                    <span id='output'></span>
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name :</label>
                                        <input type="text" id='first_name' name='first_name' class="form-control"
                                            placeholder="Enter Frist Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name :</label>
                                        <input type="text" id='last_name' name='last_name' class="form-control"
                                            placeholder="Enter Last Name">
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="form-label">Email :</label>
                                        <input type="email" id="email" name='email' class="form-control"
                                            placeholder="Enter email">
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="form-label">Password :</label>
                                        <input type="password" id="password" name='password' class="form-control"
                                            placeholder="Enter Password">
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <div class="form-group">
                                        <input type='hidden' id="update_id" name='update_id' value='' />
                                        <input type='hidden' id="action" name='action' value='insert' />
                                        <input type='submit' id="action_btn" class="btn btn-success" value='Add' />
                                        <button id="btn_child_ok" class="btn btn-outline-primary"
                                            data-dismiss="modal">close</button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
                </p>
            </div>
            <!-- <a href="" class="btn btn-outline-white">Follow</a> -->
        </div>
        <div class="container page__container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-separator mb-6">
                        <div class="page-separator__text">All Clients List</div>
                    </div>
                    <div class="card  mb-lg-0">
                        <div class="card-body">
                            <table id="myTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>created_at</th>
                                        <th>updated_at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </div>
    </div>
    @push('after-scripts')
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script> --}}
        <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
        <script>
            $(document).ready(function() {
                $(function() {
                    var table = $('#myTable').DataTable({
                        processing: true,
                        serverSide: true,
                        ajax: "/dashboard/client/getdata",
                        columns: [{
                                data: 'DT_RowIndex',
                                name: 'DT_RowIndex'
                            },
                            {
                                data: 'email',
                                name: 'email'
                            },
                            {
                                data: 'role',
                                name: 'role'
                            },
                            {
                                data: 'created_at',
                                name: 'created_at'
                            },
                            {
                                data: 'updated_at',
                                name: 'updated_at'
                            },
                            {
                                data: 'action',
                                name: 'action'
                            },
                        ]
                    });
                });

                $('#add').on('click', function() {
                    $('#modal_user').modal('show');
                    $('.modal-title').html('Add New Client!');
                    $('#client_form')[0].reset();
                    $('#action_btn').val('Add');
                    $('#action').val('insert');
                    $('#output').html('');
                })
                $(document).on('submit', '#client_form', function(e) {
                    e.preventDefault();
                    var form_data = $('#client_form').serialize();
                    $.ajax({
                        url: '/dashboard/client/postdata',
                        method: 'post',
                        data: form_data,
                        dataType: 'json',
                        success: function(res) {
                            if (res.error.length > 0) {
                                var html_out = ''
                                for (i = 0; i < res.error.length; i++) {
                                    html_out += res.error[i];
                                }
                                $('#output').html(html_out)
                            } else {
                                $('#output').html(res.success);
                                $('#client_form')[0].reset();
                                $('#myTable').DataTable().ajax.reload();
                                $('#action').val('insert');
                                $('#action_btn').val('Add');
                                $('.modal-title').html('Add New Client!');
                            }
                        }
                    })
                })
                $(document).on('click','.edit',function(){
                    var id=$(this).attr('data-id');
                    $.ajax({
                        url:'/dashboard/client/fetchdata',
                        method:'post',
                        data:{id:id},
                        dataType:'json',
                        success:function(res){
                            console.log(res)
                            if(res){
                                $('#modal_user').modal('show');
                                $('#update_id').val(res.id);
                                $('#first_name').val(res.first_name)
                                $('#last_name').val(res.last_name)
                                $('#email').val(res.email)
                                $('#password').val('');
                                $('.modal-title').html('Edit Client');
                                $('#action_btn').val('update');
                                $('#action').val('update');
                            }
                        }
                    })
                })
                $(document).on('click','.delete',function(){
                    var id=$(this).attr('data-id');
                    // console.log(id);
                    $.ajax({
                        url:'/dashboard/client/removedata',
                        method:'post',
                        data:{id:id},
                        dataType:'json',
                        success:function(res){
                            console.log(res.success)
                            if(res.success){
                                alert(res.success)
                                var html_out="<div class='toast'>Deleted successfully</div>"
                                $('#toast').html(html_out);
                                $('#myTable').DataTable().ajax.reload();
                            }
                        }
                    })
                })
            })
        </script>
    @endpush
@endsection
