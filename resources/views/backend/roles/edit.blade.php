@extends('layouts.app')

@section('content')

<!-- Header Layout Content -->
<div class="mdk-header-layout__content page-content ">

    <div class="pt-32pt">
        <div
            class="container page__container d-flex flex-column flex-md-row align-items-center text-center text-sm-left">
            <div class="flex d-flex flex-column flex-sm-row align-items-center mb-24pt mb-md-0">

                <div class="mb-24pt mb-sm-0 mr-sm-24pt">
                    <h2 class="mb-0">Edit Role</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">Home</a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.roles.index') }}"> Role Management</a>
                        </li>

                        <li class="breadcrumb-item active">
                            Edit Role
                        </li>
                    </ol>
                </div>
            </div>
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Go To List</a>
                </div>
            </div>

            @if($role->slug == 'custom')

            @can('role_create')
            <div class="row" role="tablist">
                <div class="col-auto mr-3">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan

            @can('role_delete')
            <div class="row" role="tablist">
                <div class="col-auto">
                    {!! Form::open(['method' => 'DELETE','route' => ['admin.roles.destroy', $role->id],'style'=>'display:inline']) !!}
                        {!! Form::submit('Delete', ['class' => 'btn btn-outline-secondary']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
            @endcan

            @endif
        </div>
    </div>

    <div class="container page__container page-section">

        @include('layouts.parts.alert-messages')

        <div class="page-separator">
            <div class="page-separator__text">Basic Information</div>
        </div>
        <div class="col-md-12 p-0">
            {!! Form::model($role, ['method' => 'PATCH','route' => ['admin.roles.update', $role->id]]) !!}
            <div class="form-group">
                <label class="form-label">Name</label>
                {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
            </div>
            <div class="form-group">
                <label class="form-label">Role Type:</label>
                <select name="type" id="" class="form-control">
                    <option value="user" <?php echo ($role->type == 'user') ? 'selected' : '';  ?> >User</option>
                    <option value="group" <?php echo ($role->type == 'group') ? 'selected' : '';  ?> >Group</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Permission</label>
                
                @foreach($permission as $value)
                <!-- <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                    {{ $value->name }}</label>
                <br /> -->

                <div class="form-group">
                    <div class="custom-control custom-checkbox-toggle custom-control-inline mr-1">
                        <input type="checkbox" id="checkbox_{{ $loop->iteration }}" name="permission[]" 
                        class="custom-control-input" value="{{ $value->id }}"
                        <?php echo in_array($value->id, $rolePermissions) ? 'checked=""' :""; ?>
                        >
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