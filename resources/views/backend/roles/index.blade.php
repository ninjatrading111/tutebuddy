@extends('layouts.app')

@push('after-styles')
<style>
    [dir=ltr] .page-link {
        padding: .1rem .5rem;
    }
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
                    <h2 class="mb-0">Role Management</h2>

                    <ol class="breadcrumb p-0 m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>

                        <li class="breadcrumb-item active">
                            Role Management
                        </li>
                    </ol>
                </div>
            </div>
            @can('role_create')
            <div class="row" role="tablist">
                <div class="col-auto">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-outline-secondary">Add New</a>
                </div>
            </div>
            @endcan
        </div>
    </div>

    <div class="container page__container page-section">
        
        <div class="card mb-lg-32pt">

            <div class="table-responsive" data-toggle="lists" data-lists-values='["js-lists-values-no", "js-lists-values-role"]'>

                <table class="table mb-0 thead-border-top-0">
                    <thead>
                        <tr>
                            <th style="width: 18px;" class="pr-0"></th>
                            <th style="width: 40px;">
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-no">No</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-role">Role</a>
                            </th>
                            <th>
                                <a href="javascript:void(0)" class="sort" data-sort="js-lists-values-type">Type</a>
                            </th>
                            <th style="white-space: normal;">Permissions</th>
                            <th style="width: 150px;">Action</th>
                        </tr>
                    </thead>
                    <tbody class="list" id="clients">
                        @foreach ($roles as $key => $role)
                        <?php
                            $rolePermissions = Spatie\Permission\Models\Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
                                ->where("role_has_permissions.role_id", $role->id)
                                ->get();

                            $str_permission = '';

                            foreach($rolePermissions as $permission) {
                                $str_permission .= $permission->name . ', ';
                            }

                            if($str_permission == '') {
                                $str_permission = 'None';
                            }

                            if(count($rolePermissions) == Spatie\Permission\Models\Permission::count()) {
                                $str_permission = 'All';
                            }
                        ?>
                        <tr>
                            <td class="pr-0"></td>
                            <td class="js-lists-values-no">{{ $loop->iteration }}</td>
                            <td style="white-space: nowrap;" class="js-lists-values-role">{{ $role->name }}</td>
                            <td style="text-transform: capitalize;" class="js-lists-values-type">{{ $role->type }}</td>
                            <td>{{ $str_permission }}</td>
                            <td>
                                @can('role_edit')
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.roles.edit', $role->id) }}">
                                    Edit
                                </a>
                                @endcan

                                @can('role_delete')
                                @if($role->slug == 'custom')
                                {!! Form::open(['method' => 'DELETE','route' => ['admin.roles.destroy', $role->id],'style'=>'display:inline']) !!}
                                    {!! Form::submit('Delete', ['class' => 'btn btn-danger btn-sm']) !!}
                                {!! Form::close() !!}
                                @endif
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="card-footer p-8pt">
                @if($roles->hasPages())
                {{ $roles->links('layouts.parts.page') }}
                @else
                <ul class="pagination justify-content-start pagination-xsm m-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Previous">
                            <span aria-hidden="true" class="material-icons">chevron_left</span>
                            <span>Prev</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Page 1">
                            <span>1</span>
                        </a>
                    </li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#" aria-label="Next">
                            <span>Next</span>
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

@endpush