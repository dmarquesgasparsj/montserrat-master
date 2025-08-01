@extends('template')
@section('content')
<div class="row bg-cover">
    <div class="col-lg-12">
        <h1>{{ __('messages.edit_permission_title') }} {!! $permission->name !!}</h1>
    </div>
    <div class="col-lg-12">
        <h2>{{ __('messages.permission_details_title') }}</h2>
        {{ html()->form('PUT', route('permission.update', [$permission->id]))->open() }}
        {{ html()->hidden('id', $permission->id) }}
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.name'), 'name') }}
                        {{ html()->text('name', $permission->name)->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.display_name'), 'display_name') }}
                        {{ html()->text('display_name', $permission->display_name)->class('form-control') }}
                    </div>
                    <div class="col-lg-3 col-md-4">
                        {{ html()->label(__('messages.description'), 'description') }}
                        {{ html()->text('description', $permission->description)->class('form-control') }}
                    </div>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-lg-12">
                    {{ html()->input('image', 'btnSave')->class('btn btn-outline-dark')->attribute('src', asset('images/save.png')) }}
                </div>
            </div>
        {{ html()->form()->close() }}
    </div>
</div>
@stop