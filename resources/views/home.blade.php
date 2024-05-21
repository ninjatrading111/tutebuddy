@extends('layouts.admin.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <button type="button" class="btn btn-success text-white icon-16pt" id="add" >Add New Writer</button>
                    <div class="modal fade" id="modal_childs" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">@lang('labels.frontend.course.select_child')</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div id="childs_container" class="form-group p-3 font-size-16pt">
                                        <!-- Childs -->
                                    </div>
                                </div>
                    
                                <div class="modal-footer">
                                    <div class="form-group">
                                        <button id="btn_child_ok" class="btn btn-outline-primary">@lang('labels.frontend.course.buy_now')</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@push('after-scripts')
<script>
    $(document).ready(function(){
        $('#add').on('click',function(){
                    $('#modal_childs').modal('toggle');
                })
    })
</script>
@endpush

@endsection
