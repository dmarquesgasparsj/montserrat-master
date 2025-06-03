@extends('template')
@section('content')

<div class="row bg-cover">
    <div class="col-lg-12">
        @can('update-role')
            <h1>
                {{ __('messages.role_details') }} <strong><a href="{{url('admin/role/'.$role->id.'/edit')}}">{{ $role->name }}</a></strong>
            </h1>
        @else
            <h1>
                {{ __('messages.role_details') }} <strong>{{$role->name}}</strong>
            </h1>
        @endCan
    </div>
    <div class="col-lg-12 mb-4">
        @can('update-role')
            <a href="{{ action([\App\Http\Controllers\RoleController::class, 'edit'], $role->id) }}" class="btn btn-light">{{ __('messages.update_role') }}</a>
        @endcan
        @can('delete-role')
            {{ html()->form('DELETE', route('role.destroy', [$role->id]))->attribute('onsubmit', 'return ConfirmDelete()')->class('d-inline')->open() }}
                {{ html()->submit(__('messages.delete_role'))->class('btn btn-danger') }}
            {{ html()->form()->close() }}
        @endcan
    </div>
    <div class="col-lg-12">
        <h5>{{ __('messages.name_label') }} {{$role->name}}</h5>
        <h5>{{ __('messages.display_name_label') }} {{$role->display_name}}</h5>
        <h5>{{ __('messages.description_label') }} {{$role->description}}</h5>
    </div>
    @can('manage-permission')             
        <div class="col-lg-12">
            {{ html()->form('POST', url('admin/role/update_permissions', ))->open() }}
                {{ html()->hidden('id', $role->id) }}
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-8">
                            {{ html()->label($role->name . ' ' . __('messages.permissions_label_suffix'), 'permissions') }}
                            {{ html()->multiselect('permissions[]', $permissions, $role->permissions->pluck('id')->toArray())->id('permissions')->class('form-control select2') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{ html()->submit(__('messages.update_permissions'))->class('btn btn-light') }}
                    </div>
                </div>
            {{ html()->form()->close() }}
        </div>
        <div class="col-lg-12 mt-5">
            {{ html()->form('POST', url('admin/role/update_users'))->open() }}
            {{ html()->hidden('id', $role->id) }}
                <div class="form-group">
                    <div class="row">
                        <div class="col-lg-8">
                            {{ html()->label(__('messages.users_with_role_prefix') . $role->name . __('messages.users_with_role_suffix'), 'users') }}
                            {{ html()->multiselect('users[]', $users, $role->users->pluck('id')->toArray())->id('users')->class('form-control select2') }}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        {{ html()->submit(__('messages.update_users'))->class('btn btn-light') }}
                    </div>
                </div>
            {{ html()->form()->close() }}
        </div>
    @endCan
</div>
@stop