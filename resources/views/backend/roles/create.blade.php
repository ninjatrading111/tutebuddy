@extends('layouts.app')

@push('after-styles')

<style>

</style>

@endpush

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Create Role</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}"> Role Management</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Create Role
                        </li>
                    </ol>
                </div>
            </div>
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container page__container page-section">

        @include('layouts.parts.alert-messages')

        <div class="page-separator">
            <div class="page-separator__text">Create New Role</div>
        </div>
        <div class="col-md-12 p-0">
            {!! Form::open(array('route' => 'admin.roles.store','method'=>'POST')) !!}
            <div class="form-group">
                <label class="form-label">Role Name:</label>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <label class="form-label">Role Type:</label>
                <select name="type" id="" class="form-control">
                    <option value="user" selected>User</option>
                    <option value="group">Group</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Permissions:</label>
                @foreach($permission as $value)
                <div class="form-group">
                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                        <input type="checkbox" id="checkbox_{{ $loop->iteration }}" name="permission[]" class="custom-control-input" value="{{ $value->id }}">
                        <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}">&nbsp;</label>
                    </div>
                    <label class="form-label mb-0" for="checkbox_{{ $loop->iteration }}">{{ $value->name }}</label>
                </div>
                @endforeach
            </div>
            <div class="form-group text-left mt-4">
                <button type="submit" class="btn btn-accent">Save changes</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

@endsection

@push('after-scripts')

@endpush