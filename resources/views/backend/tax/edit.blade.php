@extends('layouts.app')

@section('content')

@push('after-styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/select2/select2.min.css') }}" rel="stylesheet">
@endpush

<?php
    $country_list = get_country_list();
?>

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Tax</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">@lang('labels.backend.dashboard.title')</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.tax.index') }}">Tax List</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Tax
                        </li>
                    </ol>
                </div>
            </div>

            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.tax.index') }}"
                        class="btn btn-outline-secondary">@lang('labels.general.back')</a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-section border-bottom-2">
        <div class="container page__container">
            <div class="row">
                <div class="col-7">

                {!! Form::model($tax, ['method' => 'PATCH', 'route' => ['admin.tax.update', $tax->id]]) !!}

                    <div class="form-group">
                        <label class="form-label">Name:</label>
                        {!! Form::text('name', null, array('placeholder' => 'Tax name','class' => 'form-control')) !!}
                    </div>
                    <div class="form-group">
                        <label class="form-label">Rate (in %):</label>
                        {!! Form::number('rate', null, array('placeholder' => '20','class' => 'form-control')) !!}
                    </div>
                    <hr>
                    <div class="form-group">
                        <label class="form-label">Condition:</label>
                        <select name="condition" class="form-control">
                            <option value="county">Country</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Condition Value:</label>
                        <?php 
                            $values = json_decode($tax->value);
                        ?>
                        <select id="country_list" name="value[]" class="form-control" multiple="multiple">
                        @foreach($country_list as $country)
                            @if(in_array($country, $values))
                            <option value="{{ $country }}" selected>{{ $country }}</option>
                            @else
                            <option value="{{ $country }}">{{ $country }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>

                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

</div>
@push('after-scripts')

<!-- Select2 -->
<script src="{{ asset('assets/js/select2/select2.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2.js') }}"></script>

<script>
    $(function() {
        $('#country_list').select2({ tags: true })
    });
</script>

@endpush

@endsection