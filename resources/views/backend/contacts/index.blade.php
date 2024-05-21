@extends('layouts.app')

@section('content')

<?php
    $contact_type = ['', 'By Email', 'Call me on Mobile', 'Call me on Business Phone'];
?>

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">@lang('labels.backend.contacts.title')</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            @lang('labels.backend.contacts.title')
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">@lang('labels.backend.dashboard.title')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        <div class="card mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-name", "js-lists-values-email"]'>

                <table class="table mb-0 thead-border-top-0 table-nowrap" data-page-length='50'>
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">No.</th>
                            <th><a href="javascript:void(0)" class="sort" data-sort="js-lists-values-name">Name</a></th>
                            <th>@lang('labels.backend.table.company')</th>
                            <th>@lang('labels.backend.table.company_email')</th>
                            <th>@lang('labels.backend.table.business_phone')</th>
                            <th>@lang('labels.backend.table.contact_type')</th>
                            <th>@lang('labels.backend.table.actions')</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="clients">
                    @foreach ($contacts as $contact)
                    <tr>
                        <td class="pr-0"></td>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <strong>{{ $contact->name }}</strong>
                        </td>
                        <td>{{ $contact->company }}</td>
                        <td>{{ $contact->company_email }}</td>
                        <td>{{ $contact->business_phone }}</td>
                        <td>{{ $contact_type[$contact->contact_type] }}</td>
                        <td>
                            @include('backend.buttons.edit', ['edit_route' => route('admin.contacts.edit', $contact->id)])
                            @include('backend.buttons.delete', ['delete_route' => route('admin.contacts.destroy', $contact->id)])
                        </td>
                    </tr>
                    @endforeach

                    @if(count($contacts) < 1)
                    <tr>
                        <td colspan="8"><p class="text-center text-black-70 m-0 p-3">No Contacts</p></td>
                    </tr>
                    @endif
                    </tbody>
                </table>
            </div>

            <div class="card-footer p-8pt">
                @if($contacts->hasPages())
                {{ $contacts->links('layouts.parts.page') }}
                @else
                <ul class="pagination justify-content-start pagination-xsm m-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true" class="material-icons">chevron_left</span>
                            <span>@lang('labels.backend.general.prev')</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Page 1">
                            <span>1</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span>@lang('labels.backend.general.next')</span>
                            <span aria-hidden="true" class="material-icons">chevron_right</span>
                        </a>
                    </li>
                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('after-scripts')

<!-- List.js -->
<script src="{{ asset('assets/js/list.min.js') }}"></script>
<script src="{{ asset('assets/js/list.js') }}"></script>

<!-- Tables -->
<script src="{{ asset('assets/js/toggle-check-all.js') }}"></script>
<script src="{{ asset('assets/js/check-selected-row.js') }}"></script>

<script>

$(function() {
    $(document).on('submit', 'form[name="delete_item"]', function(e) {

        e.preventDefault();

        $(this).ajaxSubmit({
            success: function(res) {
                if(res.success) {
                    window.location.reload();
                } else {
                    swal("@lang('labels.backend.general.warning')", res.message, "warning");
                }
            }
        });
    });
})
</script>

@endpush