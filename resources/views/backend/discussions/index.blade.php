@extends('layouts.app')

@section('content')

@push('after-styles')

<!-- jQuery Datatable CSS -->
<link type="text/css" href="{{ asset('assets/plugin/datatables.min.css') }}" rel="stylesheet">

@endpush

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.discussions.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.discussions.title')
                        </li>

                    </ol>

                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.discussions.create') }}" class="btn btn-outline-secondary">
                        @lang('labels.backend.general.new')
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">
        <div class="page-separator">
            <div class="page-separator__text">@lang('labels.backend.discussions.title')</div>
        </div>
        <div class="card dashboard-area-tabs p-relative o-hidden mb-lg-32pt">
            <div class="table-responsive" data-toggle="lists">
                <table id="tbl_topics" class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th>@lang('labels.backend.table.title')</th>
                            <th>@lang('labels.backend.table.topics')</th>
                            <th>@lang('labels.backend.table.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="list"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- // END Header Layout Content -->

@push('after-scripts')

<script src="{{ asset('assets/plugin/datatables.min.js') }}"></script>

<script>
    $(function() {
        var table = $('#tbl_topics').DataTable(
            {
                lengthChange: false,
                searching: false,
                ordering:  false,
                info: false,
                bStateSave: true,
                ajax: "{{ route('admin.table.getTopicsByAjax') }}",
                columns: [
                    { data: 'index'},
                    { data: 'title'},
                    { data: 'topics'},
                    { data: 'action'}
                ],
                oLanguage: {
                    sEmptyTable: "@lang('labels.backend.discussions.no_results')"
                }
            }
        );

        $(document).on('submit', 'form[name="delete_item"]', function(e) {

            e.preventDefault();

            $(this).ajaxSubmit({
                success: function(res) {
                    if(res.success) {
                        table.ajax.reload();
                        $(document).find('.tooltip.show').remove();
                    } else {
                        swal("Warning!", res.message, "warning");
                    }
                }
            });
        });
    });
</script>

@endpush

@endsection